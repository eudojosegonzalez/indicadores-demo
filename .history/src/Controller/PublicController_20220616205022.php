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

    /**
     * @Route("/public_indicador", name="app_public_indicador", methods={"GET"})
     */
    public function indexPublicIndicador(
        IndicadorRepository $indicadorRepository,
        PaginatorInterface $paginator,
        EnteRepository $enteRepository,
        CategoriaRepository $categoriaRepository,
        Request $request
    ): Response {
        //$misRegistros = $registroRepository->findAll();
        $canReg = $request->query->getInt('can_reg', 20);
        $enteId = $request->query->get('enteId', '-99');
        $categoriaId = $request->query->get('categoriaId', '-99');
        if ($enteId != '-99') {
            if ($categoriaId != '-99') {
                $misRegistros = $indicadorRepository->findAllIndicadorEnteCategoria($enteId, $categoriaId);
            } else {
                $misRegistros = $indicadorRepository->findAllIndicadorEnte($enteId);
            }
        } else {
            $misRegistros = $indicadorRepository->findAllIndicador();
        }

        // Paginar los resultados de la consulta
        $indicadores = $paginator->paginate(
            // Consulta Doctrine, no resultados
            $misRegistros,
            // Definir el parámetro de la página
            $request->query->getInt('page', 1),
            // Items per page
            $canReg
        );
        $entes = $enteRepository->findAll();
        if ($enteId != '-99') {
            $categorias = $categoriaRepository->findAllCategoriaByEnte($enteId);
        } else {
            $categorias = NULL;
        }
        return $this->render('publico/index_indicador.html.twig', [
            'indicadors' => $indicadores,
            'canReg' => $canReg,
            'entes' => $entes,
            'enteId' => $enteId,
            'categorias' => $categorias,
            'categoriaId' => $categoriaId,
        ]);
    }

    /**
     * @Route("/public_busca_datos/", name="app_public_busca_datos", methods={"GET","POST"})
     */
    public function searchDatos(
        Request $request
    ): Response {
        $params = $request->request->all();
        $enteId = intval($params['id']);
        $url = $this->generateUrl('app_public_busca_datos2', [
            'id' => $enteId
        ]);
        $respuesta = new Response($url);
        return $respuesta;
    }

    /**
     * @Route("/public_busca_dato2/", name="app_public_busca_datos2", methods={"GET","POST"})
     */
    public function searchDatos2(
        Request $request,
        EnteRepository $enteRepository,
        CategoriaRepository $categoriaRepository,
        PeriodoRepository $periodoRepository
    ): Response {
        $entes = $enteRepository->findAll();
        $periodos = $periodoRepository->findAll();
        $enteId = intval($request->query->get('id'));
        $ente = $enteRepository->find($enteId);
        $categorias = $categoriaRepository->findAllCategoriaByEnte($enteId);

        return $this->renderForm('publico/busca_datos.html.twig', [
            'entes' => $entes,
            'ente' => $ente,
            'categorias' => $categorias,
            'periodos' => $periodos,
        ]);
    }

    /**
     * @Route("/public_busca_indicadores/", name="app_public_busca_indicadores", methods={"POST"})
     */
    public function buscaPublicIndicadores(
        Request $request,
        RegistroRepository $registroRepository
    ): Response {

        $params = $request->request->all();
        $enteId = intval($params['enteId']);
        $categoriaId = intval($params['categoriaId']);
        $periodoId = intval($params['periodoId']);
        $registros = $registroRepository->findAllRegistroByEnteCategoriaPeriodo($enteId, $categoriaId, $periodoId);
        if (empty($registros)) {
            $registros = $registroRepository->findAllRegistroByEnteCategoria($enteId, $categoriaId);
        }
        $salida = array($registros);
        $response = new Response(json_encode($salida));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
