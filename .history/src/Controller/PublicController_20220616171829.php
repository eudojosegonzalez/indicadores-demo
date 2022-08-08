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
     * @Route("/public_ente/", name="app_public_ente", methods={"GET"})
     */
    public function indexPublicEnte(EnteRepository $enteRepository): Response
    {
        $entes = $enteRepository->findAll();
        return $this->render('publico/index_ente.html.twig', [
            'controller_name' => 'DashboardPublicController',
            'entes' => $entes
        ]);
    }

    /**
     * @Route("/public_categoria/", name="app_public_categoria", methods={"GET"})
     */
    public function indexPublicCategoria(EnteRepository $enteRepository, CategoriaRepository $categoriaRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $entes = $enteRepository->findAll();
        //$misRegistros = $registroRepository->findAll();
        $canReg = $request->query->getInt('can_reg', 20);
        $misRegistros = $categoriaRepository->findAll();
        // Paginar los resultados de la consulta
        $categorias = $paginator->paginate(
            // Consulta Doctrine, no resultados
            $misRegistros,
            // Definir el parámetro de la página
            $request->query->getInt('page', 1),
            // Items per page
            $canReg
        );
        return $this->render('publico/index_categoria.html.twig', [
            'categorias' => $categorias,
            'canReg' => $canReg,
            'entes' => $entes
        ]);
    }
}
