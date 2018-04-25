$(document).ready(function () {
	
	$("#ver_portada_adm_usuarios").click(function(e){
		e.preventDefault();
		e.stopPropagation();
		$("#portada_administracion").show();
		$("#alta_usuario").hide();
		$("#baja_usuario").hide();
		$("#modificar_usuario").hide();
		$("#consultar_usuario").hide();
		$("#mensaje_error").hide();
		$("#mensaje_resultado").hide();
		$("#alta_recurso").hide();
		$("#baja_recurso").hide();
		$("#modificar_recurso").hide();
		$("#consultar_recurso").hide();
		$("#gestion_cola").hide();
		$("#modificar_datos_personales").hide();
		$("#capa_consultar_cola").hide();		
		
	});
	$("#ver_portada_datos_recursos").click(function(e){
		e.preventDefault();
		e.stopPropagation();
		$("#portada_administracion").show();
		$("#alta_usuario").hide();
		$("#baja_usuario").hide();
		$("#modificar_usuario").hide();
		$("#consultar_usuario").hide();
		$("#mensaje_error").hide();
		$("#mensaje_resultado").hide();
		$("#alta_recurso").hide();
		$("#baja_recurso").hide();
		$("#modificar_recurso").hide();
		$("#consultar_recurso").hide();
		$("#modificar_datos_personales").hide();
		$("#gestion_cola").hide();
		$("#capa_consultar_cola").hide();		
		
	});

	$("#ver_portada_adm_recursos").click(function(e){
		e.preventDefault();
		e.stopPropagation();
		$("#portada_administracion").show();
		$("#alta_usuario").hide();
		$("#baja_usuario").hide();
		$("#modificar_usuario").hide();
		$("#consultar_usuario").hide();
		$("#mensaje_error").hide();
		$("#mensaje_resultado").hide();
		$("#alta_recurso").hide();
		$("#baja_recurso").hide();
		$("#modificar_recurso").hide();
		$("#consultar_recurso").hide();
		$("#modificar_datos_personales").hide();
		$("#gestion_cola").hide();
		$("#capa_consultar_cola").hide();		
	});	

	$("#ver_portada_datos").click(function(e){
		e.preventDefault();
		e.stopPropagation();
		$("#portada_administracion").show();
		$("#alta_usuario").hide();
		$("#baja_usuario").hide();
		$("#modificar_usuario").hide();
		$("#consultar_usuario").hide();
		$("#mensaje_error").hide();
		$("#mensaje_resultado").hide();
		$("#alta_recurso").hide();
		$("#baja_recurso").hide();
		$("#modificar_recurso").hide();
		$("#consultar_recurso").hide();
		$("#modificar_datos_personales").hide();
		$("#gestion_cola").hide();		
		$("#capa_consultar_cola").hide();		
	});			
	
	/* Eventos de usuarios */
	$("#ver_form_alta_usuario").click(function(e){
		e.preventDefault();
		e.stopPropagation();
		$("#portada_administracion").hide();
		$("#alta_usuario").show();
		$("#baja_usuario").hide();
		$("#modificar_usuario").hide();
		$("#consultar_usuario").hide();
		$("#mensaje_error").hide();
		$("#mensaje_resultado").hide();
		$("#alta_recurso").hide();
		$("#baja_recurso").hide();
		$("#modificar_recurso").hide();
		$("#consultar_recurso").hide();
		$("#gestion_cola").hide();
		$("#capa_consultar_cola").hide();		
	});
	
	$("#ver_form_baja_usuario").click(function(e){
		e.preventDefault();
		e.stopPropagation();
		$("#portada_administracion").hide();
		$("#alta_usuario").hide();
		$("#baja_usuario").show();
		$("#modificar_usuario").hide();
		$("#consultar_usuario").hide();
		$("#mensaje_error").hide();
		$("#mensaje_resultado").hide();
		$("#alta_recurso").hide();
		$("#baja_recurso").hide();
		$("#modificar_recurso").hide();
		$("#consultar_recurso").hide();
		$("#gestion_cola").hide();
		$("#capa_consultar_cola").hide();		
	});
	
	$("#ver_form_modi_usuario").click(function(e){
		e.preventDefault();
		e.stopPropagation();
		$("#portada_administracion").hide();
		$("#alta_usuario").hide();
		$("#baja_usuario").hide();
		$("#modificar_usuario").show();
		$("#consultar_usuario").hide();
		$("#mensaje_error").hide();
		$("#mensaje_resultado").hide();
		$("#alta_recurso").hide();
		$("#baja_recurso").hide();
		$("#modificar_recurso").hide();
		$("#consultar_recurso").hide();
		$("#gestion_cola").hide();
		$("#capa_consultar_cola").hide();		
	});
	
	$("#ver_form_cons_usuario").click(function(e){
		e.preventDefault();
		e.stopPropagation();
		$("#portada_administracion").hide();
		$("#alta_usuario").hide();
		$("#baja_usuario").hide();
		$("#modificar_usuario").hide();
		$("#consultar_usuario").show();
		$("#mensaje_error").hide();
		$("#mensaje_resultado").hide();
		$("#alta_recurso").hide();
		$("#baja_recurso").hide();
		$("#modificar_recurso").hide();
		$("#consultar_recurso").hide();
		$("#gestion_cola").hide();
		$("#capa_consultar_cola").hide();		
	});

		$("#slt_lista_usuario_baja").change(function(){
		var id = $(this).val();
		if(id == ""){
			$("#form_baja_usuario").reset();
			$("#tbl_form_baja").hide();	
		}else{
			$.post("funciones/funciones_administracion.php",{tab:"info_usuario_modificar", id_profesional:id},
		   function(data){
				if(data.status == 'false'){
					alert(data.contenido);
				}else{
					$("#nombre_baja").val(data.nombre);
					$("#primer_apellido_baja").val(data.primer_apellido);
					$("#segundo_apellido_baja").val(data.segundo_apellido);
					$("#dni_baja").val(data.dni);
					$("#email_baja").val(data.email);
					$("#login_baja").val(data.login); 
					$("#codigo_baja option[value="+data.perfil+"]").attr('selected','selected');  
					$("#departamento_baja").val(data.departamento);
					$("#id_profesional").val(data.id_profesional);  
					$("#tbl_form_baja").show();
				}
		   },"json");
		}
	});
	
	$("#slt_lista_usuario_mod").change(function(){
		var id = $(this).val();
		if(id == ""){
			$("#form_modificar_usuario").reset();
			$("#tbl_form_modificar").hide();	
		}else{
			$.post("funciones/funciones_administracion.php",{tab:"info_usuario_modificar", id_profesional:id},
		   function(data){
				if(data.status == 'false'){
					alert(data.contenido);
				}else{
					$("#nombre_mod").val(data.nombre);
					$("#primer_apellido_mod").val(data.primer_apellido);
					$("#segundo_apellido_mod").val(data.segundo_apellido);
					$("#dni_mod").val(data.dni);
					$("#email_mod").val(data.email);
					$("#login_mod").val(data.login); 
					$("#codigo_mod option[value="+data.perfil+"]").attr('selected','selected');  
					$("#departamento_mod").val(data.departamento);
					$("#id_profesional_modificar").val(data.id_profesional);  
					$("#tbl_form_modificar").show();
				}
		   },"json");
		}
	});
	
	$("#slt_lista_usuario_cons").change(function(){
		var id = $(this).val();
		if(id == ""){
			$("#box_info_consultar").html('');	
		}else{
			$.post("funciones/funciones_administracion.php",{tab:"info_usuario_consultar", id_profesional:id},
		   function(data){
				if(data.status == 'false'){
					alert(data.contenido);
				}else{
					$("#box_info_consultar").html(data.contenido);	
				}
		   },"json");
		}
	});
/* Eventos para confirmar Baja usuario */
/*var currentForm;
$("#confirm").dialog({
    autoOpen: false,
    title: "First we must ask you!",
    resizable: false,
    width:500,
    height:190,
    modal: true,
    buttons: {
        'Submit for God sake!': function() {
            currentForm.submit();
        },
        Cancel: function() {
            $(this).dialog('close');
        }
    }       
});
$('#enviar_baja_usuario').on('click', function(e){
        currentForm = $(this).closest("form");
        $('#confirm').dialog('open');
        return false;
    });
*/
	/* Fin eventos de usuarios */
	
	/* Eventos de recursos */
	$("#ver_form_alta_recurso").click(function(e){
		e.preventDefault();
		e.stopPropagation();
		$("#portada_administracion").hide();
		$("#alta_usuario").hide();
		$("#baja_usuario").hide();
		$("#modificar_usuario").hide();
		$("#consultar_usuario").hide();
		$("#mensaje_error").hide();
		$("#mensaje_resultado").hide();
		$("#alta_recurso").show();
		$("#baja_recurso").hide();
		$("#modificar_recurso").hide();
		$("#consultar_recurso").hide();
		$("#modificar_datos_personales").hide();
		$("#gestion_cola").hide();
		$("#capa_consultar_cola").hide();		
	});
	
	$("#ver_form_baja_recurso").click(function(e){
		e.preventDefault();
		e.stopPropagation();
		$("#portada_administracion").hide();
		$("#alta_usuario").hide();
		$("#baja_usuario").hide();
		$("#modificar_usuario").hide();
		$("#consultar_usuario").hide();
		$("#mensaje_error").hide();
		$("#mensaje_resultado").hide();
		$("#alta_recurso").hide();
		$("#baja_recurso").show();
		$("#modificar_recurso").hide();
		$("#consultar_recurso").hide();
		$("#modificar_datos_personales").hide();
		$("#gestion_cola").hide();
		$("#capa_consultar_cola").hide();		
	});
	
	$("#ver_form_modi_recurso").click(function(e){
		e.preventDefault();
		e.stopPropagation();
		$("#portada_administracion").hide();
		$("#alta_usuario").hide();
		$("#baja_usuario").hide();
		$("#modificar_usuario").hide();
		$("#consultar_usuario").hide();
		$("#mensaje_error").hide();
		$("#mensaje_resultado").hide();
		$("#alta_recurso").hide();
		$("#baja_recurso").hide();
		$("#modificar_recurso").show();
		$("#consultar_recurso").hide();
		$("#modificar_datos_personales").hide();
		$("#gestion_cola").hide();
		$("#capa_consultar_cola").hide();		
	});
	    
	$("#ver_form_cons_recurso").click(function(e){
		e.preventDefault();
		e.stopPropagation();
		$("#portada_administracion").hide();
		$("#alta_usuario").hide();
		$("#baja_usuario").hide();
		$("#modificar_usuario").hide();
		$("#consultar_usuario").hide();
		$("#mensaje_error").hide();
		$("#mensaje_resultado").hide();
		$("#alta_recurso").hide();
		$("#baja_recurso").hide();
		$("#modificar_recurso").hide();
		$("#consultar_recurso").show();
		$("#modificar_datos_personales").hide();
		$("#gestion_cola").hide();
		$("#capa_consultar_cola").hide();		
	});

	
	
	$("#slt_lista_recurso_baja").change(function(){
		var id = $(this).val();
		if(id == ""){
			$("#form_baja_recurso").reset();
			$("#tbl_form_baja_recurso").hide();	
		}else{
			$.post("funciones/funciones_administracion.php",{tab:"info_recurso_modificar", id_recurso:id},
		   function(data){
				if(data.status == 'false'){
					alert(data.contenido);
				}else{
					$("#nombre_recurso_baja").val(data.nombre);
					$("#descripcion_baja").val(data.descripcion);
					$("#asignatura_baja").val(data.asignatura);
					$("#localizacion_recurso_baja").val(data.localizacion_recurso);
					$("#fecha_baja").val(data.fecha);
					$("#hora_inicio_baja").val(data.hora_inicio); 
					$("#hora_fin_baja").val(data.hora_fin); 
					$("#id_profesional_baja option[value="+data.id_profesional+"]").prop('selected','selected');
					$("#departamento_baja").val(data.departamento); 
					$("#mensaje_baja").val(data.mensaje); 
					$("#id_recurso").val(data.id_recurso);  
					$("#tbl_form_baja_recurso").hide();
				}
		   },"json");
		}
	});

	$("#slt_lista_recurso_mod").change(function(){
		var id = $(this).val();
		//alert(id);
		if(id == ""){
			$("#form_modificar_recurso").reset();
			$("#tbl_form_modificar_recurso").hide();	
		}else{
			$.post("funciones/funciones_administracion.php",{tab:"info_recurso_modificar", id_recurso:id},
		   function(data){

				if(data.status == 'false'){
					alert(data.contenido);
				}else{
					$("#nombre_recurso_mod").val(data.nombre);
					$("#descripcion_mod").val(data.descripcion);
					$("#asignatura_mod").val(data.asignatura);
					$("#localizacion_recurso_mod").val(data.localizacion_recurso);
					$("#fecha_mod").val(data.fecha);
					$("#hora_inicio_mod").val(data.hora_inicio); 
					$("#hora_fin_mod").val(data.hora_fin); 
					$("#id_profesional_mod option[value="+data.id_profesional+"]").prop('selected','selected');
					$("#departamento_mod_recurso").val(data.departamento);
					$("#mensaje_mod").val(data.mensaje); 
					$("#id_recurso_modificar").val(data.id_recurso);  
					$("#tbl_form_modificar_recurso").show();

				}
		   },"json");
		}
	});
	
	$("#slt_lista_recurso_cons").change(function(){
		var id = $(this).val();
		if(id == ""){
			$("#box_info_consultar_recurso").html('');	
		}else{
			$.post("funciones/funciones_administracion.php",{tab:"info_recurso_consultar", id_recurso:id},
		   function(data){
				if(data.status == 'false'){
					alert(data.contenido);
				}else{
					$("#box_info_consultar_recurso").html(data.contenido);	
				}
		   },"json");
		}
	});

	/* Fin eventos recursos */
	
	/* Modificar datos personales */
	$("#ver_form_modi_datos_personales").click(function(e){
		e.preventDefault();
		e.stopPropagation();
		$("#portada_administracion").hide();
		$("#mensaje_error").hide();
		$("#mensaje_resultado").hide();
		$("#alta_recurso").hide();
		$("#baja_recurso").hide();
		$("#modificar_recurso").hide();
		$("#consultar_recurso").hide();
		$("#modificar_datos_personales").show();
		$("#gestion_cola").hide();
		$("#capa_consultar_cola").hide();		
	});

	/*Click para ver recursos*/
	$("#gestion_cola_boton").click(function(e){
		e.preventDefault();
		e.stopPropagation();
		$("#portada_administracion").hide();
		$("#mensaje_error").hide();
		$("#mensaje_resultado").hide();
		$("#alta_recurso").hide();
		$("#baja_recurso").hide();
		$("#modificar_recurso").hide();
		$("#consultar_recurso").hide();
		$("#modificar_datos_personales").hide();
		$("#gestion_cola").show();
		$("#capa_consultar_cola").hide();		
	});
	$("#consultar_cola").click(function(e){
		e.preventDefault();
		e.stopPropagation();
		$("#portada_administracion").hide();
		$("#mensaje_error").hide();
		$("#mensaje_resultado").hide();
		$("#alta_recurso").hide();
		$("#baja_recurso").hide();
		$("#modificar_recurso").hide();
		$("#consultar_recurso").hide();
		$("#modificar_datos_personales").hide();
		$("#gestion_cola").hide();
		$("#capa_consultar_cola").show();
	});
	/* Fin modificar datos personales */

});
