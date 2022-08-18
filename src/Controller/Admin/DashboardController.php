<?php

namespace App\Controller\Admin;

use App\Entity\Comparison;
use App\Entity\Snapshot;
use App\Entity\Source;
use App\Entity\SourceGroup;
use App\Entity\Tag;
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
        phpinfo();
        die();
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
        yield MenuItem::section('Captures');
        yield MenuItem::linkToCrud('SourceGroups', 'fas fa-list', SourceGroup::class)->setAction('index');
        yield MenuItem::linkToCrud('Sources', 'fas fa-list', Source::class);
        yield MenuItem::linkToCrud('Comparisons', 'fas fa-list', Comparison::class);
        yield MenuItem::linkToCrud('Snapshot', 'fa fa-list', Snapshot::class);

        yield MenuItem::section('Tags');
        yield MenuItem::linkToCrud('Tags', 'fas fa-tag', Tag::class);
    }
}
