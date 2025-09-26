<?php

namespace App\Controller\Admin;

use App\Entity\ProjectImage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

#[IsGranted('ROLE_ADMIN')]
class ProjectImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProjectImage::class;
    }

    public function persistEntity($entityManager, $entityInstance): void
    {
        // Debug avant sauvegarde
        if ($entityInstance instanceof ProjectImage) {
            error_log("EasyAdmin: Tentative de sauvegarde ProjectImage");
            error_log("Filename: " . ($entityInstance->getFilename() ?? 'NULL'));
            error_log("Project: " . ($entityInstance->getProject()?->getId() ?? 'NULL'));
            
            // Vérifier si le fichier existe
            if ($entityInstance->getFilename()) {
                $filePath = __DIR__ . '/../../../public/images/projects/' . $entityInstance->getFilename();
                error_log("Fichier existe: " . (file_exists($filePath) ? 'OUI' : 'NON'));
                error_log("Chemin: " . $filePath);
            }
        }
        
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            
            AssociationField::new('project', 'Projet')
                ->setRequired(true)
                ->setHelp('Sélectionnez le projet auquel cette image appartient'),
            
            ImageField::new('filename', 'Image')
                ->setBasePath('/images/projects/')
                ->setUploadDir('public/images/projects')
                ->setUploadedFileNamePattern('[uuid].[extension]')
                ->setRequired(true)
                ->setHelp('Formats acceptés: JPG, PNG, WebP | Taille max: 2MB'),
            
            TextField::new('alt', 'Texte alternatif')
                ->setHelp('Description de l\'image pour l\'accessibilité (optionnel)')
                ->setRequired(false),
            
            BooleanField::new('isPrimary', 'Image principale')
                ->setHelp('⭐ Une seule image principale par projet (affichée en premier)'),
            
            IntegerField::new('sortOrder', 'Ordre d\'affichage')
                ->setHelp('Plus le nombre est petit, plus l\'image apparaît en premier (0 = premier)')
                ->setFormTypeOptions(['attr' => ['min' => 0, 'value' => 0]]),
                
            Field::new('formattedFileSize', 'Taille du fichier')
                ->hideOnForm()
                ->setTemplatePath('admin/field/file_status.html.twig'),
                
            DateTimeField::new('createdAt', 'Créé le')->hideOnForm(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Image de projet')
            ->setEntityLabelInPlural('Images des projets')
            ->setPageTitle('index', 'Gestion des Images')
            ->setPageTitle('new', 'Ajouter une nouvelle image')
            ->setPageTitle('edit', 'Modifier l\'image')
            ->setPageTitle('detail', 'Détails de l\'image')
            ->setDefaultSort(['project' => 'ASC', 'sortOrder' => 'ASC', 'createdAt' => 'DESC'])
            ->setPaginatorPageSize(20);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setIcon('fa fa-plus')->setLabel('Nouvelle image');
            })
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setIcon('fa fa-edit');
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setIcon('fa fa-trash');
            });
    }
}
