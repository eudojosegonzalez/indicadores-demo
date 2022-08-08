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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Doctrine\Persistence\ManagerRegistry;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

/**
 * @Security("is_granted('ROLE_ADMIN') and is_granted('ROLE_USER')") 
 * @Route("/carga")
 */
class CargaController extends AbstractController
{
    /**
     * @Route("/new2/", name="app_registro_new2", methods={"GET","POST"})
     */
    public function new2(
        Request $request
    ): Response {
        $params = $request->request->all();
        $enteId = intval($params['id']);
        $url = $this->generateUrl('app_registro_new3', [
            'id' => $enteId
        ]);
        $respuesta = new Response($url);
        return $respuesta;
    }

    /**
     * @Route("/new3/", name="app_registro_new3", methods={"GET","POST"})
     */
    public function new3(
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

        return $this->renderForm('registro/new2.html.twig', [
            'entes' => $entes,
            'ente' => $ente,
            'categorias' => $categorias,
            'periodos' => $periodos,
        ]);
    }

    /**
     * @Route("/busca_indicadores/", name="app_registro_busca_indicadores", methods={"POST"})
     */
    public function buscaIndicadores(
        Request $request,
        RegistroRepository $registroRepository,
        IndicadorRepository $indicadorRepository
    ): Response {

        $params = $request->request->all();
        $enteId = intval($params['ente']);
        $categoriaId = intval($params['categoria']);
        $periodoId = intval($params['periodo']);
        $indicadores = $indicadorRepository->findBy(['ente' => $enteId, 'categoria' => $categoriaId]);
        //var_dump("<pre>", $indicadores, "</pre>");
        $registros = $registroRepository->findAllRegistroByEnteCategoria($enteId, $categoriaId);
        dd($registros);
        $valores = $registroRepository->findAllRegistroByEnteCategoriaPeriodo($enteId, $categoriaId, $periodoId);
        //var_dump("<pre>", $valores, "</pre>");
        foreach ($indicadores as $indicador) {
            foreach ($registros as $registro) {
                if (($registro['indicador_id'] == $indicador->getId()) && ($registro['visible'] == 1)) {
                    /*
                        i.nombre,
                        ifnull(i.formula,'vacio') as formula,
                        ifnull(i.descripcion,'vacio') as descripcion,
                        i.ente_id,
                        i.categoria_id,
                        i.id  as indicador_id,    
                        '0' as registro_id,
                        ifnull(r.periodo_id,0) as periodo_id,
                        '0.00' as valor
                    */
                    //--- buscamos los valores si existen
                    $valorSalida = 0;
                    foreach ($valores as $valor) {
                        if ($valor['indicador_id'] == $registro['indicador_id']) {
                            /*
                                i.nombre,
                                i.formula,
                                i.descripcion,
                                i.ente_id,
                                i.categoria_id,
                                i.id  as indicador_id,    
                                r.id as registro_id,
                                r.periodo_id,
                                ifnull(r.valor,0) as valor

                            */
                            $valorSalida = $valor['valor'];
                        }
                    }
                    $arreglo = array(
                        'nombre' => $registro['nombre'],
                        'formula' => $registro['formula'],
                        'descripcion' => $registro['descripcion'],
                        'ente_id' => $registro['ente_id'],
                        'categoria_id' => $registro['categoria_id'],
                        'indicador_id' => $registro['indicador_id'],
                        'registro_id' => $registro['registro_id'],
                        'periodo_id' => $registro['periodo_id'],
                        'valor' => $valorSalida
                    );
                    $arreglo2[] = $arreglo;
                }
            }
        }
        dd($arreglo2);
        /*if (empty($registros)) {
            $registros = $registroRepository->findAllRegistroByEnteCategoria($enteId, $categoriaId);
        }*/


        $salida = array($registros);
        $response = new Response(json_encode($arreglo2));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/guarda_indicadores/", name="app_registro_guarda_indicadores", methods={"POST"})
     */
    public function guardaIndicadores(
        ManagerRegistry $doctrine,
        Request $request,
        EnteRepository $enteRepository,
        CategoriaRepository $categoriaRepository,
        PeriodoRepository $periodoRepository
    ): Response {

        $params = $request->request->all();
        //dd($params);
        $enteId = intval($params['enteid']);
        $categoriaId = intval($params['categoria']);
        $periodoId = intval($params['periodo']);
        //var_dump("<pre>", $params, "</pre>");
        //---buscamos el ente ----
        $ente = $enteRepository->find($enteId);

        //---buscamos la categoria ----
        $categoria = $categoriaRepository->find($categoriaId);

        //---buscamos el periodo ----
        $periodo = $periodoRepository->find($periodoId);

        $respuesta_00 = 0;
        $respuesta_01 = 0;
        $respuesta_02 = 0;
        $respuesta_03 = 0;
        $respuesta_04 = 0;
        $respuesta_05 = 0;
        $respuesta_06 = 0;
        $respuesta_07 = 0;
        $respuesta_08 = 0;
        $respuesta_09 = 0;
        $respuesta_10 = 0;
        $respuesta_11 = 0;
        $respuesta_12 = 0;
        $respuesta_13 = 0;
        $respuesta_14 = 0;
        $respuesta_15 = 0;
        $respuesta_16 = 0;
        $respuesta_17 = 0;
        $respuesta_18 = 0;
        $respuesta_19 = 0;
        $respuesta_20 = 0;
        $respuesta_21 = 0;
        $respuesta_22 = 0;
        $respuesta_23 = 0;
        $respuesta_24 = 0;
        $respuesta_25 = 0;
        $respuesta_26 = 0;
        $respuesta_27 = 0;
        $respuesta_28 = 0;
        $respuesta_29 = 0;
        $respuesta_30 = 0;
        $respuesta_31 = 0;
        $respuesta_32 = 0;
        $respuesta_33 = 0;
        $respuesta_34 = 0;
        $respuesta_35 = 0;


        //---- elemento del formulario indice_00 -----
        if (isset($params['indice_00'])) {
            $a = explode("-", $params['indice_00']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_00']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_00 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_00 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_01 -----
        if (isset($params['indice_01'])) {
            $a = explode("-", $params['indice_01']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_01']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_01 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_01 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_02 -----
        if (isset($params['indice_02'])) {
            $a = explode("-", $params['indice_02']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_02']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_02 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_02 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_03 -----
        if (isset($params['indice_03'])) {
            $a = explode("-", $params['indice_03']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_03']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_03 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_03 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }


        //---- elemento del formulario indice_04 -----
        if (isset($params['indice_04'])) {
            $a = explode("-", $params['indice_04']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_04']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_04 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_04 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_05 -----
        if (isset($params['indice_05'])) {
            $a = explode("-", $params['indice_05']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_05']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_05 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_06 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_06 -----
        if (isset($params['indice_06'])) {
            $a = explode("-", $params['indice_06']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_06']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_06 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_06 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_07 -----
        if (isset($params['indice_07'])) {
            $a = explode("-", $params['indice_07']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_07']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_07 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_07 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_08 -----
        if (isset($params['indice_08'])) {
            $a = explode("-", $params['indice_08']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_08']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_08 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_08 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_09 -----
        if (isset($params['indice_09'])) {
            $a = explode("-", $params['indice_09']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_09']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_09 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_09 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_010 -----
        if (isset($params['indice_010'])) {
            $a = explode("-", $params['indice_010']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_010']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_10 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_10 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }


        //---- elemento del formulario indice_011 -----
        if (isset($params['indice_011'])) {
            $a = explode("-", $params['indice_011']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_011']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_11 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_11 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_012 -----
        if (isset($params['indice_012'])) {
            $a = explode("-", $params['indice_012']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_012']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_12 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_12 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_013 -----
        if (isset($params['indice_013'])) {
            $a = explode("-", $params['indice_013']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_013']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_13 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_13 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }


        //---- elemento del formulario indice_014 -----
        if (isset($params['indice_014'])) {
            $a = explode("-", $params['indice_014']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_014']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_14 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_14 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_015 -----
        if (isset($params['indice_015'])) {
            $a = explode("-", $params['indice_015']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_015']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_15 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_15 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_016 -----
        if (isset($params['indice_016'])) {
            $a = explode("-", $params['indice_016']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_016']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_16 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_16 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_017 -----
        if (isset($params['indice_017'])) {
            $a = explode("-", $params['indice_017']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_017']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_17 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_17 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_018 -----
        if (isset($params['indice_018'])) {
            $a = explode("-", $params['indice_018']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_018']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_18 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_18 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_019 -----
        if (isset($params['indice_019'])) {
            $a = explode("-", $params['indice_019']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_019']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_19 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_19 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_20 -----
        if (isset($params['indice_20'])) {
            $a = explode("-", $params['indice_020']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_020']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_20 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_20 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_21 -----
        if (isset($params['indice_021'])) {
            $a = explode("-", $params['indice_021']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_021']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_21 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_21 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_22 -----
        if (isset($params['indice_022'])) {
            $a = explode("-", $params['indice_022']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_022']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_22 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_22 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_23 -----
        if (isset($params['indice_023'])) {
            $a = explode("-", $params['indice_023']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_023']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_23 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_23 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_24 -----
        if (isset($params['indice_024'])) {
            $a = explode("-", $params['indice_024']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_024']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_24 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_24 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_25 -----
        if (isset($params['indice_025'])) {
            $a = explode("-", $params['indice_025']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_025']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_25 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_25 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_26 -----
        if (isset($params['indice_026'])) {
            $a = explode("-", $params['indice_026']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_026']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_26 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_26 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_27 -----
        if (isset($params['indice_027'])) {
            $a = explode("-", $params['indice_027']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_027']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_27 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_27 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_28 -----
        if (isset($params['indice_028'])) {
            $a = explode("-", $params['indice_028']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_028']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_28 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_28 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_29 -----
        if (isset($params['indice_029'])) {
            $a = explode("-", $params['indice_029']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_029']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_29 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_29 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_30 -----
        if (isset($params['indice_030'])) {
            $a = explode("-", $params['indice_030']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_030']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_30 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_30 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_31 -----
        if (isset($params['indice_031'])) {
            $a = explode("-", $params['indice_031']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_031']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_31 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_31 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_32 -----
        if (isset($params['indice_032'])) {
            $a = explode("-", $params['indice_032']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_032']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_32 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_32 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_33 -----
        if (isset($params['indice_033'])) {
            $a = explode("-", $params['indice_033']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_033']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_33 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_33 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_34 -----
        if (isset($params['indice_034'])) {
            $a = explode("-", $params['indice_034']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_034']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_34 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_34 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        //---- elemento del formulario indice_35 -----
        if (isset($params['indice_035'])) {
            $a = explode("-", $params['indice_035']);
            $indicadorId = intval($a[0]);
            $registroId = intval($a[1]);
            $valor = floatval($params['ind_035']);

            if ($registroId != 0) {
                //--- existe el registro se actualiza ----
                $respuesta_35 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta_35 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        $salida = array(
            $respuesta_01,
            $respuesta_02,
            $respuesta_03,
            $respuesta_04,
            $respuesta_05,
            $respuesta_06,
            $respuesta_07,
            $respuesta_08,
            $respuesta_09,
            $respuesta_10,
            $respuesta_11,
            $respuesta_12,
            $respuesta_13,
            $respuesta_14,
            $respuesta_15,
            $respuesta_16,
            $respuesta_17,
            $respuesta_18,
            $respuesta_19,
            $respuesta_20,
            $respuesta_21,
            $respuesta_22,
            $respuesta_23,
            $respuesta_24,
            $respuesta_25,
            $respuesta_26,
            $respuesta_27,
            $respuesta_28,
            $respuesta_29,
            $respuesta_30,
            $respuesta_31,
            $respuesta_32,
            $respuesta_33,
            $respuesta_34,
            $respuesta_35
        );
        $response = new Response(json_encode($salida));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Esta función permite crear un registro en la base de datos
     * @ param Doctrine $doctrine
     * @ param int $enteId
     * @ param int $categoriaId
     * @ param int $periodoId
     * @ param int $indicadorId
     * @ param float $valor
     */
    private function creaRegistro(
        ManagerRegistry $doctrine,
        int $enteId,
        int $categoriaId,
        int $periodoId,
        int $indicadorId,
        float $valor
    ) {
        $entityManager = $doctrine->getManager();
        //--- creamos unaa instancia al ente certificador ----
        $ente = $entityManager->getRepository(Ente::class)->find($enteId);

        //--- creamos una instancia a la categoria ----
        $categoria = $doctrine->getRepository(Categoria::class)->find($categoriaId);

        //--- creamos una instancia al periodo ----
        $periodo = $entityManager->getRepository(Periodo::class)->find($periodoId);

        //--- creamos una instancia al indicador ----
        $indicador = $entityManager->getRepository(Indicador::class)->find($indicadorId);

        $registro = new Registro();
        $registro->setEnte($ente);
        $registro->setCategoria($categoria);
        $registro->setPeriodo($periodo);
        $registro->setIndicador($indicador);
        $registro->setValor($valor);

        //--- hacemos persistente el cambio
        $entityManager = $doctrine->getManager();
        $entityManager->persist($registro);
        $entityManager->flush();

        $salida = '1';
        return $salida;
    }



    /**
     * Esta función permite actualizar un registro en la base de datos
     * @param Doctrine $doctrine
     * @param int $enteId
     * @param int $categoriaId
     * @param int $periodoId
     * @param int $indicadorId
     * @param int $registroId
     * @param float $valor
     */
    private function actualizaRegistro(
        ManagerRegistry $doctrine,
        int $enteId,
        int $categoriaId,
        int $periodoId,
        int $indicadorId,
        int $registroId,
        float $valor
    ) {
        $entityManager = $doctrine->getManager();
        //--- creamos unaa instancia al ente certificador ----
        $ente = $entityManager->getRepository(Ente::class)->find($enteId);

        //--- creamos una instancia a la categoria ----
        $categoria = $doctrine->getRepository(Categoria::class)->find($categoriaId);

        //--- creamos una instancia al periodo ----
        $periodo = $entityManager->getRepository(Periodo::class)->find($periodoId);

        //--- creamos una instancia al indicador ----
        $indicador = $entityManager->getRepository(Indicador::class)->find($indicadorId);

        //----- creamos una instancia el registro ----------        
        $registro = $entityManager->getRepository(Registro::class)->find($registroId);

        if ($registro) {
            $registro->setValor($valor);

            //--- hacemos persistente el cambio
            $entityManager = $doctrine->getManager();
            $entityManager->persist($registro);
            $entityManager->flush();
        } else {
            $salida = '0';
        }


        $salida = '1';
        return $salida;
    }
}
