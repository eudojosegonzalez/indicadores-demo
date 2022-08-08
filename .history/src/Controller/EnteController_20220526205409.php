<?php

namespace App\Controller;

use App\Entity\Ente;
use App\Form\EnteType;
use App\Repository\EnteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/ente")
 */
class EnteController extends AbstractController
{
    /**
     * @Route("/", name="app_ente_index", methods={"GET"})
     */
    public function index(EnteRepository $enteRepository): Response
    {
        return $this->render('ente/index.html.twig', [
            'entes' => $enteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_ente_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EnteRepository $enteRepository): Response
    {
        $entes = $enteRepository->findAll();
        $ente = new Ente();
        $form = $this->createForm(EnteType::class, $ente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $enteRepository->add($ente, true);

            return $this->redirectToRoute('app_ente_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ente/new.html.twig', [
            'ente' => $ente,
            'form' => $form,
            'entes' => $entes,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ente_show", methods={"GET"})
     */
    public function show(Ente $ente, EnteRepository $enteRepository): Response
    {
        $entes = $enteRepository->findAll();
        return $this->render('ente/show.html.twig', [
            'ente' => $ente,
            'entes' => $entes,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ente_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Ente $ente, EnteRepository $enteRepository): Response
    {
        $entes = $enteRepository->findAll();
        $form = $this->createForm(EnteType::class, $ente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $enteRepository->add($ente, true);

            return $this->redirectToRoute('app_ente_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ente/edit.html.twig', [
            'ente' => $ente,
            'form' => $form,
            'entes' => $entes,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ente_delete", methods={"POST"})
     */
    public function delete(Request $request, Ente $ente, EnteRepository $enteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ente->getId(), $request->request->get('_token'))) {
            $enteRepository->remove($ente, true);
        }

        return $this->redirectToRoute('app_ente_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/delete_ente/", name="delete_ente", methods={"POST"})
     */
    public function deleteEnte(
        Request $request,
        ManagerRegistry $doctrine,
        CategoriaRepository $categoriaRepository
    ): Response {
        $params = $request->request->all();
        $id = intval($params['id']);

        //--- buscamos si existen registro asociados a este periodo
        $n = $categoriaRepository->countAllCategoriasEnte($id);
        dd($n);
        //--- eliminamos el periodo ------//
        if ($n[0]['t'] == 0) {
            $categoria = $categoriaRepository->find($id);
            $entityManager = $doctrine->getManager();
            $entityManager->remove($categoria);
            $entityManager->flush();
            $estado = '1';
        } else {
            $estado = '-1';
        }


        $salida = array($estado);
        $response = new Response(json_encode($salida));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
