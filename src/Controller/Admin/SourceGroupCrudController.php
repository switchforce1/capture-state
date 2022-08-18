<?php

namespace App\Controller\Admin;

use App\Entity\SourceGroup;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SourceGroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SourceGroup::class;
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
