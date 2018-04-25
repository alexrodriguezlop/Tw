<?php
/**************************************************************************************************************
 *Autores: Rodríguez López Alejandro & Antonio Cordonie Campos.
 *Fichero: lincado.php
 *Contenido: Contiene la llamada a los MÉTODOS PHP desde los MÉTODOS JAVASCRIPT.
 * ***********************************************************************************************************/
    include "funciones/libreria.php";

    if(isset($_GET['v']))
        $v = ($_GET['v']);

    if(isset($_GET['f']))
        $f = ($_GET['f']);


    switch ($f) {
        case 1:
            mostrar_recursos($v);
            break;

        case 2:
            listar_recurso($v);
            break;

        case 3:
            generar_formulario_inscripcion();
            break;

        case 4:
            inscribir_usuario($v);
            break;

        case 5:
           generar_formulario_localizador($v);
            break;

        case 6:
            informar_posicion($v);
            break;

        case 7:
            eliminar_usuario_cola($v);
            break;
        
        case 8:
            $datos = explode(',',$v);
            generador_id($datos[0],$datos[1],$datos[2],$datos[3]);
            break;
        
        case 9:
            visualizacion_avisos();
            break;

        case 10:
            visualizacion();
            break;

        case 11:
            get_avisos();
            break;

        case 12:
            generar_gestor_recurso($v);
            break;
        case 13:
            $datos = explode(',',$v);
            cerrar_atencion($datos[0], $datos[1]);
            break;
        
        case 14:
            $datos = explode(',',$v);
            poner_espera($datos[0], $datos[1], $datos[2]);
            break;
        
        case 15:
            mostrar_cola_recurso_activo($v);
            break;
        
        case 16:
            mostrar_cola_recurso($v);
            break;

    }

?>