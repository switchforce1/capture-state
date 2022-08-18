<?php

namespace App\Controller\Admin;

use App\Entity\Snapshot;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SnapshotCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Snapshot::class;
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
