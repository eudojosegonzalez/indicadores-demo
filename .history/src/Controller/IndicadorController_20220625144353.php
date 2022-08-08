<?php

namespace App\Controller;

use App\Entity\Indicador;
use App\Form\IndicadorType;
use App\Repository\IndicadorRepository;
use App\Repository\RegistroRepository;

use App\Repository\EnteRepository;
use App\Repository\CategoriaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/indicador")
 */
class IndicadorController extends AbstractController
{
    /**
     * @Route("/", name="app_indicador_index", methods={"GET"})
     */
    public function index(
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
        return $this->render('indicador/index.html.twig', [
            'indicadors' => $indicadores,
            'canReg' => $canReg,
            'entes' => $entes,
            'enteId' => $enteId,
            'categorias' => $categorias,
            'categoriaId' => $categoriaId,
        ]);
    }

    /**
     * @Route("/new", name="app_indicador_new", methods={"GET", "POST"})
     */
    public function new(Request $request, IndicadorRepository $indicadorRepository, EnteRepository $enteRepository): Response
    {
        $indicador = new Indicador();
        $form = $this->createForm(IndicadorType::class, $indicador);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $indicadorRepository->add($indicador, true);

            return $this->redirectToRoute('app_indicador_index', [], Response::HTTP_SEE_OTHER);
        }
        $entes = $enteRepository->findAll();
        return $this->renderForm('indicador/new.html.twig', [
            'indicador' => $indicador,
            'form' => $form,
            'entes' => $entes,
        ]);
    }

    /**
     * @Route("/{id}", name="app_indicador_show", methods={"GET"})
     */
    public function show(Indicador $indicador): Response
    {
        return $this->render('indicador/show.html.twig', [
            'indicador' => $indicador,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_indicador_edit", methods={"GET", "POST"})
     */
    public function edit(
        Request $request,
        Indicador $indicador,
        IndicadorRepository $indicadorRepository,
        CategoriaRepository $categoriaRepository,
        EnteRepository $enteRepository
    ): Response {
        $entes = $enteRepository->findAll();
        $form = $this->createForm(IndicadorType::class, $indicador);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $indicadorRepository->add($indicador, true);

            return $this->redirectToRoute('app_indicador_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('indicador/edit.html.twig', [
            'indicador' => $indicador,
            'form' => $form,
            'entes' => $entes,
        ]);
    }

    /**
     * @Route("/{id}", name="app_indicador_delete", methods={"POST"})
     */
    public function delete(Request $request, Indicador $indicador, IndicadorRepository $indicadorRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $indicador->getId(), $request->request->get('_token'))) {
            $indicadorRepository->remove($indicador, true);
        }

        return $this->redirectToRoute('app_indicador_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/get_categoria/", name="get_categoria", methods={"POST"})
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
            $categorias = $categoriaRepository->findAllCategoriaByEnte($id);
        } else {
            $categorias = NULL;
        }
        $response = new Response(json_encode($categorias));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/delete_indicador/", name="app_delete_indicador", methods={"POST"})
     */
    public function deleteIndicador(
        Request $request,
        ManagerRegistry $doctrine,
        IndicadorRepository $indicadorRepository,
        RegistroRepository $registroRepository
    ): Response {
        $params = $request->request->all();
        $idIndicador = $params['id'];
        $registro = $indicadorRepository->find($idIndicador);
        if ($registro) {
            $registros2 = $registroRepository->findBy(['indicador' => $registro]);
            if ($registros2) {
                $salida = array('-1');
            } else {
                //--- se puede borrar ---- 
                $entityManager = $doctrine->getManager();
                $entityManager->remove($registro);
                $entityManager->flush();
                $salida = array('1');
            }
        } else {
            $salida = array('0');
        }

        $response = new Response(json_encode($salida));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/oculta_indicador/", name="app_oculta_indicador", methods={"POST"})
     */
    public function ocultaIndicador(
        Request $request,
        ManagerRegistry $doctrine,
        IndicadorRepository $indicadorRepository,
        RegistroRepository $registroRepository
    ): Response {
        $params = $request->request->all();
        $idIndicador = $params['id'];
        $registro = $indicadorRepository->find($idIndicador);
        if ($registro) {
            //--- se puede ocultar ---- 
            $registro->setVisible(0);
            $entityManager = $doctrine->getManager();
            $entityManager->flush($registro);
            $salida = array('1');
        } else {
            $salida = array('0');
        }

        $response = new Response(json_encode($salida));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/muestra_indicador/", name="app_muestra_indicador", methods={"POST"})
     */
    public function muestraIndicador(
        Request $request,
        ManagerRegistry $doctrine,
        IndicadorRepository $indicadorRepository,
        RegistroRepository $registroRepository
    ): Response {
        $params = $request->request->all();
        $idIndicador = $params['id'];
        $registro = $indicadorRepository->find($idIndicador);
        if ($registro) {
            $registros2 = $registroRepository->findBy(['indicador' => $registro]);
            if ($registros2) {
                //--- se puede visualizar ---- 
                $registro->setVisible(1);
                $entityManager = $doctrine->getManager();
                $entityManager->flush($registro);
                $salida = array('1');
            } else {
                $salida = array('-1');
            }
        } else {
            $salida = array('0');
        }

        $response = new Response(json_encode($salida));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
