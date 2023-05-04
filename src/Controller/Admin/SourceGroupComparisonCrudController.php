<?php

namespace App\Controller\Admin;

use App\Builder\Capture\ComparisonBuilder;
use App\Entity\Capture\Comparison;
use App\Entity\Capture\Snapshot;
use App\Entity\Capture\SourceGroupComparison;
use App\Factory\Capture\ComparisonFactory;
use App\Formatter\Capture\SourceGroupSnapshotFormatter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

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

    public function configureActions(Actions $actions): Actions
    {
        $createComparisonsAction = Action::new('CreateComparisons', 'Create group comparisons')
            ->setIcon('fa fa-camera')
            ->linkToCrudAction('createComparisons');
        return parent::configureActions($actions)
            ->add(Crud::PAGE_INDEX, $createComparisonsAction)
            ;
    }

    public function createComparisons(
        AdminContext                 $adminContext,
        ComparisonBuilder            $comparisonBuilder,
        SourceGroupSnapshotFormatter $sourceGroupSnapshotFormatter,
        ComparisonFactory            $comparisonFactory
    ) {
        $id = $adminContext->getRequest()->query->get('entityId');
        /** @var SourceGroupComparison $sourceGroupComparison */
        $sourceGroupComparison = $this->container->get('doctrine')
            ->getRepository(SourceGroupComparison::class)
            ->find($id);
        $sourceGroupSnapshot1 = $sourceGroupComparison->getSourceGroupSnapshot1();
        $formattedSourceSnapshots1 = $sourceGroupSnapshotFormatter->getFormattedSourceSnapshots($sourceGroupSnapshot1);
        $sourceGroupSnapshot2 = $sourceGroupComparison->getSourceGroupSnapshot2();
        $formattedSourceSnapshots2 = $sourceGroupSnapshotFormatter->getFormattedSourceSnapshots($sourceGroupSnapshot2);

        $comparisons = [];
        /**
         * @var int $sourceId
         * @var Snapshot $snapShot1
         */
        foreach ($formattedSourceSnapshots1 as $sourceId => $snapShot1) {
            if (
                !array_key_exists($sourceId, $formattedSourceSnapshots2) ||
                ! $snapShot1 instanceof Snapshot ||
                ! $formattedSourceSnapshots2[$sourceId] instanceof Snapshot
            ) {
                continue;
            }
            $source = $snapShot1->getSource();
            $snapShot2 = $formattedSourceSnapshots2[$sourceId];

            $reason = sprintf(
                '%s : %s => {%s} VS {%s} at %s',
                $sourceGroupComparison->getReason(),
                $source->getLabel(),
                $snapShot1->getCreatedAt()->format('Y-m-d H:i:s'),
                $snapShot1->getCreatedAt()->format('Y-m-d H:i:s'),
                (new \DateTime())->format('Y-m-d H:i:s')
            );
            $comparison = $comparisonFactory->create($snapShot1, $snapShot2);
            $comparison
                ->setSource($source)
                ->setReason($reason);

            try {
                $this->persistEntity(
                    $this->container->get('doctrine')->getManagerForClass(Comparison::class),
                    $comparison
                );
                $comparisonBuilder->refreshComparisonData($comparison);
                $comparisons[] = $comparisons;
            } catch (\Exception $exception) {
                continue;
            }
        }
        $this->addFlash('success', sprintf(' %s snapshot comparison(s) created', count($comparisons)));

        $url = $this->container->get(AdminUrlGenerator::class)
            ->setController(SourceGroupComparisonCrudController::class)
            ->setAction(Action::INDEX)
            ->generateUrl();

        return $this->redirect($url);
    }
}
