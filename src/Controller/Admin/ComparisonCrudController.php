<?php

namespace App\Controller\Admin;

use App\Entity\Comparison;
use App\Helper\ComparisonHelper;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Exception\ForbiddenActionException;
use EasyCorp\Bundle\EasyAdminBundle\Exception\InsufficientEntityPermissionException;
use EasyCorp\Bundle\EasyAdminBundle\Factory\EntityFactory;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Security\Permission;

class ComparisonCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comparison::class;
    }

    public function configureFields(string $pageName): iterable
    {
        if (Crud::PAGE_DETAIL === $pageName) {
            return [
                TextField::new('reason'),
                AssociationField::new('source'),
                AssociationField::new('snapshot1'),
                AssociationField::new('snapshot2'),
                DateTimeField::new('createdAt'),
                DateTimeField::new('updatedAt'),
                TextareaField::new('mainData')
                    ->addCssClass('compare-main-data'),
                TextareaField::new('revertData')
                    ->addCssClass('compare-revert-data')
            ];
        }
        return [
            TextField::new('reason'),
            AssociationField::new('source'),
            AssociationField::new('snapshot1'),
            AssociationField::new('snapshot2'),
            DateTimeField::new('createdAt'),
            DateTimeField::new('updatedAt'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $refreshDataAction = Action::new('RefreshData', 'Refresh compare data')
            ->setIcon('fa fa-cycle')
            ->linkToCrudAction('refreshDataAction')
        ;

        return parent::configureActions($actions)
            ->add(Crud::PAGE_DETAIL, $refreshDataAction);
    }

    public function refreshDataAction(AdminContext $adminContext, ComparisonHelper $comparisonHelper)
    {
        $id = $adminContext->getRequest()->query->get('entityId');
        /** @var Comparison $comparison */
        $comparison = $this->container->get('doctrine')
            ->getRepository(Comparison::class)
            ->find($id);
        $comparison = $comparisonHelper->refreshComparisonData($comparison);

        $this->persistEntity($this->container->get('doctrine')->getManagerForClass($adminContext->getEntity()->getFqcn()), $comparison);
        $this->addFlash('success', 'Comparison data successfully refreshed.');

        $url = $this->container->get(AdminUrlGenerator::class)
            ->setController(ComparisonCrudController::class)
            ->setAction(Action::DETAIL)
            ->setEntityId($comparison->getId())
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureAssets(Assets $assets): Assets
    {
        return parent::configureAssets($assets)
            ->addWebpackEncoreEntry('admin_compare');
    }


}
