
/***************************************************************************************************
 *Autores: Rodríguez López Alejandro & Antonio Cordonie Campos.
 *Fichero: libreria.php
 *Contenido: Libreria que hemos ido desarrollando con las funciones diseñadas durante el proyecto.
 * *************************************************************************************************/

/*****************************************
 *       Conexiones AJAX                    *
 *****************************************/


/***************Index********************/
//Llama a mostrar recursos.
function refresca_recursos_ajax(str) {
    document.getElementById("lateral_derecho").innerHTML = '';
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET","lincado.php?v="+str+"&f="+1,true);
    xmlhttp.send();
}

//Llama a listar recurso.
function detalle_recurso_ajax(str){
    document.getElementById("lateral_derecho").innerHTML = '';
    if (str == '') {
        alert('No hay ningún recurso seleccionado para efectuar la consulta');
    } else {
        boton_listar(1);
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET","lincado.php?v="+str+"&f="+2,true);
        xmlhttp.send();
    }
}

function boton_listar(accion){
    if(accion) {
        document.getElementById('boton_volver').style.display = '';
        document.getElementById('boton_listar').style.display = 'none';
    }
    else{
        document.getElementById('boton_volver').style.display = 'none';
        document.getElementById('boton_listar').style.display = '';
    }
}

function generar_formulario_localizador_ajax(str,fun){
    if (str == '') {
        alert('No hay ningún recurso seleccionado para efectuar la consulta');
    }
    else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("lateral_derecho").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET", "lincado.php?v=" + fun + "&f="+5, true);
        xmlhttp.send();
    }

}

function generar_formulario_inscripcion_ajax(str){
    if (str == '') {
        alert('No hay ningún recurso seleccionado para efectuar la consulta');
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("lateral_derecho").innerHTML = xmlhttp.responseText;
            }
        };

        xmlhttp.open("GET","lincado.php?v="+str+"&f="+3,true);
        xmlhttp.send();
    }

}


function validar_formulario_inscripcion(str){
    var datos_formulario = [];
    var expresiones_regulares = [];
    var tamaños_maximos = [];
    var tamaños_minimos = [];
    var campos = [];
    var correcto = true;

    datos_formulario[0] = document.getElementsByName("nombre")[0].value;
    datos_formulario[1] = document.getElementsByName("primer_apellido")[0].value;
    datos_formulario[2] = document.getElementsByName("segundo_apellido")[0].value;
    datos_formulario[3] = document.getElementsByName("dni")[0].value;

    campos[0] = 'Nombre';
    campos[1] = 'Primer apellido';
    campos[2] = 'Segundo apellido';
    campos[3] = 'Dni';

    tamaños_maximos[0] = 20;
    tamaños_maximos[1] = 40;
    tamaños_maximos[2] = 40;
    tamaños_maximos[3] = 9;


    tamaños_minimos[0]= 3;
    tamaños_minimos[1]= 3;
    tamaños_minimos[2]= 3;
    tamaños_minimos[3]= 9;


    expresiones_regulares[0] = /[a-zA-ZñÑ]/;
    expresiones_regulares[1] = /[a-zA-ZñÑ]/;
    expresiones_regulares[2] = /[a-zA-ZñÑ]/;
    expresiones_regulares[3] = /^((\d{8}([A-Z]|[a-z])))$/;




    for (var i = 0; i < 4; i++) {

        if (datos_formulario[i] == '') {
            correcto = false;
            alert("El campo " + campos[i] + " esta vacío");
        }
        else {
            if (!expresiones_regulares[i].test(datos_formulario[i])) {
                alert("El valor " + datos_formulario[i] + " del campo " + campos[i] + " no cumple con los requisitos.");
                correcto = false;
            } else {
                if (datos_formulario[i].length < tamaños_minimos[i]) {
                    correcto = false;
                    alert("El valor " + datos_formulario[i] + " del campo " + campos[i] + " es demasiado corto.");
                }
                else {
                    if (datos_formulario[i].length > tamaños_maximos[i]) {
                        correcto = false;
                        alert("El valor " + datos_formulario[i] + " del campo " + campos[i] + " es demasiado largo.");
                    }

                }

            }
        }
    }
    if(correcto)
        get_formulario_usuario_ajax(str);
}


function validar_formulario_localizador(id_recurso, fun ){
    var datos_formulario;
    var expresiones_regulares;
    var tamaño;
    var correcto = true;

    datos_formulario = document.getElementsByName("localizador")[0].value;


    tamaño = 9;
    expresiones_regulares = /^([a-z]{6})([0-9]{2})([a-z])$/;

    if (datos_formulario == '' ) {
        correcto = false;
        alert("El campo LOCALIZADOR esta vacío");
    }
        /*
    else{

        if(!expresiones_regulares.test(datos_formulario)) {
            alert("El valor " + datos_formulario + "  no cumple con los requisitos.");
            correcto = false;
        }
        else{
            if(datos_formulario.length != tamaño) {
                correcto = false;
                alert("El valor " + datos_formulario + " tiene un tamaño incorrecto, se requieren "+tamaño+" caractéres .");
            }
        }
    }
*/
    if(correcto)
        get_formulario_localizador_ajax(id_recurso, fun);

}



