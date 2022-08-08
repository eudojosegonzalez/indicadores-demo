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
 * @Route("/publico")
 */
class PublicController extends AbstractController
{
    /**
     * @Route("/", name="app_public", methods={"GET"})
     */
    public function index(EnteRepository $enteRepository): Response
    {
        $entes = $enteRepository->findAll();
        return $this->render('publico/index.html.twig', [
            'controller_name' => 'DashboardPublicController',
            'entes' => $entes
        ]);
    }

    /**
     * @Route("/public_ente", name="app_public_ente", methods={"GET"})
     */
    public function indexEnte(EnteRepository $enteRepository): Response
    {
        $entes = $enteRepository->findAll();
        return $this->render('publico/index_ente.html.twig', [
            'controller_name' => 'DashboardPublicController',
            'entes' => $entes
        ]);
    }
}
