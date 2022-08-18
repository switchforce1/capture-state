<?php

namespace App\Controller\Admin;

use App\Entity\Comparison;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ComparisonCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comparison::class;
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