function get_formulario_localizador_ajax(str, id){

    var datos_formulario = [];

    datos_formulario[0] = document.getElementsByName("localizador")[0].value;
    datos_formulario[1] = str;

    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var mensaje = xmlhttp.responseText;
            alert(mensaje);
        }
    };

    if (id == 1){
        xmlhttp.open("GET", "lincado.php?v=" + datos_formulario + "&f=" + 6, true);
        xmlhttp.send();
    }
    else {
        var mensaje = "Está a punto de abandonar la cola del recurso " + str + " ¿Esta seguro?";
        if(confirm(mensaje)){
            xmlhttp.open("GET", "lincado.php?v=" + datos_formulario + "&f=" + 7, true);
            xmlhttp.send();
        }
        else
            alert('Operación cancelada por el usuario');
    }


}


//Recupera los datos de incripcion en recurso
function get_formulario_usuario_ajax(str){
    var datos_formulario = [];

    datos_formulario[0]=document.getElementsByName("nombre")[0].value;
    datos_formulario[1]=document.getElementsByName("primer_apellido")[0].value;
    datos_formulario[2]=document.getElementsByName("segundo_apellido")[0].value;
    datos_formulario[3]=document.getElementsByName("dni")[0].value;
    datos_formulario[4]=str;

    var mensaje = "Está a punto de crear un nuevo usuario, ¿Esta seguro?";
    var confirmacion = confirm(mensaje);

    if(confirmacion) {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("inscripcion").reset();
                var mensaje = xmlhttp.responseText;
                alert(mensaje);
            }
        };

        xmlhttp.open("GET", "lincado.php?v="+datos_formulario+"&f="+4, true);
        xmlhttp.send();
    }
    else
        alert('Operación cancelada por el usuario.');
}



/******************Gestion de colas *********************/
function generar_gestion_cola_ajax(id_propietario){
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            //var t=setInterval(setInterval(function(){ generar_gestion_cola_ajax(id_propietario)}, 5000 ));
            document.getElementById("gestion_cola").innerHTML = xmlhttp.responseText;
            // if(xmlhttp.responseText !='<br> No hay usuarios en espera.'){
            // clearInterval(t);
            //}
        }
    };
    xmlhttp.open("GET", "lincado.php?v=" + id_propietario + "&f=" + 12, true);
    xmlhttp.send();

}


function cerrar_atencion_ajax(id_usuario, id_propietario){
    var datos = [];

    datos[0] = id_usuario;
    datos[1] = id_propietario;
    
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            alert(xmlhttp.responseText);
            generar_gestion_cola_ajax(id_propietario);
        }
    };
    xmlhttp.open("GET","lincado.php?v="+datos+"&f="+13,true);
    xmlhttp.send();
    
}

function poner_espera_ajax(id_recurso, estado, id_usuario, id_propietario){
    var datos = [];
    datos[0] = id_recurso;
    datos[1] = estado;
    datos[2] = id_usuario;
    
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            alert(xmlhttp.responseText);
            generar_gestion_cola_ajax(id_propietario);
        }
    };
    xmlhttp.open("GET","lincado.php?v="+datos+"&f="+14,true);
    xmlhttp.send();

}

function ver_cola_recurso_activo_ajax(id_propietario){
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("capa_consultar_cola").innerHTML = xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET", "lincado.php?v=" + id_propietario + "&f="+15, true);
    xmlhttp.send();

}


function ver_cola_recurso_ajax(id_recurso){
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("ver_cola_recurso").innerHTML = xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET", "lincado.php?v=" + id_recurso + "&f="+16, true);
    xmlhttp.send();

}

/*************Visualizacion*************/
function visualizacion_ajax(){

    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('turnos').innerHTML = xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET","lincado.php?f="+10,true);
    xmlhttp.send();
}

function get_campo_aviso(){

    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp_aviso = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp_aviso = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp_aviso.onreadystatechange = function() {
        if (xmlhttp_aviso.readyState == 4 && xmlhttp_aviso.status == 200) {
            document.getElementById('avisos').innerHTML = xmlhttp_aviso.responseText;
        }
    };
    xmlhttp_aviso.open("GET","lincado.php?f="+9,true);
    xmlhttp_aviso.send();
}

function reloj() {
        var h = new Date();
        var f = new Date();
        var hora = h.toLocaleTimeString();
        var fecha = f.toLocaleDateString();
        document.getElementById("reloj").innerHTML = fecha+' '+hora;
}

function get_aviso(){
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp_aviso2 = new XMLHttpRequest();
    }
    else {
        // code for IE6, IE5
        xmlhttp_aviso2 = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp_aviso2.onreadystatechange = function() {
        if (xmlhttp_aviso2.readyState == 4 && xmlhttp_aviso2.status == 200){
            var wind_string = xmlhttp_aviso2.responseText;
            
            //if(wind_string = '')
               //wind_string = 'Bienvenido al panel de gestion de citas No hay avisos ';

            document.getElementById('wind').innerHTML = wind_string;
        }

    };
    xmlhttp_aviso2.open("GET", "lincado.php?f="+11, true);
    xmlhttp_aviso2.send();
}


function refresco_ajax(){
    reloj();
    visualizacion_ajax();
    get_campo_aviso();
    get_aviso();

}

