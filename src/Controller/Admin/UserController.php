<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\Extension\Core\Type\{
    PasswordType,
    RepeatedType
};
use Symfony\Component\Form\{
    FormBuilderInterface,
    FormEvent,
    FormEvents
};
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
use EasyCorp\Bundle\EasyAdminBundle\Config\{KeyValueStore, Action, Actions};

class UserController extends AbstractCrudController
{
    public function __construct(
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->passwordHasher = $passwordHasher;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('uid')->setSortable(false),
            TextField::new('password')
                ->setFormType(PasswordType::class)
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
                ->setSortable(false)
        ];
    }

    public function createNewFormBuilder(
        EntityDto $entityDto,
        KeyValueStore $formOptions,
        AdminContext $context
    ): FormBuilderInterface {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    public function createEditFormBuilder(
        EntityDto $entityDto,
        KeyValueStore $formOptions,
        AdminContext $context
    ): FormBuilderInterface {
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    private function addPasswordEventListener(FormBuilderInterface $formBuilder): FormBuilderInterface
    {
        return $formBuilder->addEventListener(FormEvents::POST_SUBMIT, $this->hashPassword());
    }

    private function hashPassword()
    {
        return function ($event) {
            $form = $event->getForm();
            if (!$form->isValid()) {
                return;
            }

            if ($form->get('password')->getData() != '') {
                $password = $form->get('password')->getData();
                $hash = $this->passwordHasher->hashPassword($this->getUser(), $password);
                $form->getData()->setPassword($hash);
            }
        };
    }

    public function configureCrud(Crud $crud): Crud
    {
        return Crud::new()
            ->setEntityLabelInSingular('User')
            ->setEntityLabelInPlural('User')
            ->setEntityPermission('ROLE_ADMIN')
            ->setPageTitle('index', 'User')
            ->setDefaultSort(['id' => 'DESC'])
            ->setPaginatorPageSize(30);
    }
}
