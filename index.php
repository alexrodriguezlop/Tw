<?php
/**************************************************************************************************************
 *Autores: Rodríguez López Alejandro & Antonio Cordonie Campos.
 *Fichero: index.php
 *Contenido: Contiene la parte de código referente al INDEX y a los usuarios tipo USUARIO.
 * Nota: tanto la plantilla HTML como los ficheros CSS de esta web han sido obtidos de http://decsai.ugr.es/
 * ***********************************************************************************************************/
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


	</head>
	<!--Cuando carge body lanza la lista de los RECURSOS-->
	<body onload="refresca_recursos_ajax()">

		<?php
		//Autenticacion

		session_start();
		include_once("funciones/libreria.php");

		//Reporte errores logueo
		//if(isset($_SESSION['mensaje']))
			//echo "<script>alert('".$_SESSION['mensaje']."')</script>";

		//Logueo
		//Existe sesion válida
		if(!isset($_SESION["id"])) {
			//Venir de formulario
			if(isset($_POST["user"])){
				if (!empty($_POST["user"]) && !empty($_POST["passwd"]))
					verificar_login($_POST["user"], $_POST["passwd"]);
				else
					$_SESSION["mensaje"] = "Uno o varios campos del formulario estan vacíos";

				redirigir();
			}
		}
		?>
		<div id="contenedor_margenes" class="">
			<div id="contenedor" class="">
				<div id="cabecera" class="">
					<h1 id="cab_inf">Ciencias de la Computación e Inteligencia Artificial</h1>
					<div id="formularios">
						<div id="buscador">
							<h2>Buscar</h2>
							<form action="http://www.google.es/search?hl=es&amp;as_qdr=all" method="get" onsubmit="javascript:document.getElementById('sq').value+=' site:decsai.ugr.es'">
								<div id="formulario_buscar">
									<div id="buscador-input">
										<label id="buscar" for="sq">
											<input type="hidden" name="search" value="1" />
											<input class="with_default_value" type="text" name="query" id="sq" value="búsqueda..." onclick="this.value=''" />
										</label>
										<label id="enviar_buscar" for="submit_buscar">
											<input type="image" src="img/transp.gif" alt="iniciar búsqueda" name="submit" id="submit_buscar" class="image-buscar"/>
										</label>
							  		</div>
								</div>
							 </form>
						  </div>
						<a href="../www.ugr.es/index.html" id="enlace_ugr">Universidad de Granada</a>
						<span class="separador_enlaces"> | </span>
						<div class="depto titulo"><span class="titulo_stack">Departamento</span><a id="enlace_stack">Departamento de Ciencias de la Computación e I.A.</a></div>
							<span class="separador_enlaces"> | </span>
						</div>
				</div>

				<!--Logout y miga de pan -->
				<div id="rastro-logout">
					<div class="logout">
						<?php
							if(isset($_SESSION['id'])) {
								echo "Bienvenido " . $_SESSION['nombre'] . " " . $_SESSION['primer_apellido'] . " " . $_SESSION['segundo_apellido']." ";
								echo "<a href='administracion.php'><img src='img/panel.png'></a><a id='cerrar_sesion' href='logout.php'>Cerrar Sesion</a></br>";
							}
						?>
					</div>
					<!--miga de pan -->
					<div id="rastro">
						<ul id="rastro_breadcrumb">
							<li class='first'>
								<a class='first' href='index.php'>Inicio</a>
							</li>
						</ul>
					</div>

				</div>

				<!--Menús-->
			  	<div id="general">
					<div id="menus">
			  			<div id="enlaces_secciones" class="mod-menu_secciones">
			  				<ul>
								<li class="selected tipo2-selected item-first_level">
									<a class="first">Inicio</a>
								</li>
								<?php
									if(!isset($_SESSION['id'])) {?>
									<!--Formulario de login-->
										<form class="widget_loginform" action="index.php" method="post">
											<div id="login_form_widget"
												 class="mod-buttons fieldset login_form login_form_widget">
												<label id="login_widget" for="ilogin_widget" class="login login_widget">
													<span>Usuario</span>
													<input name="user" id="ilogin_widget" value="usuario..."
														   onfocus="javascript:if(this.value='usuario...') this.value='';return true;"
														   type="text"/>
												</label>
												<label id="password_widget" for="ipassword_widget"
													   class="password password_widget">
													<span>Contraseña</span>
													<input name="passwd" id="ipassword_widget" type="password"/>
												</label>
												<label id="enviar_login_widget" for="submit_login_widget"
													   class="enviar_login enviar_login_widget">
													<input name="enviar" src="img/transp.gif"
														   alt="enviar datos de identificación" id="submit_login_widget"
														   class="image-enviar" type="image"/>
												</label>
												<div style="float:right; margin-top:5px;">
												</div>
												<span id="login_error_widget"> </span>
											</div>
										</form>

								<?php
								}
								?>

							</ul>
			  			</div>
					</div>
					<div id="pagina">
						<h1 id="titulo_pagina"><span class="texto_titulo">Recursos disponibles</span></h1>
						<div id="contenido" class="sec_interior">
							<div class="content_doku">
								
								<!--Almacena el ID recurso para pasárselo a AJAX-->
								<script language='JavaScript' type='text/JavaScript'>
									var id_recurso ='';
								</script>
								
								<!--Centro-->
								<div id="txtHint"></div>
								<br/>
								<!--La variable VALOR es generada con la funcion generar_select_dpto() -->
								<div style="margin-left: 35%">
										<img  style="display: none" class='icono' id="boton_volver" src='img/iconos/volver.png' title='Abandonar la cola de un recurso' alt='Baja' onClick='id_recurso=``;refresca_recursos_ajax(); boton_listar(0) '/>
										<img  class='icono' src='img/iconos/detalles.png' id="boton_listar" title='Consultar detalles del recurso' alt='Detalles' onClick='detalle_recurso_ajax(id_recurso)'/>
										<img  class='icono' src='img/iconos/insribir.png' title='Inscribirse en el recurso' alt='Inscribirse' onClick='generar_formulario_inscripcion_ajax(id_recurso)'/>
										<img  class='icono' src='img/iconos/posicion.png' title='Consultar posicion en la cola' alt='Posición' onClick='generar_formulario_localizador_ajax(id_recurso,1)'/>
										<img  class='icono' src='img/iconos/baja.png' title='Abandonar la cola de un recurso' alt='Baja' onClick='generar_formulario_localizador_ajax(id_recurso,2)'/>
								</div>
							</div>
						</div>
		  			</div>
				</div>
		  	</div>

			<div id="lateral_doku">
				<div class="content_doku content_doku_display">
					<div class="content_doku">
						<div id="lateral_derecho"></div>

					</div>
				</div>
			</div>

			<div id="interior_pie">
				<div id="pie">
					<a class="cmswebmap" href="index4290.html?p=mapa">Ver Mapa Web</a>
					<span class="separador_enlaces"> | </span>
					<a class="contactar" href="indexab6f.html?p=contacto">Contacto y Localización</a>
					<span class="separador_enlaces"> | </span>
					<a class="validador" href="indexb209.html?p=accesibilidad">Accesibilidad</a>
					<span class="separador_enlaces"> | </span>
					<a class="privacidad" href="index51ac.html?p=privacidad">Política de Privacidad</a>
					<p>
						<a href="../www.ugr.es/pages/creditos.html">&copy; 2016</a> <span class="separador_enlaces"> | </span> <a href="../www.ugr.es/index.html">Universidad de Granada</a>
					</p>
				</div>
			</div>
		</div>

	</body>
</html>
    