<?php

namespace App\Controller\Admin;

use App\Builder\Capture\SnapshotBuilder;
use App\Entity\Capture\Source;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class SourceCrudController extends AbstractCrudController
{
    private const ACTION_CREATE_SNAPSHOT = 'create-snapshot';
    public static function getEntityFqcn(): string
    {
        return Source::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('label');
        yield TextField::new('typeCode')->setValue('JSON_URL');
        yield TextField::new('method');
        yield TextField::new('url');
        yield AssociationField::new('sourceGroup');
        yield AssociationField::new('tags');
    }

    public function configureActions(Actions $actions): Actions
    {
        $createSnapshotAction = Action::new('CreateSnapshot', 'Create snapshot')
            ->setIcon('fa fa-camera')
            ->linkToCrudAction('createSnapshotAction');
        $createSelectedSnapshotsAction = Action::new('createSelectedSnapshots', 'Create snapshot')
            ->setIcon('fa fa-camera')
            ->linkToCrudAction('createSelectedSnapshots');
        return parent::configureActions($actions)
            ->add(Crud::PAGE_INDEX, $createSnapshotAction)
        ;
    }

    public function createSnapshotAction(AdminContext $adminContext, SnapshotBuilder $snapshotBuilder)
    {
        $id = $adminContext->getRequest()->query->get('entityId');
        /** @var Source $source */
        $source = $this->container->get('doctrine')
            ->getRepository(Source::class)
            ->find($id);

        $snapshot = $snapshotBuilder->buildSnapshot($source);

        if (!empty($snapshot)) {
            $this->persistEntity($this->container->get('doctrine')->getManagerForClass($adminContext->getEntity()->getFqcn()), $snapshot);
            $this->addFlash('success', 'Snapshot created');
        } else {
            $this->addFlash('warning', 'Can not create snapshot !!!');
        }


        $url = $this->container->get(AdminUrlGenerator::class)
            ->setController(SourceCrudController::class)
            ->setAction(Action::INDEX)
            ->generateUrl();

        return $this->redirect($url);
    }

    /**
     * @param AdminContext $adminContext
     * @param SnapshotBuilder $snapshotBuilder
     * @return void
     */
    public function createSelectedSnapshots(AdminContext $adminContext, SnapshotBuilder $snapshotBuilder)
    {

    }
}
