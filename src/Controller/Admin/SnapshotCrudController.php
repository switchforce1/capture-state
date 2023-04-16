<?php

namespace App\Controller\Admin;

use App\Entity\Snapshot;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SnapshotCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Snapshot::class;
    }

    public function configureFields(string $pageName): iterable
    {
        if ($pageName === Crud::PAGE_INDEX) {
            return [
                TextField::new('uuid'),
                AssociationField::new('source'),
                AssociationField::new('sourceGroupSnapshot'),
                DateTimeField::new('createdAt'),
                DateTimeField::new('updatedAt'),
            ];
        }
        if ($pageName == Crud::PAGE_DETAIL) {
            return [
                TextField::new('uuid'),
                AssociationField::new('source'),
                AssociationField::new('sourceGroupSnapshot'),
                DateTimeField::new('createdAt'),
                DateTimeField::new('updatedAt'),
                TextareaField::new('rawData'),
            ];
        }
        return [
            AssociationField::new('source'),
            DateTimeField::new('createdAt'),
            DateTimeField::new('updatedAt'),
        ];
    }
}
