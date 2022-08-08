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
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Style\Language;
use PhpOffice\PhpWord\Style\TOC;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Image;
use PhpOffice\PhpWord\SimpleType\Jc;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use tecnickcom\tcpdf\TCPDF;

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

        //-- creamos el objeto phpword
        $phpword = new PhpWord();

        $phpword->setDefaultFontName('Calibri');
        $phpword->getSettings()->setUpdateFields(true);
        $fontStyle = array('spaceAfter' => 60, 'size' => 12, 'bgColor' => 'FFFFFF');
        $phpword->addTitleStyle(1, array('size' => 12, 'color' => '000000', 'bgColor' => 'FFFFFF', 'bold' => false));

        $section = $phpword->addSection(
            array(
                'marginLeft' => 1300,
                'marginRight' => 1300,
                'marginTop' => 600,
                'marginBottom' => 600
            )
        );

        //---------------------- encabezado --------------------------------------------//
        $headert = $section->addHeader();
        $table = $headert->addTable();
        $table->addRow();
        $table->addCell(5000)->addImage(
            'assets/imagenes/logo.png',
            array(
                'width'  => 50,
                'height' => 50,
                'align'  => 'left'
            )
        );
        $table->addCell(5000)->addText(htmlspecialchars(' '), array('size' => 12, 'bold' => true, 'color' => '000000'), array('alignment' => 'left'));

        //--- contenido -----//
        $section->addText(htmlspecialchars('La autoevaluación es el primer paso en el proceso de mejoramiento continuo de la calidad de la gestión de los Gobiernos Regionales y corresponde a la fase en que la institución reúne información acerca del cumplimiento de los requerimientos del Modelo de Excelencia en la Gestión y de su misión y propósitos, a través de evidencia fidedigna evaluada externamente.'), array('size' => 12, 'bold' => false, 'color' => '000000'), array('alignment' => 'both'));
        $section->addTextBreak(1);

        //--- fin contenido -----//

        // Guardando el documento como archivo OOXML ...
        $phpword->getSettings()->setThemeFontLang(new Language("ES-ES"));
        $phpword->getSettings()->setUpdateFields(true);

        $objWriter = IOFactory::createWriter($phpword, 'Word2007');

        //---- definimos el nombre del archivo de salida en base al nombre de la Region
        $safeFilename = $slugger->slug('informe_indicadores_' . $ente->getNombre());
        $newFilename0 = $safeFilename . '-' . uniqid() . '.docx';

        $filePath = $this->getParameter('archivos') . $newFilename0;
        // Escribir archivo en la ruta
        $objWriter->save($filePath);


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
                $subReportes = $subreporteRepository->findBy(['reporte' => $reporteId]);

                //---- buscar los indicadores asociados a los subreportes -----
                foreach ($subReportes as $subReporte) {
                    $nombreReporte = $subReporte->getProcedimiento();
                    $categoriaId = $subReporte->getCategoria()->getId();
                    $indicadores[] = $reporteRepository->queryIndicators($enteId, $categoriaId, $periodoId, $nombreReporte);
                }
                dd($indicadores);
                break;
        }

        $salida = array($indicadores);
        $response = new Response(json_encode($salida));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
