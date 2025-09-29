<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use App\Entity\ProjectImage;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
        $fields = [
            IdField::new('id')->hideOnForm(),
            TextField::new('title', 'Titre (Français)')
                ->setRequired(true)
                ->setHelp('Le titre de votre projet en français'),
            TextField::new('titleEn', 'Titre (Anglais)')
                ->setRequired(false)
                ->setHelp('Le titre de votre projet en anglais (optionnel)')
                ->hideOnIndex(), // Masquer sur la liste pour gagner de la place
            TextareaField::new('description', 'Description (Français)')
                ->setRequired(true)
                ->setNumOfRows(4)
                ->setHelp('Description détaillée du projet en français')
                ->hideOnIndex(), // Masquer sur la liste pour gagner de la place
            TextareaField::new('descriptionEn', 'Description (Anglais)')
                ->setRequired(false)
                ->setNumOfRows(4)
                ->setHelp('Description détaillée du projet en anglais (optionnel)')
                ->hideOnIndex(), // Masquer sur la liste pour gagner de la place
            
            AssociationField::new('images', 'Images du projet')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ])
                ->setHelp('Gérer les images de ce projet - Utilisez le menu "Images des Projets" pour ajouter/modifier les images')
                ->hideOnForm(),
            ArrayField::new('technologies', 'Technologies')
                ->setHelp('Liste des technologies utilisées (ex: PHP, Symfony, JavaScript)')
                ->hideOnIndex(), // Masquer sur la liste pour gagner de la place
            UrlField::new('githubUrl', 'URL GitHub')
                ->setHelp('Lien vers le repository GitHub (optionnel)')
                ->hideOnIndex(), // Masquer sur la liste pour gagner de la place
            UrlField::new('demoUrl', 'URL Démo')
                ->setHelp('Lien vers la démo en ligne (optionnel)')
                ->hideOnIndex(), // Masquer sur la liste pour gagner de la place
            BooleanField::new('featured', 'Mis en avant')
                ->setHelp('Afficher ce projet en premier sur la page d\'accueil'),
            BooleanField::new('published', 'Publié')
                ->setHelp('Rendre ce projet visible sur le site'),
            IntegerField::new('sortOrder', 'Ordre')
                ->setHelp('Plus le nombre est petit, plus le projet apparaît en premier (0 = premier)')
                ->setFormTypeOptions(['attr' => ['min' => 0]]),
            DateTimeField::new('createdAt', 'Créé le')->hideOnForm()->hideOnIndex(),
            DateTimeField::new('updatedAt', 'Modifié le')->hideOnForm()->hideOnIndex(),
        ];

        return $fields;
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
            ->setPaginatorPageSize(10)
            ->showEntityActionsInlined(); // Affiche les actions dans chaque ligne
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setIcon('fa fa-plus')->setLabel('Nouveau projet');
            })
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setIcon('fa fa-edit')->setLabel('');
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setIcon('fa fa-trash')->setLabel('');
            })
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
                return $action->setIcon('fa fa-eye')->setLabel('');
            })
            ->reorder(Crud::PAGE_INDEX, [Action::DETAIL, Action::EDIT, Action::DELETE]);
    }
}
