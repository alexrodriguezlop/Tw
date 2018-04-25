<?php
    /***************************************************************************************************************
     *Autores: Rodríguez López Alejandro & Antonio Cordonie Campos.
     *Fichero: administracion.php
     *Contenido: Contiene la parte de código referente a los usuarios tipo ADMINITRADOR.
     * Nota: tanto la plantilla HTML como los ficheros CSS de esta web han sido obtidos de http://decsai.ugr.es/
     * ************************************************************************************************************/

    //Cargamos la sesion
    session_start();
	/*
	echo "<pre>";
	echo var_dump($_SESSION);
	echo "</pre>";
	exit;
	*/
    require_once 'funciones/libreria.php';
	require_once 'funciones/funciones.php';
    $action = null;
    //Comprocion de la sesion (Sí­ existe y es válida para esta página)
    if($_SESSION["id"] && $_SESSION["cod"] == "adm") {
		
	//PROCESAR RESPUESTA DE FORMULARIOS
	$seguir = true;
	$mensaje_error = "";
	$mensaje_resultado = "";
	$error = "NO";
	
	if (isset($_POST['tipo_formulario']))
	{
		$link = iconectar();
		$tipo = $_POST['tipo_formulario'];
		reset ($_POST);
		while (list ($param, $val) = each ($_POST)) {
			$valor = $link->real_escape_string($_POST[$param]);
			$asignacion = "\$" . $param . "='" . $valor . "';";
			//echo $asignacion;
			eval($asignacion);
		}
		//$nombre = mysqli_real_escape_string($_POST['nombre']); --> metodo alternativo si no usamos el bucle anterior

		switch ($tipo) {
			case "form_alta_usuario":
				if(trim($nombre) == ""){
					$seguir = false;
					$mensaje_error .= "El nombre no puede estar vacío<br>";
				}
				if(trim($primer_apellido) == ""){
					$seguir = false;
					$mensaje_error .= "El primer apellido no puede estar vacío<br>";
				}
                if(trim($segundo_apellido) == ""){
                    $seguir = false;
                    $mensaje_error .= "El segundo apellido no puede estar vacío<br>";
                }
                if(trim($dni) == ""){
                    $seguir = false;
                    $mensaje_error .= "El dni no puede estar vacío<br>";
                }
                if(trim($email) == ""){
                    $seguir = false;
                    $mensaje_error .= "El email no puede estar vacío<br>";
                }                            
                if(trim($login) == ""){
                    $seguir = false;
                    $mensaje_error .= "El login no puede estar vacío<br>";
                }                
                if(trim($pass) == ""){
                    $seguir = false;
                    $mensaje_error .= "El pass no puede estar vacío<br>";
                } 
                if(trim($pass2) == ""){
                    $seguir = false;
                    $mensaje_error .= "El campo repetir contraseña no puede estar vacío<br>";
                }                               
                if(trim($codigo) == ""){
                    $seguir = false;
                    $mensaje_error .= "El Perfil no puede estar vacío<br>";
                }                
                if(trim($departamento) == ""){
                    $seguir = false;
                    $mensaje_error .= "El Departamento no puede estar vacío<br>";
                }                
				if($pass!=$pass2){
	                    $seguir = false;
                    $mensaje_error .= "Las claves deben de ser iguales<br>";	
				}
				
				if($seguir){
					$id_profesional = generador_id_profesional($codigo, $nombre, $primer_apellido, $segundo_apellido, $dni);
					//$id_profesional = $codigo.strtolower(substr($nombre, -2)).strtolower(substr($segundo_apellido, 0, 2)).strtolower(substr($primer_apellido, 0, 2)).substr($dni, -5,1).substr($dni, -2);
					$sql = "SELECT * FROM profesionales WHERE id_profesional='".$id_profesional."';";

					$rs = $link->query($sql);
					if($rs->num_rows > 0){
						$error = "SI";
						$mensaje_error = "Ya existe un usuario con esa identificación en la base de datos";
					}
					$sql = "SELECT * FROM profesionales WHERE email='".$email."';";

					$rs = $link->query($sql);
					if($rs->num_rows > 0){
						$error = "SI";
						$mensaje_error = "Ya se está utilizando el correo";
					}
					$sql = "SELECT * FROM profesionales WHERE login='".$login."';";

					$rs = $link->query($sql);
					if($rs->num_rows > 0){
						$error = "SI";
						$mensaje_error = "Ya se está utilizando el login";
					}
					$sql = "SELECT * FROM profesionales WHERE dni='".$dni."';";

					$rs = $link->query($sql);
					if($rs->num_rows > 0){
						$error = "SI";
						$mensaje_error = "Ya se está utilizando el dni";
					}
					$sql = "INSERT INTO profesionales ( id_profesional,cod_profesional,nombre,primer_apellido,segundo_apellido,dni,email,login,pass,departamento) ";
					$sql .= "VALUES ('".$id_profesional."','".$codigo."','".$nombre."','".$primer_apellido."','".$segundo_apellido."','".$dni."','".$email."','".$login."','".md5($pass)."','".$departamento."')";

					$rs = $link->query($sql);
					if(!$rs){
						$mensaje_resultado = "No se ha podido dar de alta el usuario";
						$error = "SI";
					}else{
						$mensaje_resultado = "Se ha dado de alta el usuario correctamente con identificación <strong>".$id_profesional."</strong>";
						$error = "NO";
						$tipo = '';
					}
				}else{
					$error = "SI";
				}
				break;
			
			case "form_baja_usuario":
                if(trim($dni_baja) == ""){
                    $seguir = false;
                    $mensaje_error .= "El campo DNI no puede estar vacío<br>";
                }
				if($dni_baja == $_SESSION['dni']){
					$seguir = false;
					$mensaje_error = "Debe ser borrado desde otra cuenta. ";
				}


                //Si ha introducido algo en el campo DNI buscamos en la base de datos ese DNI y borramos:
                if($seguir){
					$sql = "SELECT id_profesional FROM profesionales WHERE dni='".$dni_baja."';";
					$rs = $link->query($sql);
					if($rs->num_rows > 0){

						$row = $rs->fetch_assoc();
						$id_profesional = $row['id_profesional'];
						
						$sql = "DELETE FROM profesionales WHERE dni = '".$dni_baja."'";
						$rs = $link->query($sql);
						if(!$rs){
							$mensaje_resultado = "No se ha podido dar de baja al usuario";
							$error = "SI";  
						}else{
							$mensaje_resultado = "Se ha dado de baja correctamente <strong>".$dni_baja."</strong>";
							$error = "NO";
							$tipo = '';
							//Borrar todos los recursos asosciados al profesor
							$sql = "SELECT id_recurso FROM recursos WHERE id_profesional = '".$id_profesional."'";
							$rs2 = $link->query($sql);
							while($row = $rs2->fetch_assoc()){
								eliminar_recurso_formulario($row['id_recurso']);
							}
						}
					}else{
						$error = "SI";
						$mensaje_error = "No existe ningún usuario con ese DNI";
					}
                }else{
                    $error = "SI";  
                }                            
				break;
				
			case "form_modificar_usuario":
				if(trim($nombre_mod) == ""){
					$seguir = false;
					$mensaje_error .= "El nombre no puede estar vacío<br>";
				}
				if(trim($primer_apellido_mod) == ""){
					$seguir = false;
					$mensaje_error .= "El primer apellido no puede estar vacío<br>";
				}
                if(trim($segundo_apellido_mod) == ""){
                    $seguir = false;
                    $mensaje_error .= "El segundo apellido no puede estar vacío<br>";
                }
                if(trim($dni_mod) == ""){
                    $seguir = false;
                    $mensaje_error .= "El dni no puede estar vacío<br>";
                }
                if(trim($email_mod) == ""){
                    $seguir = false;
                    $mensaje_error .= "El email no puede estar vacío<br>";
                }                            
                if(trim($login_mod) == ""){
                    $seguir = false;
                    $mensaje_error .= "El login no puede estar vacío<br>";
                }                
                                          
                if(trim($codigo_mod) == ""){
                    $seguir = false;
                    $mensaje_error .= "El Perfil no puede estar vacío<br>";
                }                
                if(trim($departamento_mod) == ""){
                    $seguir = false;
                    $mensaje_error .= "El Departamento no puede estar vacío<br>";
                }                
				if(trim($pass_mod)!="" && $pass_mod!=$pass2_mod){
	                    $seguir = false;
                    $mensaje_error .= "Las claves deben ser iguales<br>";	
				}
				
				if($seguir){
					$sql = "SELECT * FROM profesionales WHERE email='".$email_mod."' AND id_profesional<>'".$id_profesional_modificar."';";
					$rs = $link->query($sql);
					if($rs->num_rows > 0){
						$error = "SI";
						$mensaje_error = "Ya se está utilizando ese correo";	
					}
					$sql = "SELECT * FROM profesionales WHERE login='".$login_mod."' AND id_profesional<>'".$id_profesional_modificar."';";
					$rs = $link->query($sql);
					if($rs->num_rows > 0){
						$error = "SI";
						$mensaje_error = "Ya se está utilizando ese login";	
					}
					$sql = "SELECT * FROM profesionales WHERE dni='".$dni_mod."' AND id_profesional<>'".$id_profesional_modificar."';";
					$rs = $link->query($sql);
					if($rs->num_rows > 0){
						$error = "SI";
						$mensaje_error = "Ya se está utilizando ese dni";	
					}
					if($error!="SI"){
					if(trim($pass_mod)==""){
						$sql = "UPDATE profesionales SET 
								cod_profesional='".$codigo_mod."',
								nombre='".$nombre_mod."',
								primer_apellido='".$primer_apellido_mod."',
								segundo_apellido='".$segundo_apellido_mod."',
								dni='".$dni_mod."',
								email='".$email_mod."',
								login='".$login_mod."',
								departamento='".$departamento_mod."'
								WHERE id_profesional='".$id_profesional_modificar."';";
					}else{
						$sql = "UPDATE profesionales SET 
								cod_profesional='".$codigo_mod."',
								nombre='".$nombre_mod."',
								primer_apellido='".$primer_apellido_mod."',
								segundo_apellido='".$segundo_apellido_mod."',
								dni='".$dni_mod."',
								email='".$email_mod."',
								login='".$login_mod."',
								pass='".md5($pass_mod)."',
								departamento='".$departamento_mod."'
								WHERE id_profesional='".$id_profesional_modificar."';";
					}
					$rs = $link->query($sql);
					if(!$rs){
						$mensaje_resultado = "No se ha podido modificar el usuario";
						$error = "SI";	
					}else{
						$mensaje_resultado = "Se ha modificado el usuario correctamente con identificación <strong>".$id_profesional_modificar."</strong>";
						$error = "NO";
						$tipo = '';
					}
				}
				}else{
					$error = "SI";	
				}
				break;
				
			case "form_alta_recurso":
				if(trim($nombre) == ""){
					$seguir = false;
					$mensaje_error .= "El campo nombre no puede estar vacío<br>";
				}
				if(trim($descripcion) == ""){
					$seguir = false;
					$mensaje_error .= "El campo descripción no puede estar vacío<br>";
				}
                if(trim($asignatura) == ""){
                    $seguir = false;
                    $mensaje_error .= "El campo de asignatura no puede estar vacío<br>";
                }
				if(trim($localizacion_recurso) == ""){
                    $seguir = false;
                    $mensaje_error .= "El despacho no puede estar vacío<br>";
                }
                if(trim($fecha) == ""){
                    $seguir = false;
                    $mensaje_error .= "El campo fecha no puede estar vacío<br>";
                }      
				if(trim($fecha < date("Y-m-d"))){
					$seguir = false;
					$mensaje_error .= "La fecha no es válida, ya ha pasado<br>";
				}                      
                if(trim($hora_inicio) == ""){
                    $seguir = false;
                    $mensaje_error .= "El campo hora de inicio no puede estar vacío<br>";
                }      
				if(trim($hora_fin) == ""){
                    $seguir = false;
                    $mensaje_error .= "El campo hora final no puede estar vacío<br>";
                }            
                if(trim($id_profesional) == ""){
                    $seguir = false;
                    $mensaje_error .= "El campo profesor no puede estar vacío<br>";
                } 
                if(trim($departamento) == ""){
                    $seguir = false;
                    $mensaje_error .= "El campo de departamento no puede estar vacío<br>";
                }  
				if(!isset($mensaje)){
					$mensaje = '';
				}
				
				if($seguir){
					$id_recurso = generador_id_recurso($nombre, $departamento, $localizacion_recurso, date("Y-m-d",strtotime($fecha)), $hora_inicio);
					//$id_profesional = $codigo.strtolower(substr($nombre, -2)).strtolower(substr($segundo_apellido, 0, 2)).strtolower(substr($primer_apellido, 0, 2)).substr($dni, -5,1).substr($dni, -2);

					$sql = "SELECT * FROM recursos WHERE id_recurso='".$id_recurso."';";
					
					$rs = $link->query($sql);
					if($rs->num_rows > 0){
						$error = "SI";
						$mensaje_error = "Ya existe un recurso con esa identificación en la base de datos";	
					}
					
					$sql = "INSERT INTO recursos ( id_recurso,nombre,descripcion,asignatura,localizacion_recurso,fecha,hora_inicio,hora_fin,id_profesional,departamento,mensaje) ";
					$sql .= "VALUES ('".$id_recurso."','".$nombre."','".$descripcion."','".$asignatura."','".$localizacion_recurso."','".$fecha."','".$hora_inicio."','".$hora_fin."','".$id_profesional."','".$departamento."','".$mensaje."')";
					
					$rs = $link->query($sql);
					if(!$rs){
						$mensaje_resultado = "No se ha podido dar de alta el recurso";
						$error = "SI";	
					}else{
						$mensaje_resultado = "Se ha dado de alta el recurso correctamente con identificación <strong>".$id_recurso."</strong>";
						$error = "NO";
						$tipo = '';
					}
				}else{
					$error = "SI";	
				}
				break;
			
			case "form_baja_recurso":
                if(trim($id_recurso) == ""){
                    $seguir = false;
                    $mensaje_error .= "El Identificador del recurso no puede estar vacío<br>";
                }
				
                if($seguir){
					//Borrar todos los recursos asosciados al profesor
					$rs = eliminar_recurso_formulario($id_recurso);
                    if(!$rs){
                        $mensaje_resultado = "No se ha podido dar de baja el recurso";
                        $error = "SI";  
                    }else{
                        $mensaje_resultado = "Se ha dado de baja correctamente el recurso <strong>".$id_recurso."</strong>";
                        $error = "NO";
						$tipo = '';
                    }
                }else{
                    $error = "SI";  
                }                            
				break;
				
			case "form_modificar_recurso":
				if(trim($nombre_mod) == ""){
					$seguir = false;
					$mensaje_error .= "El nombre no puede estar vacío<br>";
				}
				if(trim($descripcion_mod) == ""){
					$seguir = false;
					$mensaje_error .= "La descripción no puede estar vacía<br>";
				}
                if(trim($asignatura_mod) == ""){
                    $seguir = false;
                    $mensaje_error .= "La asignatura no puede estar vacía<br>";
                }
				if(trim($localizacion_recurso_mod) == ""){
                    $seguir = false;
                    $mensaje_error .= "El despacho no puede estar vacío<br>";
                }
                if(trim($fecha_mod) == ""){
                    $seguir = false;
                    $mensaje_error .= "El campo fecha no puede estar vacío<br>";
                }
				if(trim($fecha_mod < date("Y-m-d"))){
					$seguir = false;
					$mensaje_error .= "La fecha no es válida, ya ha pasado<br>";
				}                                         
                if(trim($hora_inicio_mod) == ""){
                    $seguir = false;
                    $mensaje_error .= "El campo hora de inicio no puede estar vacío<br>";
                }      
				if(trim($hora_fin_mod) == ""){
                    $seguir = false;
                    $mensaje_error .= "El campo hora final no puede estar vacío<br>";
                }            
                if(trim($id_profesional_mod) == ""){
                    $seguir = false;
                    $mensaje_error .= "El campo profesor no puede estar vacío<br>";
                } 
                if(trim($departamento_mod_recurso) == ""){
                    $seguir = false;
                    $mensaje_error .= "El campo de departamento no puede estar vacío<br>";
                } 
				
				if($seguir){
					$sql = "UPDATE recursos SET 
							nombre='".$nombre_mod."',
							descripcion='".$descripcion_mod."',
							asignatura='".$asignatura_mod."',
							localizacion_recurso='".$localizacion_recurso_mod."',
							fecha='".$fecha_mod."',
							hora_inicio='".$hora_inicio_mod."',
							hora_fin='".$hora_fin_mod."',
							id_profesional='".$id_profesional_mod."',
							departamento='".$departamento_mod_recurso."',
							mensaje='".$mensaje_mod."'
							WHERE id_recurso='".$id_recurso_modificar."';";
					$rs = $link->query($sql);
					if(!$rs){
						$mensaje_resultado = "No se ha podido modificar el recurso";
						$error = "SI";	
					}else{
						$mensaje_resultado = "Se ha modificado el recurso correctamente con identificación <strong>".$id_recurso_modificar."</strong>";
						$error = "NO";
						$tipo = '';
					}
				}else{
					$error = "SI";	
				}
				break;
			
		}
	}//Fin del post
	
?>
		<!DOCTYPE html>
        <html lang="en">
		<head>
        	<meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Panel de administración</title>
			<meta name="description" content="Gestor de citas| Asignatura Tecnologias web" />

			<link rel="shortcut icon" href="decsai.ico" type="image/vnd.microsoft.icon" />
            <link rel="icon" href="decsai.ico" type="image/vnd.microsoft.icon" />
            <link rel="stylesheet" id="css-style" type="text/css" href="css/estilo.css" media="all" />

			<script type="text/javascript" src="js/jquery.js"></script>
            <script type="text/javascript" src="js/interface.js"></script>
            <script type="text/javascript" src="js/ejercicio.js"></script>
            <script type="text/javascript" src="js/funciones_jquery.js"></script>
            <script type="text/javascript" src="js/jquery-ui.js"></script>
		</head>
		<body>
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
                        <div class="depto titulo"><span class="titulo_stack">Departamento</span><a href="index-2.html" id="enlace_stack">Departamento de Ciencias de la Computación e I.A.</a></div>
                        <span class="separador_enlaces"> | </span>
                    </div>
                </div>
                <div id="rastro-logout">
                    <div class="logout">
                        Bienvenido <?php echo $_SESSION['nombre']." ".$_SESSION['primer_apellido']." ".$_SESSION['segundo_apellido']?>
                        <a id="cerrar_sesion" href='logout.php'>Cerrar Sesion</a>
                        </br>
                    </div>
                    <div id="rastro">
                        <ul id="rastro_breadcrumb">
                            <li class='first'>
                                <a class='first' href='index.php'>Inicio</a>
                                <a href='administracion.php'>Administración</a>
                            </li>
                        </ul>
                    </div>

                </div>
                <!--Menús-->
                <div id="general">
                    <div id="menus">
                        <div id="enlaces_secciones" class="mod-menu_secciones">
                            <ul>
                                <li class="selected tipo2-selected item-first_level"><a>Menú de administración</a></li>
                                <li class="tipo2 item-first_level"><a id="ver_portada_adm_usuarios" href="#">Administración de usuarios</a>
                                    <ul>
                                        <li class="tipo1 item-second_level"><a id="ver_form_alta_usuario" href="#">Alta de nuevo usuario</a></li>
                                        <li class="tipo1 item-second_level"><a id="ver_form_baja_usuario" href="#">Baja de usuario</a></li>
                                        <li class="tipo1 item-second_level"><a id="ver_form_modi_usuario" href="#">Modificar usuario</a></li>
                                        <li class="tipo1 item-second_level"><a id="ver_form_cons_usuario" href="#">Consultar usuario</a></li>
                                    </ul>
                                </li>
                            </ul>
                            <ul>
                                <li class="tipo2 item-first_level"><a id="ver_portada_adm_recursos" href="#" >Administración de Recursos</a>
                                    <ul>
                                        <li class="tipo1 item-second_level"><a id="ver_form_alta_recurso" href="#">Alta de nuevo recurso</a></li>
                                        <li class="tipo1 item-second_level"><a id="ver_form_baja_recurso" href="#">Baja de recurso</a></li>
                                        <li class="tipo1 item-second_level"><a id="ver_form_modi_recurso" href="#">Modificar recurso</a></li>
                                        <li class="tipo1 item-second_level"><a id="ver_form_cons_recurso" href="#">Consultar recurso</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>


                    </div>
                    <div id="pagina">
                        <h1 id="titulo_pagina"><span class="texto_titulo">Administración</span></h1>
                        <div id="contenido" class="sec_interior">
                            <div class="content_doku">
								<div style="text-align:center">
                                    <br /><br />
                                    	<?php echo (trim($mensaje_resultado) != "") ? '<div id="mensaje_resultado">'.$mensaje_resultado.'</div>' : ''; ?>
                                        <?php echo (trim($mensaje_error) != "") ? '<div id="mensaje_error">'.$mensaje_error.'</div>' : ''; ?>


                                    	<!-- Capas de los menus -->
                                    	<?php
                                        if($error == "SI" && $tipo != ""){
											echo '<div id="portada_administracion" style="display:none">';
										}else{
											echo '<div id="portada_administracion">';
										}
                                        ?>
                                        	<img title="Departamento de Ciencias de la Computación e Inteligencia Artificial DECSAI" alt="logo" src="img/WebDECSAI.png" />
                                        </div>
                                        <?php
										if($error == "SI" && $tipo == "form_alta_usuario"){
											echo '<div id="alta_usuario">';
										}else{
											echo '<div id="alta_usuario" style="display:none">';
										}
										?>
                                        	<p><strong>Alta de nuevo usuario</strong></p>
                                            <!--Formulario Altas-->
                                            <form id="form_alta_usuario" method="post" action="#" >
                                                <table style="width:80%">
                                                    <tr>
                                                        <td>
                                                            <label for="nombre">Nombre: </label>
                                                            <input type="text" id="nombre" name="nombre" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_alta_usuario") ? $nombre : ''; ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="primer_apellido">Primer apellido: </label>
                                                            <input type="text" id="primer_apellido" name="primer_apellido" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_alta_usuario") ? $primer_apellido : ''; ?>" />
                                                        </td>                     
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="segundo_apellido">Segundo apellido: </label>
                                                            <input type="text" id="segundo_apellido" name="segundo_apellido" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_alta_usuario") ? $segundo_apellido : ''; ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="dni">Dni: </label>
                                                            <input placeholder="XXXXXXXXL" type="text" id="dni" name="dni" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_alta_usuario") ? $dni : ''; ?>" /><br><em>El formato correcto del DNI es: XXXXXXXXL</em>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="email">Email: </label>
                                                            <input type="email" id="email" name="email" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_alta_usuario") ? $email : ''; ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr>   
                                                        <td>
                                                            <label for="login">Login: </label>
                                                            <input type="text" id="login" name="login" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_alta_usuario") ? $login : ''; ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr>    
                                                        <td>
                                                            <label for="pass">Contraseña: </label>
                                                            <input type="password" id="pass" name="pass" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_alta_usuario") ? $pass : ''; ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr>    
                                                        <td>
                                                            <label for="pass2">Repita la contraseña: </label>
                                                            <input type="password" id="pass2" name="pass2" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_alta_usuario") ? $pass2 : ''; ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr>    
                                                        <td>
                                                            <label for="codigo">Perfil: </label>
                                                            <select type="text" id="codigo" name="codigo" value="">
                                                                <option selected="selected" value="" ></option>
                                                                <option value="prf">Profesor</option>
                                                                <option value="adm">Administrador</option>
                                                            </select>
                                                        </td>                   
                                                    </tr>
                                                    <tr>    
                                                        <td>
                                                            <label>Departamento: </label>
                                                            <input type="text" id="departamento" name="departamento" value="">
                                                        </td>          
                                                    </tr>
                                                    <tr>
                                                        <td class="txt-center">
                                                        	<input type="hidden" id="tipo_formulario" name="tipo_formulario" value="form_alta_usuario" />
                                                            <input type="submit" id="enviar_alta_usuario" name="enviar" value="Enviar" class="btn-enviar" />
                                                        </td>
                                                    </tr>
                                                </table>
                                            </form >
                                        </div>
                                        <div id="baja_usuario" style="display:none">
                                        	<p><strong>Baja de usuario</strong></p>
                                            <form id="form_baja_usuario" method="post" action="#">
                                                <table style="width:80%">
                                                    <tr>
                                                        <td>
                                                            <label for="dni_baja">Dni: </label>
                                                            <input placeholder="XXXXXXXXL" type="text" id="dni_baja" name="dni_baja" value="" />
                                                            <br><em>El formato del DNI es: XXXXXXXXL<em>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" id="tipo_formulario" name="tipo_formulario" value="form_baja_usuario" />
                                                            <input type="submit" class="btn-enviar" id="enviar_baja_usuario" name="enviar" value="Dar de baja" />

                                                        </td>
                                                    </tr>
                                                </table>
                                            </form>

											<select id="slt_lista_usuario_baja">
                                                	<option value="">Selecciona el usuario para dar de baja</option>
                                                    <?php 
													$link = iconectar();
													$sql = "SELECT * FROM profesionales";
													$rs = $link->query($sql);
													if(!$rs){
														die("error database");
													}
													while($row = $rs->fetch_assoc()){
														echo "<option value='".$row['id_profesional']."'>".$row['nombre']." ".$row['primer_apellido']." ".$row['segundo_apellido']."</option>";
													}
													?>
                                                </select>
												<?php
												if($error == "SI" && $tipo == "form_baja_usuario"){
													echo '<table style="width:80%;" id="tbl_form_baja">';
												}else{
													echo '<table style="width:80%; display:none;" id="tbl_form_baja">';
												}
												?>
                                                        <td class="txt-center">
                                                        	<input type="hidden" id="tipo_formulario" name="tipo_formulario" value="form_baja_usuario" />
                                                            <input type="hidden" id="id_profesional" name="id_profesional" value="" />
                                                            <input type="submit" form="form_baja_usuario" id="enviar_baja_usuario" name="enviar" value="Dar de baja" class="btn-enviar" />
                                                        </td>
                                                    </tr>
                                                </table>
                                            </form >
                                        </div>                                                                                          
                                        </div>
                                        <?php
										if($error == "SI" && $tipo == "form_modificar_usuario"){
											echo '<div id="modificar_usuario">';
										}else{
											echo '<div id="modificar_usuario" style="display:none">';
										}
										?>
                                       		<p><strong>Modificar usuario</strong></p>
                                        	<form id="form_modificar_usuario" method="post" action="#">
                                            	<select id="slt_lista_usuario_mod">
                                                	<option value="">Selecciona el usuario a modificar</option>
                                                    <?php 
													$link = iconectar();
													$sql = "SELECT * FROM profesionales";
													$rs = $link->query($sql);
													if(!$rs){
														die("error database");
													}
													while($row = $rs->fetch_assoc()){
														echo "<option value='".$row['id_profesional']."'>".$row['nombre']." ".$row['primer_apellido']." ".$row['segundo_apellido']."</option>";
													}
													?>
                                                </select>
                                                <?php
												if($error == "SI" && $tipo == "form_modificar_usuario"){
													echo '<table style="width:80%;" id="tbl_form_modificar">';
												}else{
													echo '<table style="width:80%; display:none;" id="tbl_form_modificar">';
												}
												?>
                                                
                                                    <tr>
                                                        <td>
                                                            <label for="nombre_mod">Nombre: </label>
                                                            <input type="text" id="nombre_mod" name="nombre_mod" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_modificar_usuario") ? $nombre_mod : ''; ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="primer_apellido_mod">Primer apellido: </label>
                                                            <input type="text" id="primer_apellido_mod" name="primer_apellido_mod" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_modificar_usuario") ? $primer_apellido_mod : ''; ?>" />
                                                        </td>                     
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="segundo_apellido_mod">Segundo apellido: </label>
                                                            <input type="text" id="segundo_apellido_mod" name="segundo_apellido_mod" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_modificar_usuario") ? $segundo_apellido_mod : ''; ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="dni_mod">Dni: </label>
                                                            <input type="text" id="dni_mod" name="dni_mod" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_modificar_usuario") ? $dni_mod : ''; ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="email_mod">Email: </label>
                                                            <input type="email" id="email_mod" name="email_mod" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_modificar_usuario") ? $email_mod : ''; ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr>   
                                                        <td>
                                                            <label for="login_mod">Login: </label>
                                                            <input type="text" id="login_mod" name="login_mod" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_modificar_usuario") ? $login_mod : ''; ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr>    
                                                        <td>
                                                            <label for="pass_mod">Contraseña: </label>
                                                            <input type="password" id="pass_mod" name="pass_mod" value="" />
                                                            <br/><em>Deje la contraseña en blanco si desea mantener la actual</em>
                                                        </td>
                                                    </tr>
                                                    <tr>    
                                                        <td>
                                                            <label for="pass2_mod">Repita la contraseña: </label>
                                                            <input type="password" id="pass2_mod" name="pass2_mod" value="" />
                                                        </td>
                                                    </tr>
                                                    <tr>    
                                                        <td>
                                                            <label for="codigo_mod">Perfil: </label>
                                                            <select type="text" id="codigo_mod" name="codigo_mod">
                                                                <option value="" ></option>
                                                                <?php
																if(isset($_POST['tipo_formulario']) && $tipo=="form_modificar_usuario" && $codigo_mod == "prf"){
                                                                	echo '<option value="prf" selected="selected">Profesor</option>';
																}else{
																	echo '<option value="prf">Profesor</option>';
																}
																if(isset($_POST['tipo_formulario']) && $tipo=="form_modificar_usuario" && $codigo_mod == "adm"){
                                                                	echo '<option value="adm" selected="selected">Administrador</option>';
																}else{
																	echo '<option value="adm">Administrador</option>';
																}
                                                                ?>
                                                            </select>
                                                        </td>                   
                                                    </tr>
                                                    <tr>    
                                                        <td>
                                                            <label for="departamento_mod">Departamento: </label>
                                                            <input type="text" id="departamento_mod" name="departamento_mod" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_modificar_usuario") ? $departamento_mod : ''; ?>">
                                                        </td>          
                                                    
                                                    <tr>
                                                        <td class="txt-center">
                                                        	<input type="hidden" id="tipo_formulario" name="tipo_formulario" value="form_modificar_usuario" />
                                                            <input type="hidden" id="id_profesional_modificar" name="id_profesional_modificar" value="" />
                                                            <input type="submit" id="enviar_modificar_usuario" name="enviar" value="Enviar" class="btn-enviar" />
                                                        </td>
                                                    </tr>
                                                </table>
                                            </form >
                                        </div>
                                        <div id="consultar_usuario" style="display:none">
                                        	<p><strong>Consultar usuario</strong></p>
                                            <select id="slt_lista_usuario_cons">
                                                    <option value="">Selecciona el usuario a consultar</option>
                                                    <?php 
                                                    $link = iconectar();
                                                    $sql = "SELECT * FROM profesionales";
                                                    $rs = $link->query($sql);
                                                    if(!$rs){
                                                        die("error database");
                                                    }
                                                    while($row = $rs->fetch_assoc()){
                                                        echo "<option value='".$row['id_profesional']."'>".$row['nombre']." ".$row['primer_apellido']." ".$row['segundo_apellido']."</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <div id="box_info_consultar"></div>
                                        </div>
                                       
                                        <?php
										if($error == "SI" && $tipo == "form_alta_recurso"){
											echo '<div id="alta_recurso">';
										}else{
											echo '<div id="alta_recurso" style="display:none">';
										}
										?>
                                        	<p><strong>Alta de nuevo recurso</strong></p>
                                            <!--Formulario Altas-->
                                            <form id="form_alta_recurso" method="post" action="#" >
                                                <table style="width:80%">
                                                    <tr>
                                                        <td>
                                                            <label for="nombre">Nombre: </label>
                                                            <input type="text" id="nombre" name="nombre" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_alta_recurso") ? $nombre : ''; ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="descripcion">Descripción: </label>
                                                            <textarea id="descripcion" name="descripcion"><?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_alta_recurso") ? $descripcion : ''; ?></textarea>
                                                        </td>                     
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="asignatura">Asignatura: </label>
                                                            <input type="text" id="asignatura" name="asignatura" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_alta_recurso") ? $asignatura : ''; ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="localizacion_recurso">Despacho: </label>
                                                            <input type="text" id="localizacion_recurso" name="localizacion_recurso" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_alta_recurso") ? $localizacion_recurso : ''; ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="fecha">Fecha: </label>
                                                            <input type="date" placeholder="2016-06-25" id="fecha" name="fecha" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_alta_recurso") ? $fecha : ''; ?>" /><br><em>Formato de fecha: 2016-06-25</em>
                                                        </td>
                                                    </tr>
                                                    <tr>   
                                                        <td>
                                                            <label for="hora_inicio">Hora inicio: </label>
                                                            <input type="text" placeholder="21:00:00" id="hora_inicio" name="hora_inicio" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_alta_recurso") ? $hora_inicio : ''; ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr>    
                                                        <td>
                                                            <label for="hora_fin">Hora fin: </label>
                                                            <input type="text" placeholder="21:00:00" id="hora_fin" name="hora_fin" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_alta_recurso") ? $hora_fin : ''; ?>" /><br><em>Formato de hora: 21:00:00</em>
                                                        </td>
                                                    </tr>
                                                    <tr>    
                                                        <td>
                                                            <label for="id_profesional">Profesor: </label>
                                                            <select id="id_profesional" name="id_profesional">
                                                            	<option selected='selected' value=''></option>
																<?php 
                                                                $link = iconectar();
                                                                $sql = "SELECT * FROM profesionales WHERE cod_profesional='prf'";
                                                                $rs = $link->query($sql);
                                                                if(!$rs){
                                                                    die("error database");
                                                                }
                                                                while($row = $rs->fetch_assoc()){
																	if(isset($_POST['tipo_formulario']) && $tipo=="form_alta_recurso" && $row['id_profesional']==$id_profesional){
																		echo "<option value='".$row['id_profesional']."' selected='selected'>".$row['nombre']." ".$row['primer_apellido']." ".$row['segundo_apellido']."</option>";
																	}else{
                                                                    	echo "<option value='".$row['id_profesional']."'>".$row['nombre']." ".$row['primer_apellido']." ".$row['segundo_apellido']."</option>";
																	}
                                                                }
                                                                ?>
                                                                </select>
                                       					</td>          
                                                    </tr>
                                                    <tr>    
                                                        <td>
                                                            <label for="departamento">Departamento: </label>
                                                            <input type="text" id="departamento" name="departamento" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_alta_recurso") ? $departamento : ''; ?>" />
                                                        </td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td class="txt-center">
                                                        	<input type="hidden" id="tipo_formulario" name="tipo_formulario" value="form_alta_recurso" />
                                                            <input type="submit" id="enviar_alta_recurso" name="enviar" value="Enviar" class="btn-enviar" />
                                                        </td>
                                                    </tr>
                                                </table>
                                            </form >
                                        </div>
                                        <div id="baja_recurso" style="display:none">
                                        	<p><strong>Baja de recurso</strong></p>
                                            <form id="form_baja_recurso" method="post" action="#">
                                                <table style="width:80%">
                                                    <tr>
                                                        <td>
                                                            <label for="id_recurso_borrar"><strong>Identificador recurso: </strong></label>
                                                            <input type="text" id="id_recurso_borrar" placeholder="3213c181817" name="id_recurso" value="" /><br><em>Ejemplo de id: 3213c181817</em>
                                                        </td>
                                                    </tr>
                                                   <tr>
                                                        <td class="txt-center">
                                                        	<input type="hidden" id="tipo_formulario" name="tipo_formulario" value="form_baja_recurso" />
                                                            <input type="hidden" id="id_recurso" name="id_recurso" value="" />          
                                                        </td>
                                                    </tr>                                                   
                                            <tr>
                                                	<td><strong>Recursos: </strong>
                                          	<select id="slt_lista_recurso_baja">
                                                	<option value="">Selecciona el recurso para dar de baja</option>
                                                    <?php 
													$link = iconectar();
													$sql = "SELECT * FROM recursos";
													$rs = $link->query($sql);
													if(!$rs){
														die("error database");
													}
													while($row = $rs->fetch_assoc()){
														echo "<option value='".$row['id_recurso']."'>[".$row['asignatura']."] ".$row['nombre']."</option>";
													}
													?>
                                                </select>
                                                <tr>
                                                	<td>
                                                <input type="submit" id="enviar_baja_recurso" name="enviar" value="Dar de baja" class="btn-enviar" />					
                                                	</td>
                                                </tr>                                                
                                        			</td>
                                            </tr>
                                                <?php
												if($error == "SI" && $tipo == "form_baja_recurso"){
													echo '<table style="width:80%;" id="tbl_form_baja_recurso">';
												}else{
													echo '<table style="width:80%; display:none;" id="tbl_form_baja_recurso">';
												}
												?>							
                                                </table>
                                            </form>
                                        </div>
                                        <?php
										if($error == "SI" && $tipo == "form_modificar_recurso"){
											echo '<div id="modificar_recurso">';
										}else{
											echo '<div id="modificar_recurso" style="display:none">';
										}
										?>
                                       		<p><strong>Modificar recurso</strong></p>
                                        	<form id="form_modificar_recurso" method="post" action="#">
                                            	<select id="slt_lista_recurso_mod">
                                                	<option value="">Selecciona el recurso a modificar</option>
                                                    <?php 
													$link = iconectar();
													$sql = "SELECT * FROM recursos WHERE fecha >='".date("Y-m-d")."';";
													$rs = $link->query($sql);
													if(!$rs){
														die("error database");
													}
													while($row = $rs->fetch_assoc()){
														echo "<option value='".$row['id_recurso']."'>[".$row['asignatura']."] ".$row['nombre']."</option>";
													}
													?>
                                                </select>
                                                <?php
												if($error == "SI" && $tipo == "form_modificar_recurso"){
													echo '<table style="width:80%;" id="tbl_form_modificar_recurso">';
												}else{
													echo '<table style="width:80%; display:none;" id="tbl_form_modificar_recurso">';
												}
												?>
                                                	<tr>
                                                        <td>
                                                            <label for="nombre_recurso_mod">Nombre: </label>
                                                            <input type="text" id="nombre_recurso_mod" name="nombre_mod" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_modificar_recurso") ? $nombre_mod : ''; ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="descripcion_mod">Descripción: </label>
                                                            <textarea id="descripcion_mod" name="descripcion_mod"><?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_modificar_recurso") ? $descripcion_mod : ''; ?></textarea>
                                                        </td>                     
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="asignatura_mod">Asignatura: </label>
                                                            <input type="text" id="asignatura_mod" name="asignatura_mod" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_modificar_recurso") ? $asignatura_mod : ''; ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="localizacion_recurso_mod">Despacho: </label>
                                                            <input type="text" id="localizacion_recurso_mod" name="localizacion_recurso_mod" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_modificar_recurso") ? $localizacion_recurso_mod : ''; ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="fecha_mod">Fecha: </label>
                                                            <input type="date" id="fecha_mod" name="fecha_mod" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_modificar_recurso") ? $fecha_mod : ''; ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr>   
                                                        <td>
                                                            <label for="hora_inicio_mod">Hora inicio: </label>
                                                            <input type="text" id="hora_inicio_mod" name="hora_inicio_mod" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_modificar_recurso") ? $hora_inicio_mod : ''; ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr>    
                                                        <td>
                                                            <label for="hora_fin_mod">Hora fin: </label>
                                                            <input type="text" id="hora_fin_mod" name="hora_fin_mod" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_modificar_recurso") ? $hora_fin_mod : ''; ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr>    
                                                        <td>
                                                            <label for="id_profesional_mod">Profesor: </label>
                                                            <select id="id_profesional_mod" name="id_profesional_mod">
                                                            	<option selected='selected' value=''></option>
																<?php 
                                                                $link = iconectar();
                                                                $sql = "SELECT * FROM profesionales WHERE cod_profesional='prf'";
                                                                $rs = $link->query($sql);
                                                                if(!$rs){
                                                                    die("error database");
                                                                }
                                                                while($row = $rs->fetch_assoc()){
																	if(isset($_POST['tipo_formulario']) && $tipo=="form_modificar_recurso" && $row['id_profesional']==$id_profesional_mod){
																		echo "<option value='".$row['id_profesional']."' selected='selected'>".$row['nombre']." ".$row['primer_apellido']." ".$row['segundo_apellido']."</option>";
																	}else{
                                                                    	echo "<option value='".$row['id_profesional']."'>".$row['nombre']." ".$row['primer_apellido']." ".$row['segundo_apellido']."</option>";
																	}
                                                                }
																
                                                                ?>
																
                                                                </select>
                                       					</td>          
                                                    </tr>
                                                    <tr>    
                                                        <td>
                                                            <label for="departamento_mod_recurso">Departamento: </label>
                                                            <input type="text" id="departamento_mod_recurso" name="departamento_mod_recurso" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_modificar_recurso") ? $departamento_mod_recurso : ''; ?>" />
                                                        </td>
                                                    </tr>
                                                    </tr>
                                                        <td>
                                                            <label for="mensaje_mod">Mensaje: </label>
                                                            <input type="text" id="mensaje_mod" name="mensaje_mod" value="<?php echo (isset($_POST['tipo_formulario']) && $tipo=="form_modificar_usuario") ? $mensaje_mod : ''; ?>">
                                                        </td>          
                                                    </tr>                                                    
                                                    <tr>
                                                        <td class="txt-center">
                                                        	<input type="hidden" id="tipo_formulario" name="tipo_formulario" value="form_modificar_recurso" />
                                                            <input type="hidden" id="id_recurso_modificar" name="id_recurso_modificar" value="" />
                                                            <input type="submit" id="enviar_modificar_recurso" name="enviar" value="Enviar" class="btn-enviar" />
                                                        </td>
                                                    </tr>
                                                </table>
                                            </form >
                                        </div>
                                        <div id="consultar_recurso" style="display:none">
                                        	<p><strong>Consultar recurso</strong></p>
                                            <select id="slt_lista_recurso_cons">
                                                    <option value="">Selecciona el recurso a consultar</option>
                                                    <?php 
                                                    $link = iconectar();
                                                    $sql = "SELECT * FROM recursos";
                                                    $rs = $link->query($sql);
                                                    if(!$rs){
                                                        die("error database");
                                                    }
                                                    while($row = $rs->fetch_assoc()){
                                                        echo "<option value='".$row['id_recurso']."'>[".$row['asignatura']."] ".$row['nombre']."</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <div id="box_info_consultar_recurso"></div>
                                       </div>
                                    
                                    <br /><br />
                                    <span>BIENVENIDO AL PANEL DE ADMINISTRACIÓN</span>
                                    <?php
                                        if (isset($_REQUEST["action"])){
                                            if($_REQUEST["action"]=="XXX")
                                                echo "  Hay variable";
                                            else
                                                echo "No hay variable";
                                        }

                                    ?>
                                    <br /><br />
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
                        <a class="cmswebmap" href="#">Ver Mapa Web</a>
                        <span class="separador_enlaces"> | </span>
                        <a class="contactar" href="#">Contacto y Localización</a>
                        <span class="separador_enlaces"> | </span>
                        <a class="validador" href="#">Accesibilidad</a>
                        <span class="separador_enlaces"> | </span>
                        <a class="privacidad" href="#">Política de Privacidad</a>
                        <p>
                            <a href="#">&copy; 2016</a> <span class="separador_enlaces"> | </span> <a href="#">Universidad de Granada</a></p>
                    </div>
                </div>
            </div>
        </div>
        </body>
        </html>
<?php }
else {
    echo "<script>alert('Inicie sesion para acceder a este contenido.')</script>";
    redirigir();
}
?>