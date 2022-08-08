<?php

namespace App\Controller;

use App\Entity\Periodo;
use App\Form\PeriodoType;
use App\Repository\PeriodoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/periodo")
 */
class PeriodoController extends AbstractController
{
    /**
     * @Route("/", name="app_periodo_index", methods={"GET"})
     */
    public function index(PeriodoRepository $periodoRepository): Response
    {
        return $this->render('periodo/index.html.twig', [
            'periodos' => $periodoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_periodo_new", methods={"GET", "POST"})
     */
    public function new(Request $request, PeriodoRepository $periodoRepository): Response
    {
        $periodo = new Periodo();
        $form = $this->createForm(PeriodoType::class, $periodo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $periodoRepository->add($periodo, true);

            return $this->redirectToRoute('app_periodo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('periodo/new.html.twig', [
            'periodo' => $periodo,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_periodo_show", methods={"GET"})
     */
    public function show(Periodo $periodo): Response
    {
        return $this->render('periodo/show.html.twig', [
            'periodo' => $periodo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_periodo_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Periodo $periodo, PeriodoRepository $periodoRepository): Response
    {
        $form = $this->createForm(PeriodoType::class, $periodo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $periodoRepository->add($periodo, true);

            return $this->redirectToRoute('app_periodo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('periodo/edit.html.twig', [
            'periodo' => $periodo,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_periodo_delete", methods={"POST"})
     */
    public function delete(Request $request, Periodo $periodo, PeriodoRepository $periodoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$periodo->getId(), $request->request->get('_token'))) {
            $periodoRepository->remove($periodo, true);
        }

        return $this->redirectToRoute('app_periodo_index', [], Response::HTTP_SEE_OTHER);
    }
}
