<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    public const ROLES = [
        'ROLE_ADMIN' => 'ROLE_ADMIN',
        'ROLE_USER' => 'ROLE_USER',
    ];

    public function __construct(private UserPasswordHasherInterface $passwordEncoder) 
    {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }
    
    public function configureFields(string $pageName): iterable
    {
        yield FormField::addColumn('col-md-6');
        yield FormField::addFieldset('Account');
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('username');
        yield EmailField::new('email');
        
        yield Field::new('plainPassword')->setFormType(PasswordType::class)->setRequired(Crud::PAGE_NEW === $pageName)->onlyOnForms();
        yield FormField::addColumn('col-md-6');
        yield FormField::addFieldset('Permissions');
        yield ChoiceField::new('roles')->setChoices(self::ROLES)->allowMultipleChoices();
        yield BooleanField::new('isVerified');
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('detail', 'User')
            ->setPageTitle('edit', 'Edit')
            ->setPageTitle('new', 'Create')
            ->showEntityActionsInlined()
        ;
    }
    public function configureActions(Actions $actions): Actions
    {
        return $actions->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
            return $action->setLabel('Add');
        })
        ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
            return $this->canDelete($action);
        })
        ->update(Crud::PAGE_DETAIL, Action::DELETE, function (Action $action) {
            return $this->canDelete($action);
        })
        ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->setPassword($entityInstance);
        $entityInstance->setRoles(array_values($entityInstance->getRoles()));

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->setPassword($entityInstance);
        $entityInstance->setRoles(array_values($entityInstance->getRoles()));

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function setPassword(User $entity): void
    {
        $plainPassword = $entity->getPlainPassword();
        if ($plainPassword !== null) {
            $entity->setPassword($this->passwordEncoder->hashPassword($entity, $plainPassword));
        }

        $entity->eraseCredentials();
    }

    private function canDelete(Action $action): Action
    {
        return $action->displayIf(static function (User $entity) {
            $roles = $entity->getRoles();

            return !in_array('ROLE_ADMIN', $roles);
        });
    }
}
