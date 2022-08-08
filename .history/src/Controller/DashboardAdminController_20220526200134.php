<?php

namespace App\Controller;


use App\Repository\EnteRepository;
use App\Repository\CategoriaRepository;
use App\Repository\IndicadorRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/dashboard_admin")
 */
class DashboardAdminController extends AbstractController
{
    /**
     * @Route("/", name="app_dashboard_admin", methods={"GET"})
     */
    public function index(EnteRepository $enteRepository): Response
    {
        $entes = $enteRepository->findAll();
        return $this->render('dashboard_admin/index.html.twig', [
            'controller_name' => 'DashboardAdminController',
            'entes' => $entes
        ]);
    }
}
