<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Builder\Capture\ComparisonBuilder;
use App\Entity\Capture\Comparison;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

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
//        $deleteSelectedItemsAction = Action::new('deleteSelectedItemsAction', 'Delete selected items')
//            ->setIcon('fa fa-trash')
//            ->linkToCrudAction('deleteSelected')
//        ;

        return parent::configureActions($actions)
            ->add(Crud::PAGE_DETAIL, $refreshDataAction)
//            ->addBatchAction($deleteSelectedItemsAction)
        ;
    }

    public function refreshDataAction(AdminContext $adminContext, ComparisonBuilder $comparisonBuilder)
    {
        $id = $adminContext->getRequest()->query->get('entityId');
        /** @var Comparison $comparison */
        $comparison = $this->container->get('doctrine')
            ->getRepository(Comparison::class)
            ->find($id);
        $comparison = $comparisonBuilder->refreshComparisonData($comparison);

        $this->persistEntity($this->container->get('doctrine')->getManagerForClass($adminContext->getEntity()->getFqcn()), $comparison);
        $this->addFlash('success', 'Comparison data successfully refreshed.');

        $url = $this->container->get(AdminUrlGenerator::class)
            ->setController(ComparisonCrudController::class)
            ->setAction(Action::DETAIL)
            ->setEntityId($comparison->getId())
            ->generateUrl();

        return $this->redirect($url);
    }

//    public function deleteSelectedItemsAction(AdminContext $context, BatchActionDto $batchActionDto)
//    {
//        $event = new BeforeCrudActionEvent($context);
//        $this->container->get('event_dispatcher')->dispatch($event);
//        if ($event->isPropagationStopped()) {
//            return $event->getResponse();
//        }
//
//        if (!$this->isCsrfTokenValid('ea-batch-action-'.Action::BATCH_DELETE, $batchActionDto->getCsrfToken())) {
//            return $this->redirectToRoute($context->getDashboardRouteName());
//        }
//
//        $ids = $batchActionDto->getEntityIds();
//
//    }

    public function configureAssets(Assets $assets): Assets
    {
        return parent::configureAssets($assets)
            ->addWebpackEncoreEntry('admin_compare');
    }
}
