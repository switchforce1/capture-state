<?php

namespace App\Controller\Admin;

use App\Entity\Snapshot;
use App\Entity\Source;
use App\Entity\SourceGroupSnapshot;
use App\Helper\SnapshotHelper;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Exception\ForbiddenActionException;
use EasyCorp\Bundle\EasyAdminBundle\Exception\InsufficientEntityPermissionException;
use EasyCorp\Bundle\EasyAdminBundle\Factory\EntityFactory;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Security\Permission;

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
        SnapshotHelper $snapshotHelper,
        EntityManagerInterface $entityManager
    ) {
        $id = $adminContext->getRequest()->query->get('entityId');
        /** @var SourceGroupSnapshot $sourceGroupSnapshot */
        $sourceGroupSnapshot = $this->container->get('doctrine')
            ->getRepository(SourceGroupSnapshot::class)
            ->find($id);
        $existingSnapshots =  $sourceGroupSnapshot->getSnapshots();
        if (!$existingSnapshots->isEmpty()) {
            $this->addFlash('warning', 'Snapshots already exist for this sourceGroupSnapshot');
        } else {
            $sourceGroup = $sourceGroupSnapshot->getSourceGroup();
            $sources = $sourceGroup->getSources();
            /** @var Source $source */
            foreach ($sources as $source) {
                /** @var Snapshot $snapshot */
                $snapshot = $snapshotHelper->buildSnapshot($source);
                if (!empty($snapshot)) {
                    $snapshot->setSourceGroupSnapshot($sourceGroupSnapshot);
                    $this->persistEntity($this->container->get('doctrine')->getManagerForClass($adminContext->getEntity()->getFqcn()), $snapshot);
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
