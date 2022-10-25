<?php

namespace App\Controller\Admin;

use App\Entity\SourceGroupComparison;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SourceGroupComparisonCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SourceGroupComparison::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('reason'),
            TextField::new('code'),
            AssociationField::new('sourceGroupSnapshot1'),
            AssociationField::new('sourceGroupSnapshot2'),
        ];
    }
}
