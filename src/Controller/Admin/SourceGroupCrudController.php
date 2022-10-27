<?php

namespace App\Controller\Admin;

use App\Entity\SourceGroup;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SourceGroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SourceGroup::class;
    }

    public function configureFields(string $pageName): iterable
    {
        if (in_array($pageName, [Crud::PAGE_INDEX, Crud::PAGE_DETAIL])) {
            return [
                TextField::new('name'),
                TextField::new('description'),
                DateTimeField::new('createdAt'),
                DateTimeField::new('updatedAt'),
                AssociationField::new('sources'),
            ];
        }
        return [
            TextField::new('name'),
            TextField::new('description'),
            DateTimeField::new('createdAt'),
            DateTimeField::new('updatedAt'),
        ];
    }
    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
