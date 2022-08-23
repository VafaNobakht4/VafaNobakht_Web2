<?php

namespace App\Controller\Admin;

use App\Entity\User;
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
        return $this->render('admin/dashbord.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Welcome dear Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoRoute('Users', 'fas fa-list', 'users_list');
        yield MenuItem::linktoRoute('Categories', 'fas fa-list', 'category_list');
        yield MenuItem::linktoRoute('Add product', 'fas fa-add', 'new_product');
        yield MenuItem::linktoRoute('Add Category', 'fas fa-add', 'new_category');
    }
}
