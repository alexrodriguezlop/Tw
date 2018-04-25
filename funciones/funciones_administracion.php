<?php

require_once 'funciones.php';

session_start();

if ( $_REQUEST['tab'] == 'info_usuario_modificar'){
	$id_profesional = $_REQUEST['id_profesional'];
	$link = iconectar();
	$sql = "SELECT * FROM profesionales WHERE id_profesional='".$id_profesional."';";
	$rs = $link->query($sql);
	if(!$rs){
		die("error database");
	}
	if($rs->num_rows > 0){
		$row = $rs->fetch_assoc();
		$nombre = $row['nombre'];
		$primer_apellido = $row['primer_apellido'];
		$segundo_apellido = $row['segundo_apellido'];
		$dni = $row['dni'];
		$email = $row['email'];
		$login = $row['login'];
		$perfil = $row['cod_profesional'];
		$departamento = $row['departamento'];
		$id_profesional = $row['id_profesional'];
		echo json_encode(array("status"=>"true", "id_profesional"=>$id_profesional, "nombre"=>$nombre, "primer_apellido"=>$primer_apellido, "segundo_apellido"=>$segundo_apellido, "dni"=>$dni, "email"=>$email, "login"=>$login, "perfil"=>$perfil, "departamento"=>$departamento));
	}else{
		echo json_encode(array("status"=>"false", "contenido"=>"No existe usuario"));
	}
}

if ( $_REQUEST['tab'] == 'info_usuario_consultar'){
	$id_profesional = $_REQUEST['id_profesional'];
	$link = iconectar();
	$sql = "SELECT * FROM profesionales WHERE id_profesional='".$id_profesional."';";
	$rs = $link->query($sql);
	if(!$rs){
		die("error database");
	}
	if($rs->num_rows > 0){
		$row = $rs->fetch_assoc();
		$contenido = '';
		$contenido .= '<ul>';
		$contenido .= '<li>Nombre: <strong>'.htmlspecialchars($row['nombre']).'</strong></li>';
		$contenido .= '<li>Primer apellido: <strong>'.htmlspecialchars($row['primer_apellido']).'</strong></li>';
		$contenido .= '<li>Segundo apellido: <strong>'.htmlspecialchars($row['segundo_apellido']).'</strong></li>';
		$contenido .= '<li>DNI: <strong>'.htmlspecialchars($row['dni']).'</strong></li>';
		$contenido .= '<li>E-Mail: <strong>'.htmlspecialchars($row['email']).'</strong></li>';
		$contenido .= '<li>Login: <strong>'.htmlspecialchars($row['login']).'</strong></li>';
		$contenido .= '<li>Perfil: <strong>'.(($row['cod_profesional'] == 'adm') ? 'Administrador' : 'Profesor').'</strong></li>';
		$contenido .= '<li>Departamento: <strong>'.htmlspecialchars($row['departamento']).'</strong></li>';		
		$contenido .= '</ul>';
		echo json_encode(array("status"=>"true", "contenido"=>$contenido));
	}else{
		echo json_encode(array("status"=>"false", "contenido"=>"No existe usuario"));
	}
}

if ( $_REQUEST['tab'] == 'info_recurso_modificar'){
	$id_recurso = $_REQUEST['id_recurso'];
	$link = iconectar();
	$sql = "SELECT * FROM recursos WHERE id_recurso='".$id_recurso."';";
	$rs = $link->query($sql);
	if(!$rs){
		die("error database");
	}
	if($rs->num_rows > 0){
		$row = $rs->fetch_assoc();
		$nombre = $row['nombre'];
		$descripcion = $row['descripcion'];
		$asignatura = $row['asignatura'];
		$localizacion_recurso = $row['localizacion_recurso'];
		$fecha = $row['fecha'];
		$hora_inicio = $row['hora_inicio'];
		$hora_fin = $row['hora_fin'];
		$departamento = $row['departamento'];
		$id_profesional = $row['id_profesional'];
		$mensaje = $row['mensaje'];
		$id_recurso = $row['id_recurso'];
		echo json_encode(array("status"=>"true", "id_recurso"=>$id_recurso, "nombre"=>$nombre, "descripcion"=>$descripcion, "asignatura"=>$asignatura, "localizacion_recurso"=>$localizacion_recurso, "fecha"=>$fecha, "hora_inicio"=>$hora_inicio, "hora_fin"=>$hora_fin, "departamento"=>$departamento, "id_profesional"=>$id_profesional, "mensaje"=>$mensaje));
	}else{
		echo json_encode(array("status"=>"false", "contenido"=>"No existe recurso"));
	}
}

if ( $_REQUEST['tab'] == 'info_recurso_consultar'){
	$id_recurso = $_REQUEST['id_recurso'];
	$link = iconectar();
	$sql = "SELECT * FROM recursos WHERE id_recurso='".$id_recurso."';";
	$rs = $link->query($sql);
	if(!$rs){
		die("error database");
	}
	if($rs->num_rows > 0){
		$row = $rs->fetch_assoc();
		$contenido = '';
		$contenido .= '<ul>';
		$contenido .= '<li>ID recurso: <strong>'.$row['id_recurso'].'</strong></li>';
		$contenido .= '<li>Nombre: <strong>'.htmlspecialchars($row['nombre']).'</strong></li>';
		$contenido .= '<li>Descripción: <strong>'.htmlspecialchars($row['descripcion']).'</strong></li>';
		$contenido .= '<li>Asignatura: <strong>'.htmlspecialchars($row['asignatura']).'</strong></li>';
		$contenido .= '<li>Despacho: <strong>'.htmlspecialchars($row['localizacion_recurso']).'</strong></li>';
		$contenido .= '<li>Fecha: <strong>'.htmlspecialchars($row['fecha']).'</strong></li>';
		$contenido .= '<li>Hora inicio: <strong>'.htmlspecialchars($row['hora_inicio']).'</strong></li>';
		$contenido .= '<li>Hora fin: <strong>'.htmlspecialchars($row['hora_fin']).'</strong></li>';
		$contenido .= '<li>Departamento: <strong>'.htmlspecialchars($row['departamento']).'</strong></li>';
		$sql = "SELECT * FROM profesionales WHERE id_profesional='".$row['id_profesional']."'";
		$rs_aux = $link->query($sql);
		$aux = $rs_aux->fetch_assoc();
		$contenido .= '<li>Profesor: <strong>'.htmlspecialchars($aux['nombre'].' '.$aux['primer_apellido'].' '.$aux['segundo_apellido']).'</strong></li>';
		$contenido .= '<li>Mensaje: <strong>'.htmlspecialchars($row['mensaje']).'</strong></li>';
		$id_recurso = $row['id_recurso'];
		$contenido .= '<input value="Desplegar cola usuarios" class="btn-enviar" type="button" onclick = "ver_cola_recurso_ajax(`'.$id_recurso.'`)"/>';
		$contenido .= '</ul>';
		$contenido .= '<div id="ver_cola_recurso"></div>';

		echo json_encode(array("status"=>"true", "contenido"=>$contenido));
	}else{
		echo json_encode(array("status"=>"false", "contenido"=>"No existe recurso"));
	}
}

?>