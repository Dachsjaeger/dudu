<?php

namespace App\Controller\Admin;

use App\Entity\Aufgabe;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class AufgabeController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Aufgabe::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('user_Id', 'User'),
            TextField::new('datum', 'Bis'),
            TextField::new('aufgabe', 'Aufgabe')->setSortable(false),
            TextField::new('bildName', 'Datei'),
            ImageField::new('bildName', 'Bild (falls vorhanden)')
                ->setUploadDir('public/uploads/')
                ->setBasePath('uploads/')
                ->setSortable(false)
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return Crud::new()
            ->setEntityLabelInSingular('Aufgabe')
            ->setEntityLabelInPlural('Aufgaben')
            ->setEntityPermission('ROLE_ADMIN')
            ->setPageTitle('index', 'Aufgaben')
            ->setPaginatorPageSize(30);
    }
}
