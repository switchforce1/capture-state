<?php

namespace App\Controller\Admin;

use App\Entity\Comparison;
use App\Entity\Snapshot;
use App\Entity\Source;
use App\Entity\SourceGroup;
use App\Entity\SourceGroupComparison;
use App\Entity\SourceGroupSnapshot;
use App\Entity\Tag;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard/index.html.twig', [
            'dashboard_controller_filepath' => (new \ReflectionClass(static::class))->getFileName(),
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Snapshot Dashboard');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('MDM - Snapshot data', 'fa fa-dashboard');

        // CAPTURES
        yield MenuItem::section('Captures');
        yield MenuItem::linkToCrud('SourceGroups', 'fa fa-archive', SourceGroup::class)
            ->setAction('index');
        yield MenuItem::linkToCrud('Sources', 'fa fa-dot-circle', Source::class);
        yield MenuItem::linkToCrud('Snapshots', 'fa fa-camera', Snapshot::class);
        yield MenuItem::linkToCrud('Comparisons', 'fa fa-binoculars', Comparison::class);

        // GROUPS
        yield MenuItem::section('Groups');
        yield MenuItem::linkToCrud('SourceGroupSnapshots', 'fa fa-layer-group', SourceGroupSnapshot::class)
            ->setAction('index');
        yield MenuItem::linkToCrud('SourceGroupComparisons', 'fa fa-list-check', SourceGroupComparison::class);

        // TAGS
        yield MenuItem::section('Tags');
        yield MenuItem::linkToCrud('Tags', 'fas fa-tag', Tag::class);
    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }


}
