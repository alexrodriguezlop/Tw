<?php
/**************************************************************************************************************
 *Autores: Rodríguez López Alejandro & Antonio Cordonie Campos.
 *Fichero: visualizacion.php
 *Contenido: Contiene la parte de código referente a la visualización de turnos en pantalla.
 * Nota: tanto la plantilla HTML como los ficheros CSS de esta web han sido obtidos de http://decsai.ugr.es/
 * ***********************************************************************************************************/

include_once("funciones/libreria.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es" >
<head>
    <title>Gestor de citas</title>

    <meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
    <meta name="description" content="Gestor de citas| Asignatura Tecnologias web" />
    <meta name="keywords" content="" />
    <meta http-equiv="content-language" name="language" content="es" />
    <meta http-equiv="X-Frame-Options" content="deny" />
    <meta name="verify-v1" content="wzNyCz8sYCNt7F8Bg9GWfznkU43lC9PNaZZAxRzkjJA=" />

    <link rel="shortcut icon" href="decsai.ico" type="image/vnd.microsoft.icon" />
    <link rel="icon" href="decsai.ico" type="image/vnd.microsoft.icon" />
    <link rel="stylesheet" id="css-style" type="text/css" href="css/estilo.css" media="all" />


    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/interface.js"></script>
    <script type="text/javascript" src="js/ejercicio.js"></script>
    

    <!--[if lt IE 7]>
    <style type="text/css">
        .dock img { behavior: url(iepngfix.htc) }
    </style>
    <![endif]-->
    
    <!--Los turnos se refrescan cada 5 segundos-->
    <script>setInterval(function(){ visualizacion_ajax()}, 2000 );</script>

    <!--El reloj se actualiza cada 1 segundo-->
    <script>setInterval(function(){ reloj()}, 1000 );</script>

    <!--Los avisos se refrescan cada 5 segundos-->
    <script>setInterval(function(){ get_aviso()}, 5000 );</script>
 
 
     <!--Valores animación de texto-->
    <script language="JavaScript">
    var id,pause=0,position=0;
    </script>


    <script type="text/javascript" src="js/xbMarquee.js"></script>




</head>
<!--Al cargar la pagina se invocan las funciones-->
<body onload="refresco_ajax();">


<div id="contenedor_margenes" class="">
    <div id="contenedor" class="">
        <div id="cabecera" class="">
            <h1 id="cab_inf">Ciencias de la Computación e Inteligencia Artificial</h1>
            <div id="formularios">
                <a id="enlace_ugr">Universidad de Granada</a>
                <span class="separador_enlaces"> | </span>
                <div class="depto titulo"><span class="titulo_stack">Departamento</span><a id="enlace_stack">Departamento de Ciencias de la Computación e I.A.</a></div>
                <span class="separador_enlaces"> | </span>
            </div>
        </div>

        <div id="general">
            <div id="pagina">
                <h1 id="titulo_pagina"><span class="texto_titulo">Próximos turnos:</span></h1>
                <div id="contenido" class="sec_interior">
                    <div class="content_doku">
                        <script> var mensaje = 'fdfdfd'</script>
                        <!--Centro-->
                        <div style="text-align:center">
                            <p id='reloj'></p>
                            <div id='turnos'>
                                
                            </div>
                            <div id='avisos'>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="lateral_doku">
            <div class="content_doku content_doku_display">
                <div class="content_doku">
                </div>
            </div>
        </div>


        <div id="interior_pie">
            <div id="pie">
            </div>
        </div>
    </div>
    
</div>
</body>
</html>
    