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

use Symfony\Component\String\Slugger\SluggerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
        for ($i = 0; $i <= $nperiodos; $i++) {
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
                                $indicadores_salida[$yyy]['valores'][($i - 1)] = $valorv;
                            }
                            $yyy++;
                        }
                    }
                }
                $indicadores = $indicadores_salida;

                // este funciona
                //$indicadores = $reporteRepository->queryIndicators($enteId, $categoriaId, $periodoId, $nombreReporte);
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
                                    $indicadores_salida[$yyy]['valores'][($i - 1)] = $valorv;
                                }
                                $yyy++;
                            }
                        }
                    }
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

            $nombreImagen = __DIR__ . '/../../public/assets/images/logo2.png';
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
            $sheet->setCellValue('A6', 'Item');
            $sheet->setCellValue('B6', 'Indicador');
            $sheet->setCellValue('C6', 'Definción');
            $sheet->setCellValue('D6', 'Fórmula');
            $sheet->setCellValue('E6', 'Periodo');
            $sheet->setCellValue('F6', 'Resultado');
            $i = 7;
            $u = 1;
            foreach ($indicadores as $indicador) {
                $sheet->setCellValue('A' . $i, $u);
                $sheet->setCellValue('B' . $i, $indicador['concepto']);
                $sheet->setCellValue('C' . $i, $indicador['definicion']);
                $sheet->setCellValue('D' . $i, $indicador['formula']);
                /*$sheet->setCellValue('E' . $i, $indicador['periodo']);
                if ($indicador['unidad'] == "%") {
                    $sheet->setCellValue('F' . $i, $indicador['valor'] . " %");
                } else {
                    $sheet->setCellValue('F' . $i, $indicador['valor']);
                }*/
                $i++;
                $u++;
            }

            $spreadsheet->getActiveSheet()->mergeCells("A1:B1");
            $sheet->getStyle("A1:F1")->getFont()->setBold(true)->setSize(16)
                ->getColor()
                ->setRGB('FFFFFF');
            $sheet->getStyle("A1:F1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('16626f');


            $sheet->getStyle("A6:F6")->getFont()->setBold(true);
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
        if ($nperiodos == 0) {
            $nperiodos = 1;
        }

        $cadena_periodos = "";
        foreach ($periodos2 as $periodo2) {
            $cadena_periodos += "<th>" . trim(($periodo2['nombre']) . "</th>";
        }
        $salida = array($indicadores, "../assets/archivos/" . $n1, "../assets/archivos/" . $n2, $nperiodos, $cadena_periodos);
        $response = new Response(json_encode($salida));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
