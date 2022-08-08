<?php

namespace App\Controller;

use App\Entity\Registro;
use App\Entity\Ente;
use App\Entity\Categoria;
use App\Entity\Indicador;
use App\Entity\Periodo;
use App\Form\RegistroType;
use App\Repository\RegistroRepository;
use App\Repository\EnteRepository;
use App\Repository\CategoriaRepository;
use App\Repository\IndicadorRepository;
use App\Repository\PeriodoRepository;
use App\Repository\ReporteRepository;
use App\Repository\SubreporteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

/**
 * @Security("is_granted('ROLE_ADMIN') and is_granted('ROLE_USER')") 
 * @Route("/reporte")
 */
class ReporteController extends AbstractController
{
    /**
     * @Route("/", name="app_reporte_index", methods={"GET","POST"})
     */
    public function index(
        IndicadorRepository $indicadorRepository,
        EnteRepository $enteRepository,
        PeriodoRepository $periodoRepository,
        ReporteRepository $reporteRepository,
        Request $request
    ): Response {
        //$misRegistros = $registroRepository->findAll();
        $enteId = $request->query->get('enteId', '-99');
        // var_dump($enteId);
        $reporteId = $request->query->get('reporteId', '-99');
        if ($enteId != '-99') {
            $reportes = $reporteRepository->findBy(['ente' => $enteId]);
        } else {
            $reportes = [];
        }

        $periodos = $periodoRepository->findAll();

        $entes = $enteRepository->findAll();
        $ente = $enteRepository->find($enteId);
        return $this->render('reporte/index.html.twig', [
            'ente' => $ente,
            'entes' => $entes,
            'enteId' => $enteId,
            'periodos' => $periodos,
            'reportes' => $reportes,
        ]);
    }

    /**
     * @Route("/busca_indicadores/", name="app_reporte_busca_indicadores", methods={"GET","POST"})
     */
    public function searchIndicadores(
        IndicadorRepository $indicadorRepository,
        EnteRepository $enteRepository,
        PeriodoRepository $periodoRepository,
        ReporteRepository $reporteRepository,
        SubreporteRepository $subreporteRepository,
        Request $request
    ): Response {
        //$misRegistros = $registroRepository->findAll();
        $params = $request->request->all();
        $enteId = $params['enteId'] ?? '-99';
        $reporteId =  $params['reporteId'] ?? '-99';
        $periodoId = $params['periodoId'] ?? '-99';
        //--- buscamos los periodos ------
        $periodos = $periodoRepository->findAll();

        $entes = $enteRepository->findAll();
        $ente = $enteRepository->find($enteId);

        switch ($enteId) {
            case "1": // CNED
                //---- buscamos el reporte especifico -----
                $reporte = $reporteRepository->find($reporteId);

                $nombreReporte = $reporte->getProcedimiento();
                $categoriaId = $reporte->getCategoria()->getId();

                $indicadores = $reporteRepository->queryIndicators($enteId, $categoriaId, $periodoId, $nombreReporte);
                break;
            case "2": // CNA
                //---- buscar los nombres de categorias asociadas a la dimension -----
                $subReporte = $subreporteRepository->findBy(['reporte' => $reporteId]);
                //---- buscamos el nombre del reporte ----------
                $nombreSubReporte = $subReporte->getProcedimiento();
                //---- buscar los indicadores asociados a los subreportes -----
                dd($nombreSubReporte);
                break;
        }

        $salida = array($indicadores);
        $response = new Response(json_encode($salida));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
