<?php

namespace App\Controller;

use App\Entity\Registro;
use App\Form\RegistroType;
use App\Repository\RegistroRepository;
use App\Repository\EnteRepository;
use App\Repository\CategoriaRepository;
use App\Repository\IndicadorRepository;
use App\Repository\PeriodoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @Security("is_granted('ROLE_ADMIN') and is_granted('ROLE_USER)") 
 * @Route("/registro")
 */
class RegistroController extends AbstractController
{
    /**
     * @Route("/", name="app_registro_index", methods={"GET"})
     */
    public function index(RegistroRepository $registroRepository): Response
    {
        return $this->render('registro/index.html.twig', [
            'registros' => $registroRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_registro_new", methods={"GET", "POST"})
     */
    public function new(Request $request, RegistroRepository $registroRepository): Response
    {
        $registro = new Registro();
        $form = $this->createForm(RegistroType::class, $registro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $registroRepository->add($registro, true);

            return $this->redirectToRoute('app_registro_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('registro/new.html.twig', [
            'registro' => $registro,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_registro_show", methods={"GET"})
     */
    public function show(Registro $registro): Response
    {
        return $this->render('registro/show.html.twig', [
            'registro' => $registro,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_registro_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Registro $registro, RegistroRepository $registroRepository): Response
    {
        $form = $this->createForm(RegistroType::class, $registro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $registroRepository->add($registro, true);

            return $this->redirectToRoute('app_registro_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('registro/edit.html.twig', [
            'registro' => $registro,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_registro_delete", methods={"POST"})
     */
    public function delete(Request $request, Registro $registro, RegistroRepository $registroRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $registro->getId(), $request->request->get('_token'))) {
            $registroRepository->remove($registro, true);
        }

        return $this->redirectToRoute('app_registro_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/new2/", name="app_registro_new2", methods={"POST"})
     */
    public function new2(
        Request $request,
        RegistroRepository $registroRepository,
        EnteRepository $enteRepository,
        CategoriaRepository $categoriaRepository,
        IndicadorRepository $indicadorRepository,
        PeriodoRepository $periodoRepository
    ): Response {
        $entes = $enteRepository->findAll();
        $periodos = $periodoRepository->findAll();
        $enteId = intval($request->query->get('enteid'));
        $ente = $enteRepository->find($enteId);
        $categorias = $categoriaRepository->findAllCategoriaEnte($enteId);

        return $this->renderForm('registro/new2.html.twig', [
            'entes' => $entes,
            'ente' => $ente,
            'categorias' => $categorias,
        ]);
    }
}
