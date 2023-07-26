<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IdField,
    DateTimeField,
    TextField,
    TextEditorField,
    ChoiceField,
    TimezoneField,
    HiddenField,
    ImageField
};
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractCrudController
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('uid')->setSortable(false),
            TextField::new('password')
                ->setFormTypeOption('empty_data', '')
                ->setRequired($pageName === Crud::PAGE_NEW)
                ->onlyOnForms(),
            TextField::new('password', 'password2')->hideOnIndex()->hideOnForm(),
            ChoiceField::new('roles')
                ->setChoices([
                    'Admin' => 'ROLE_ADMIN',
                    'Moderator' => 'ROLE_MODERATOR',
                    'Nutzer' => 'ROLE_USER'
                ])
                ->renderAsBadges([
                    'Admin' => 'warning',
                ])
                ->allowMultipleChoices()
                ->setSortable(false),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('User')
            ->setEntityLabelInPlural('User')
            ->setEntityPermission('ROLE_ADMIN')
            ->setPageTitle('index', 'User')
            ->setDefaultSort(['id' => 'DESC'])
            ->setPaginatorPageSize(30);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User && $entityInstance->getPassword() !== '') {
            $hashedPassword = $this->passwordHasher->hashPassword($entityInstance, $entityInstance->getPassword());
            $entityInstance->setPassword($hashedPassword);
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User && $entityInstance->getPassword() !== '') {
            $hashedPassword = $this->passwordHasher->hashPassword($entityInstance, $entityInstance->getPassword());
            $entityInstance->setPassword($hashedPassword);
        }

        parent::updateEntity($entityManager, $entityInstance);
    }
}
