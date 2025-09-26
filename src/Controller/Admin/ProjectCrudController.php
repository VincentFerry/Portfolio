<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class ProjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title', 'Titre')
                ->setRequired(true)
                ->setHelp('Le titre de votre projet'),
            TextareaField::new('description', 'Description')
                ->setRequired(true)
                ->setNumOfRows(4)
                ->setHelp('Description détaillée du projet'),
            ImageField::new('image', 'Image')
                ->setBasePath('images/projects/')
                ->setUploadDir('public/images/projects/')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setHelp('Image de présentation du projet (formats acceptés: jpg, png, webp)')
                ->setFormTypeOptions([
                    'upload_filename' => function($originalFilename, $formData) {
                        return 'project-'.uniqid().'.'.$originalFilename->guessExtension();
                    }
                ]),
            ArrayField::new('technologies', 'Technologies')
                ->setHelp('Liste des technologies utilisées (ex: PHP, Symfony, JavaScript)'),
            UrlField::new('githubUrl', 'URL GitHub')
                ->setHelp('Lien vers le repository GitHub (optionnel)'),
            UrlField::new('demoUrl', 'URL Démo')
                ->setHelp('Lien vers la démo en ligne (optionnel)'),
            BooleanField::new('featured', 'Projet mis en avant')
                ->setHelp('Afficher ce projet en premier sur la page d\'accueil'),
            BooleanField::new('published', 'Publié')
                ->setHelp('Rendre ce projet visible sur le site'),
            IntegerField::new('sortOrder', 'Ordre d\'affichage')
                ->setHelp('Plus le nombre est petit, plus le projet apparaît en premier (0 = premier)')
                ->setFormTypeOptions(['attr' => ['min' => 0]]),
            DateTimeField::new('createdAt', 'Créé le')->hideOnForm(),
            DateTimeField::new('updatedAt', 'Modifié le')->hideOnForm(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Projet')
            ->setEntityLabelInPlural('Projets')
            ->setPageTitle('index', 'Gestion des Projets')
            ->setPageTitle('new', 'Créer un nouveau projet')
            ->setPageTitle('edit', 'Modifier le projet')
            ->setPageTitle('detail', 'Détails du projet')
            ->setDefaultSort(['sortOrder' => 'ASC', 'createdAt' => 'DESC'])
            ->setPaginatorPageSize(10);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setIcon('fa fa-plus')->setLabel('Nouveau projet');
            })
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setIcon('fa fa-edit');
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setIcon('fa fa-trash');
            });
    }
}
