<?php

namespace App\Controller;


use App\Repository\EnteRepository;
use App\Repository\CategoriaRepository;
use App\Repository\IndicadorRepository;
use App\Repository\PeriodoRepository;
use App\Repository\RegistroRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/dashboard_admin")
 */
class PublicController extends AbstractController
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
