<?php

namespace App\Controller;


use App\Repository\EnteRepository;
use App\Repository\CategoriaRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/dashboard_admin")
 */
class DashboardAdminController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('dashboard_admin/index.html.twig', [
            'controller_name' => 'DashboardAdminController',
        ]);
    }

    /**
     * @Route("/get_categoria", name="get_categoria", methods={"POST"})
     */
    public function getCategorias(
        Request $request,
        IndicadorRepository $indicadorRepository,
        EnteRepository $enteRepository,
        CategoriaRepository $categoriaRepository
    ): Response {
        $params = $request->request->all();
        $id = intval($params['enteid']);
        $ente = $enteRepository->find($id);
        if (!is_null($ente)) {
            $categorias = $categoriaRepository->findBy(['ente' => $ente]);
        }
        $response = new Response(json_encode($categorias));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
