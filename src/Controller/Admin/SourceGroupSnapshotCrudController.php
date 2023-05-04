<?php

namespace App\Controller\Admin;

use App\Builder\Capture\SnapshotBuilder;
use App\Entity\Capture\Snapshot;
use App\Entity\Capture\Source;
use App\Entity\Capture\SourceGroupSnapshot;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class SourceGroupSnapshotCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SourceGroupSnapshot::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $createSnapshotAction = Action::new('CreateSnapshot', 'Create snapshots')
            ->setIcon('fa fa-camera')
            ->linkToCrudAction('createSnapshotsAction');
        return parent::configureActions($actions)
            ->add(Crud::PAGE_INDEX, $createSnapshotAction)
            ;
    }

    public function createSnapshotsAction(
        AdminContext $adminContext,
        SnapshotBuilder $snapshotBuilder
    ) {
        $id = $adminContext->getRequest()->query->get('entityId');
        /** @var SourceGroupSnapshot $sourceGroupSnapshot */
        $sourceGroupSnapshot = $this->container->get('doctrine')
            ->getRepository(SourceGroupSnapshot::class)
            ->find($id);
        $existingSnapshots =  $sourceGroupSnapshot->getSnapshots();
        if (!$existingSnapshots->isEmpty()) {
            $this->addFlash(
                'warning',
                'Snapshots already exist for this sourceGroupSnapshot. You must create another one'
            );
        } else {
            $sourceGroup = $sourceGroupSnapshot->getSourceGroup();
            $sources = $sourceGroup->getSources();
            /** @var Source $source */
            foreach ($sources as $source) {
                /** @var Snapshot $snapshot */
                $snapshot = $snapshotBuilder->buildSnapshot($source);
                if (!empty($snapshot)) {
                    $snapshot->setSourceGroupSnapshot($sourceGroupSnapshot);
                    $this->persistEntity(
                        $this->container->get('doctrine')->getManagerForClass($adminContext->getEntity()->getFqcn()),
                        $snapshot
                    );
                }
            }
            $this->addFlash('success', 'Snapshot created');
        }

        $url = $this->container->get(AdminUrlGenerator::class)
            ->setController(SourceGroupSnapshotCrudController::class)
            ->setAction(Action::INDEX)
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureFields(string $pageName): iterable
    {
        if (in_array($pageName, [Crud::PAGE_DETAIL, Crud::PAGE_INDEX])) {
            return [
                TextField::new('name'),
                TextField::new('code'),
                AssociationField::new('sourceGroup'),
                AssociationField::new('snapshots'),
            ];
        }
        return [
            TextField::new('name'),
            TextField::new('code'),
            AssociationField::new('sourceGroup'),
        ];
    }
}
