<?php

namespace App\Controller;

use App\Entity\Categoria;
use App\Form\CategoriaType;
use App\Repository\CategoriaRepository;
use App\Repository\EnteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/categoria")
 */
class CategoriaController extends AbstractController
{
    /**
     * @Route("/", name="app_categoria_index", methods={"GET"})
     */
    public function index(CategoriaRepository $categoriaRepository, enteRepository $enteRepository, PaginatorInterface $paginator, Request $request): Response
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
        return $this->render('categoria/index.html.twig', [
            'categorias' => $categorias,
            'canReg' => $canReg,
            'entes' => $entes
        ]);
    }

    /**
     * @Route("/new", name="app_categoria_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CategoriaRepository $categoriaRepository): Response
    {
        $categorium = new Categoria();
        $form = $this->createForm(CategoriaType::class, $categorium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoriaRepository->add($categorium, true);

            return $this->redirectToRoute('app_categoria_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categoria/new.html.twig', [
            'categorium' => $categorium,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_categoria_show", methods={"GET"})
     */
    public function show(Categoria $categorium): Response
    {
        return $this->render('categoria/show.html.twig', [
            'categorium' => $categorium,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_categoria_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Categoria $categorium, CategoriaRepository $categoriaRepository): Response
    {
        $form = $this->createForm(CategoriaType::class, $categorium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoriaRepository->add($categorium, true);

            return $this->redirectToRoute('app_categoria_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categoria/edit.html.twig', [
            'categorium' => $categorium,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_categoria_delete", methods={"POST"})
     */
    public function delete(Request $request, Categoria $categorium, CategoriaRepository $categoriaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $categorium->getId(), $request->request->get('_token'))) {
            $categoriaRepository->remove($categorium, true);
        }

        return $this->redirectToRoute('app_categoria_index', [], Response::HTTP_SEE_OTHER);
    }
}
