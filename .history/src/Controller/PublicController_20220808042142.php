<?php

namespace App\Controller;


use App\Repository\EnteRepository;
use App\Repository\CategoriaRepository;
use App\Repository\IndicadorRepository;
use App\Repository\PeriodoRepository;
use App\Repository\RegistroRepository;
use App\Repository\ReporteRepository;
use App\Repository\SubreporteRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use Symfony\Component\String\Slugger\SluggerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


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
            // Definir el par??metro de la p??gina
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
     * @Route("/public_index2/", name="app_public_index2", methods={"GET","POST"})
     */
    public function index2(
        Request $request
    ): Response {
        $params = $request->request->all();
        $enteId = intval($params['id']);
        $url = $this->generateUrl('app_public_index3', [
            'id' => $enteId
        ]);
        $respuesta = new Response($url);
        return $respuesta;
    }

    /**
     * @Route("/public_index3/", name="app_public_index3", methods={"GET","POST"})
     */
    public function index3(
        Request $request,
        EnteRepository $enteRepository,
        CategoriaRepository $categoriaRepository,
        PaginatorInterface $paginator
    ): Response {
        $entes = $enteRepository->findAll();
        $enteId = intval($request->query->get('id'));
        $ente = $enteRepository->find($enteId);
        //$categorias = $categoriaRepository->findAllCategoriaByEnte($enteId);
        $canReg = $request->query->getInt('can_reg', 20);
        $misRegistros = $categoriaRepository->findBy(['ente' => $enteId]);
        // Paginar los resultados de la consulta
        $categorias = $paginator->paginate(
            // Consulta Doctrine, no resultados
            $misRegistros,
            // Definir el par??metro de la p??gina
            $request->query->getInt('page', 1),
            // Items per page
            $canReg
        );

        return $this->renderForm('publico/index_categoria2.html.twig', [
            'entes' => $entes,
            'ente' => $ente,
            'categorias' => $categorias,
            'canReg' => $canReg,
        ]);
    }


    /**
     * @Route("/public_index4/", name="app_public_index4", methods={"GET","POST"})
     */
    public function index4(
        Request $request
    ): Response {
        $params = $request->request->all();
        $enteId = intval($params['id']);
        $url = $this->generateUrl('app_public_index5', [
            'id' => $enteId
        ]);
        $respuesta = new Response($url);
        return $respuesta;
    }

    /**
     * @Route("/public_index5/", name="app_public_index5", methods={"GET","POST"})
     */
    public function index5(
        IndicadorRepository $indicadorRepository,
        PaginatorInterface $paginator,
        EnteRepository $enteRepository,
        CategoriaRepository $categoriaRepository,
        Request $request
    ): Response {
        //$misRegistros = $registroRepository->findAll();
        $canReg = $request->query->getInt('can_reg', 20);
        $enteId = $request->query->get('id', '-99');
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
            // Definir el par??metro de la p??gina
            $request->query->getInt('page', 1),
            // Items per page
            $canReg
        );
        $ente = $enteRepository->find($enteId);
        $entes = $enteRepository->findAll();
        if ($enteId != '-99') {
            $categorias = $categoriaRepository->findAllCategoriaByEnte($enteId);
        } else {
            $categorias = NULL;
        }
        return $this->render('publico/index_indicador2.html.twig', [
            'indicadors' => $indicadores,
            'canReg' => $canReg,
            'ente' => $ente,
            'entes' => $entes,
            'enteId' => $enteId,
            'categorias' => $categorias,
            'categoriaId' => $categoriaId,
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
            // Definir el par??metro de la p??gina
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


    /**
     * @Route("/public_reporte_index", name="app_public_reporte_index", methods={"GET","POST"})
     */
    public function indexPublicReporte(
        IndicadorRepository $indicadorRepository,
        EnteRepository $enteRepository,
        PeriodoRepository $periodoRepository,
        ReporteRepository $reporteRepository,
        SluggerInterface $slugger,
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

        return $this->render('publico/index_reporte.html.twig', [
            'ente' => $ente,
            'entes' => $entes,
            'enteId' => $enteId,
            'periodos' => $periodos,
            'reportes' => $reportes,
        ]);
    }


    /**
     * @Route("/public_busca_indicadores_reporte/", name="app_public_reporte_busca_indicadores_reporte", methods={"GET","POST"})
     */
    public function buscaPublicIndicadoresReporte(
        IndicadorRepository $indicadorRepository,
        EnteRepository $enteRepository,
        PeriodoRepository $periodoRepository,
        ReporteRepository $reporteRepository,
        SubreporteRepository $subreporteRepository,
        SluggerInterface $slugger,
        Request $request
    ): Response {
        //$misRegistros = $registroRepository->findAll();
        $params = $request->request->all();
        $enteId = $params['enteId'] ?? '-99';
        $reporteId =  $params['reporteId'] ?? '-99';
        $periodoId = $params['periodoId'] ?? '-99';
        $periodo2Id = $params['periodo2Id'] ?? '-99';
        //--- buscamos los periodos ------
        $periodos = $periodoRepository->findAll();

        $entes = $enteRepository->findAll();
        $ente = $enteRepository->find($enteId);
        $periodo = $periodoRepository->find($periodoId);

        $idp1 = $periodoId;
        $idp2 = $periodo2Id;

        $periodos2 = $periodoRepository->findPeriodosRango(intval($idp1), intval($idp2));
        //dd($periodos2);
        $nperiodos = $idp2 - $idp1;
        $nperiodos = count($periodos2);
        if ($nperiodos == 0) {
            $nperiodos = 1;
        }

        for ($i = 0; $i < $nperiodos; $i++) {
            $arreglo_valores[] = "";
        }
        switch ($enteId) {
            case "1": // CNED
                //---- buscamos el reporte especifico -----
                $reporte = $reporteRepository->find($reporteId);

                $nombreReporte = $reporte->getProcedimiento();
                $categoriaId = $reporte->getCategoria()->getId();
                // creamos el arreglo  que contendra los resultados
                /*
                concepto
                unidad
                nombre
                definicion
                formula
                valor1
                valor2
                valor3
                valor..n

                */

                $indicadores_patron = $reporteRepository->queryIndicators($enteId, $categoriaId, $periodoId, $nombreReporte);
                $n = count($indicadores_patron);
                for ($i = 0; $i < $n; $i++) {
                    $indicadores_salida[$i]['id'] = $indicadores_patron[$i]['id'];
                    $indicadores_salida[$i]['concepto'] = $indicadores_patron[$i]['concepto'];
                    $indicadores_salida[$i]['unidad'] = $indicadores_patron[$i]['unidad'];
                    $indicadores_salida[$i]['nombre'] = $indicadores_patron[$i]['nombre'];
                    $indicadores_salida[$i]['definicion'] = $indicadores_patron[$i]['definicion'];
                    $indicadores_salida[$i]['formula'] = $indicadores_patron[$i]['formula'];
                    $indicadores_salida[$i]['valores'] = $arreglo_valores;
                }
                //--- recorremos los periodos ------
                $indice = 0;
                for ($i = $idp1; $i <= $idp2; $i++) {
                    $resp1 = $reporteRepository->queryIndicators($enteId, $categoriaId, $i, $nombreReporte);
                    //-- el periodo corresponde con indice del arreglo de valores
                    //-- se toma en cuenta el id del indicador para ubicarlo dentro del arreglo de valorSalida
                    foreach ($resp1 as $resp) {
                        $idv = $resp['id'];
                        $valorv = $resp['valor'];
                        $yyy = 0;
                        foreach ($indicadores_salida as $indicador_salida) {
                            if ($indicador_salida['id'] == $idv) {
                                //-- son iguales es el mismo indicador debemos colocar el resultado de valor ---
                                // se toma en cuenta el indice i para determinar en que parte del arreglo va el valor
                                $indicadores_salida[$yyy]['valores'][($indice)] = $valorv;
                            }
                            $yyy++;
                        }
                    }
                    $indice++;
                }
                $indicadores = $indicadores_salida;
                break;
            case "2": // CNA
                $reporte = $reporteRepository->find($reporteId);
                //---- buscar los nombres de categorias asociadas a la dimension -----
                $subReportes = $subreporteRepository->findBy(['reporte' => $reporteId]);

                foreach ($subReportes as $subReporte) {
                    $nombreReporte = $subReporte->getProcedimiento();
                    $categoriaId = $subReporte->getCategoria()->getId();
                    $resp1 = $reporteRepository->queryIndicators($enteId, $categoriaId, $periodoId, $nombreReporte);
                    foreach ($resp1 as $resp) {
                        $indicadores_patron[] = $resp;
                    }
                }
                //--- creamos el rreglo de salida sin valores
                $n = count($indicadores_patron);
                for ($i = 0; $i < $n; $i++) {
                    $indicadores_salida[$i]['id'] = $indicadores_patron[$i]['id'];
                    $indicadores_salida[$i]['concepto'] = $indicadores_patron[$i]['concepto'];
                    $indicadores_salida[$i]['unidad'] = $indicadores_patron[$i]['unidad'];
                    $indicadores_salida[$i]['nombre'] = $indicadores_patron[$i]['nombre'];
                    $indicadores_salida[$i]['definicion'] = $indicadores_patron[$i]['definicion'];
                    $indicadores_salida[$i]['formula'] = $indicadores_patron[$i]['formula'];
                    $indicadores_salida[$i]['valores'] = $arreglo_valores;
                }

                //--- recorremos los periodos ------
                $indice = 0;
                for ($i = $idp1; $i <= $idp2; $i++) {
                    $resp1 = $reporteRepository->queryIndicators($enteId, $categoriaId, $i, $nombreReporte);
                    //-- el periodo corresponde con indice del arreglo de valores
                    //-- se toma en cuenta el id del indicador para ubicarlo dentro del arreglo de valorSalida

                    //---- buscar los indicadores asociados a los subreportes -----
                    foreach ($subReportes as $subReporte) {

                        $nombreReporte = $subReporte->getProcedimiento();
                        $categoriaId = $subReporte->getCategoria()->getId();
                        $resp1 = $reporteRepository->queryIndicators($enteId, $categoriaId, $i, $nombreReporte);
                        foreach ($resp1 as $resp) {
                            $idv = $resp['id'];
                            $valorv = $resp['valor'];
                            $yyy = 0;
                            foreach ($indicadores_salida as $indicador_salida) {
                                if ($indicador_salida['id'] == $idv) {
                                    //-- son iguales es el mismo indicador debemos colocar el resultado de valor ---
                                    // se toma en cuenta el indice i para determinar en que parte del arreglo va el valor
                                    $indicadores_salida[$yyy]['valores'][($indice)] = $valorv;
                                }
                                $yyy++;
                            }
                        }
                    }
                    $indice++;
                }
                $indicadores = $indicadores_salida;

                //---- buscar los indicadores asociados a los subreportes -----
                /*foreach ($subReportes as $subReporte) {
                    $nombreReporte = $subReporte->getProcedimiento();
                    $categoriaId = $subReporte->getCategoria()->getId();
                    $resp1 = $reporteRepository->queryIndicators($enteId, $categoriaId, $periodoId, $nombreReporte);
                    foreach ($resp1 as $resp) {
                        $indicadores[] = $resp;
                    }
                }*/
                break;
        }

        if ($indicadores) {
            // =========================== archivo PDF ==============================

            // Configure Dompdf according to your needs
            $pdfOptions = new Options();
            $pdfOptions->set('defaultFont', 'Calibri');

            // Instantiate Dompdf with our options
            $dompdf = new Dompdf($pdfOptions);

            $nombreImagen = __DIR__ . '/../../public/assets/images/logo1.jpg';
            $logo = "data:image/png;base64," . base64_encode(file_get_contents($nombreImagen));
            // Retrieve the HTML generated in our twig file
            //dd($indicadores);
            $html = $this->renderView('pdfs/index.html.twig', [
                'title' => "Reportes de Indicadores",
                'ente' => $ente,
                'periodo' => $periodo,
                'reporte' => $reporte,
                'logo' => $logo,
                'indicadores' => $indicadores,
                'periodos2' => $periodos2,
            ]);


            // Load HTML to Dompdf
            $dompdf->loadHtml($html);

            // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
            $dompdf->setPaper('letter', 'portrait');

            // Render the HTML as PDF
            $dompdf->render();

            // Store PDF Binary Data
            $output = $dompdf->output();

            // In this case, we want to write the file in the public directory
            //$publicDirectory = $this->get('kernel')->getProjectDir() . '/public';
            // e.g /var/www/project/public/mypdf.pdf
            $n1 = $slugger->slug($ente->getNombre() . '-' . $reporte->getTitulo() . "-" . $periodo->getNombre() . uniqid()) . '.pdf';
            $pdfFilepath =  $this->getParameter('archivos') . $n1;

            // Write file to the desired path
            file_put_contents($pdfFilepath, $output);

            // =========================== archivo excel ==============================
            $spreadsheet = new Spreadsheet();

            /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'Reporte de Indicadores');
            $sheet->setCellValue('A2', 'Organismo Acreditador: ');
            $sheet->setCellValue('B2', $ente->getNombre());
            $sheet->setCellValue('A3', 'Reporte:' . $reporte->getTitulo());
            $sheet->setCellValue('B3', $reporte->getTitulo());
            $sheet->setCellValue('A4', 'Periodo:');
            $sheet->setCellValue('B4',  $periodo->getNombre());
            //dd($indicadores);
            $sheet->setCellValue('E5', 'Periodos');
            $sheet->setCellValue('A6', 'Item');
            $sheet->setCellValue('B6', 'Indicador');
            $sheet->setCellValue('C6', 'Definci??n');
            $sheet->setCellValue('D6', 'F??rmula');
            $nletra = ord("E");
            foreach ($periodos2 as $periodo2) {
                $sheet->setCellValue(chr($nletra) . "6", $periodo2['nombre']);
                $nletra++;
            }
            $i = 7;
            $u = 1;
            foreach ($indicadores as $indicador) {
                $sheet->setCellValue('A' . $i, $u);
                $sheet->setCellValue('B' . $i, str_replace("</font>", "", str_replace("<font color=red>", "", $indicador['concepto'])));
                $sheet->setCellValue('C' . $i, $indicador['definicion']);
                $sheet->setCellValue('D' . $i, $indicador['formula']);
                $nletra = ord("E");
                foreach ($indicador['valores'] as $valor) {
                    if ($indicador['unidad'] == "%") {
                        $sheet->setCellValue(chr($nletra) . $i, number_format(($valor * 100), 2, ',', '.') . " %");
                    } else {
                        $sheet->setCellValue(chr($nletra) . $i, $valor);
                    }
                    $nletra++;
                }

                $i++;
                $u++;
            }

            $spreadsheet->getActiveSheet()->mergeCells("A1:B1");
            $spreadsheet->getActiveSheet()->mergeCells("E5:" . chr($nletra - 1) . "5");
            $sheet->getStyle("E5:" . chr($nletra - 1) . "5")->getFont()->setBold(true)->setSize(16)
                ->getColor()
                ->setRGB('FFFFFF');
            $sheet->getStyle("E5:" . chr($nletra - 1) . "5")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('16626f');

            $sheet->getStyle("A1:F1")->getFont()->setBold(true)->setSize(16)
                ->getColor()
                ->setRGB('FFFFFF');
            $sheet->getStyle("A1:F1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('16626f');

            $sheet->getStyle("A6:" . chr($nletra - 1) . "6")->getFont()->setBold(true)->setSize(16)
                ->getColor()
                ->setRGB('FFFFFF');
            $sheet->getStyle("A6:" . chr($nletra - 1) . "6")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('16626f');


            $sheet->getStyle("A6:X6")->getFont()->setBold(true);
            //$sheet->getStyle("A6:B6")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->setTitle("CNED");

            // Crear tu archivo de Office 2007 Excel (XLSX Formato)
            $writer = new Xlsx($spreadsheet);

            // En este caso deseamos escribir el archivo en el directorio public del proyecto
            $n2 = $slugger->slug($ente->getNombre() . '-' . $reporte->getTitulo() . "-" . $periodo->getNombre() . uniqid()) . '.xlsx';
            $excelFilepath =  $this->getParameter('archivos') . $n2;


            // Crear el archivo y guardarlo en el directorio
            $writer->save($excelFilepath);
        }

        $cadena_periodos = "";
        foreach ($periodos2 as $periodo2) {
            $cadena_periodos .= "<th>" . $periodo2['nombre'] . "</th>";
        }
        $salida = array($indicadores, "../assets/archivos/" . $n1, "../assets/archivos/" . $n2, $nperiodos, $cadena_periodos);
        $response = new Response(json_encode($salida));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
