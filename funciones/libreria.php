<?php
    require_once 'funciones.php';
    /***************************************************************************************************
     *Autores: Rodríguez López Alejandro & Antonio Cordonie Campos.
     *Fichero: libreria.php
     *Contenido: Libreria que hemos ido desarrollando con las funciones diseñadas durante el proyecto.
     * *************************************************************************************************/


    /*****************************************
     *              SESION Y LOGUEO          *
     *****************************************/

    //verifica los campos introducidos, sí son correctos crea la sesion;
    //Sí no lo son crea una sesion indicando que hay un error en el inicio de sesion;
    function verificar_login($login, $pass){


        $conexion = conectar_db();
        $consulta = "SELECT * FROM profesionales WHERE login = '$login' AND  pass = md5('$pass')";
        $resultados = mysqli_query($conexion,$consulta)or die("Error consulta");

        cerrar_conexion($conexion);

        $num_resultados = mysqli_num_rows($resultados);

        if ($num_resultados == 1) {
            //$fila = mysqli_fetch_row($resultados);
            $fila = mysqli_fetch_array($resultados);

            //Creamos la sesision;
            $_SESSION = array();
            session_destroy();
            session_start();
            $_SESSION["id"] = $fila["id_profesional"];
            $_SESSION["cod"] = $fila["cod_profesional"];
            $_SESSION["nombre"] = $fila["nombre"];
            $_SESSION["primer_apellido"] = $fila["primer_apellido"];
            $_SESSION["segundo_apellido"] = $fila["segundo_apellido"];
            $_SESSION["dni"] = $fila["dni"];

        }
        else
            $_SESSION["mensaje"] = "Usuario o contraseña incorrectos.".$_SESSION["camino"];
    }


    //Sí la sesion es valida, refresca el tiempo y devuleve true,
    //Sí no lo es devuelve false.
    function verificar_sesion(){
        //Sí la sesion tiene ID es correcta, la regeneramos.
        if($_SESSION[id]) {
            session_regenerate_id(true);
            return true;
        }
        else
            return false;
    }

    //Redirige la SESION a su fichero correspondiente.
    function redirigir(){
        $cod = $_SESSION["cod"];
        if($cod == "adm")
            header("location: administracion.php");
        elseif($cod == "prf")
            header("location: gestion.php");
        else
            header("location: index.php");
    }


    //cierra la sesion
    function logout(){
        session_start();
        $_SESSION = array();
        session_regenerate_id(TRUE);
        session_destroy();
    }




    /********************************************************************************
     *              Conexiones, consultas e inserciones en la base de datos         *
     *******************************************************************************/

    //Conecta con la base de datos
    //Los parametros de conexion son constantes definidas en la funcion.
    function conectar_db()
    {
        //Parametros de acceso a la base de datos:
        if (!defined('servidor')) define("servidor", "localhost");
        if (!defined('usuario')) define("usuario", "ejercicio_pw");
        if (!defined('contraseña')) define("contraseña", "pass_ejercicio_pw");
        if (!defined('db')) define("db", "75162276r");

        /*
        //Datos del servidor local
        define("servidor", "localhost");
        define("usuario", "root");
        define("contraseña", "");
        define("db", "twdb");
        */

        //Datos servidor remoto
        /*
        define("servidor", "localhost");
        define("usuario", "ejercicio_pw");
        define("contraseña", "pass_ejercicio_pw");
        define("db", "75162276r");
        */

        //Conexion a la base de datos
        $conexion = mysqli_connect(servidor, usuario, contraseña);

        if (!$conexion) {
            die("La conexion a la base de datos ha fallado: " . mysqli_connect_error());
        }

        //Correccion de acentos
        $acentos = "SET NAMES 'utf8'";
        mysqli_query($conexion, $acentos);
        
        
        //Seleccion de la base de datos
        $sql = "USE"." ".db;
        if (!mysqli_query($conexion, $sql))
            echo "Error seleccionando la base de datos, error: " . mysqli_error($conexion);


        return $conexion;
    }

    //Conecta a la BD, Realiza la consulta y cierra la conexión
    function get_consulta($consulta){
        $conexion = conectar_db();
        $resultados = mysqli_query($conexion, $consulta)or die(mysqli_error($conexion));
        cerrar_conexion($conexion);

        return $resultados;
    }

    function get_num_filas($resultado){
        $num_filas = mysqli_num_rows($resultado);

        return $num_filas;
    }

    function get_num_columnas($resultado){
        $num_columnas = mysqli_num_fields($resultado);

        return $num_columnas;
    }

    function get_fila($resultado){
        if(get_num_filas($resultado)>0)
            $fila = mysqli_fetch_array($resultado);
        else
            $fila=false;

        return $fila;

    }


    function get_siguiente($id_recurso){
    $consulta = "SELECT id_usuario, posicion_cola FROM colas WHERE estado_usuario = '0' AND id_recurso = '$id_recurso' ORDER BY posicion_cola ASC";
    $resultados = get_consulta($consulta);
    $num_filas = get_num_filas($resultados);
    if($num_filas > 0){
        $fila = get_fila($resultados);
        return $fila;
    }
    else
        return false;
}

    function get_datos_cliente($id_cliente){
    $consulta = "SELECT * FROM usuarios WHERE id_usuario = '$id_cliente'";
    $resultados = get_consulta($consulta);
    $num_filas = get_num_filas($resultados);

    if($num_filas > 0) {
        $fila = get_fila($resultados);
        return $fila;
    }
    else
        return false;
}


    //devuelve los datos del usuario correspondiente a login y pass proporcionados
    //si no hay datos devuelve null.
    function get_usuario($login, $pass){
        $consulta = "SELECT * FROM profesionales WHERE login = '$login' AND pass = '$pass'";
        
        $datos_usuario = get_consulta($consulta);

        return $datos_usuario;
    }

    function get_profesional($id_profesional){
        $consulta = "SELECT nombre, primer_apellido, segundo_apellido  FROM profesionales where id_profesional = '$id_profesional'";

        $resultados = get_consulta($consulta);
        $num_filas = get_num_filas($resultados);

        $fila = mysqli_fetch_array($resultados);
        if($num_filas > 0)
            return $fila[0]." ".$fila[1]." ".$fila[2];
        else
            return 0;

    }

    function get_profesional_dni($dni){
    $consulta = "SELECT nombre, primer_apellido, segundo_apellido  FROM profesionales where dni = '$dni'";

    $resultados = get_consulta($consulta);
    $num_filas = get_num_filas($resultados);

    $fila = mysqli_fetch_array($resultados);
    if($num_filas > 0)
        return $fila[0]." ".$fila[1]." ".$fila[2];
    else
        return 0;

}

    function get_turno($id_recurso){
        $consulta="SELECT posicion_cola  FROM colas where id_recurso = '$id_recurso' ORDER BY posicion_cola DESC";
        $resultado = get_consulta($consulta);
        $num_filas = get_num_filas($resultado);

        if($num_filas == 0)
            $turno = 1;
        else {
            $fila = get_fila($resultado);
            $turno = $fila[0] + 1;
        }
        return $turno;
    }

    function get_posicion($id_usuario, $id_recurso){
        //Si no se encuentra el localizador para el recurso

        if(!verificar_localizador($id_usuario, $id_recurso)) {
            //echo "<script>alert('El localizador " . $id_usuario . " no ha sido encontrado para el recurso " . $id_recurso . "');</script>";
            echo "Localizador no encontrado para el recurso";
        }
        else{
            $consulta="SELECT posicion_cola  FROM colas where id_recurso = '$id_recurso' AND id_usuario = '$id_usuario'";
            $resultados = get_consulta($consulta);
            $fila = get_fila($resultados);

            $posicion = $fila[0];

            if(($posicion / 10) < 1){
                if($posicion == 5)
                    $info_posicion = 'Se encuentra entre los 5 siguientes usuarios que serán atendidos';
                elseif ($posicion > 5)
                    $info_posicion = 'Se encuentra entre los 10 siguientes usuarios que serán atendidos';
                else
                    $info_posicion = 'Será atendido en breve';
            }
            elseif (($posicion / 100) < 1)
                    $info_posicion = 'Se encuentra entre los '.variant_int ($posicion/10).'0 siguientes usuarios que serán atendidos';
            
            return $info_posicion;   
        }
        
    }

    function get_despacho($id_recurso){
        $consulta = "SELECT localizacion_recurso FROM recursos WHERE id_recurso = '$id_recurso'";

        $resultados = get_consulta($consulta);
        $fila = get_fila($resultados);

        return $fila[0];

    }

    function get_mensaje($id_recurso){
        $consulta = "SELECT mensaje FROM recursos WHERE id_recurso = '$id_recurso'";

        $resultados = get_consulta($consulta);
        $fila = get_fila($resultados);

        return $fila[0];

    }

    function get_recursos_activos(){
        $consulta ="SELECT id_recurso FROM recursos WHERE DATE(fecha) = DATE(NOW()) AND TIME(hora_inicio) <= TIME(NOW()) AND TIME(hora_fin) > TIME(NOW())";
        $resultados = get_consulta($consulta);

        $num_filas = get_num_filas($resultados);
        if($num_filas > 0)
            return $resultados;
        else
            echo 'No hay recursos activos.';
    }

    function get_recursos_activos_id($id_propietario){
        $consulta ="SELECT id_recurso, nombre FROM recursos WHERE DATE(fecha) = DATE(NOW()) AND TIME(hora_inicio) <= TIME(NOW()) AND TIME(hora_fin) > TIME(NOW()) AND id_profesional = '$id_propietario'";
        $resultados = get_consulta($consulta);

        $num_filas = get_num_filas($resultados);
        if($num_filas > 0){
            $datos=get_fila($resultados);
            return $datos;
        }
        else
            return false;
    }

    function get_datos_recurso($id_recurso){
        $consulta ="SELECT * FROM recursos WHERE id_recurso = '$id_recurso'";
        $resultados = get_consulta($consulta);

        $num_filas = get_num_filas($resultados);
        if($num_filas > 0){
            $fila = get_fila($resultados);
            return $fila;
        }
        else
            return false;
    }

    //Cierra la conexion con la base de datos.
    function cerrar_conexion($conexion){
        //Cerramos la conexion con la BD
        mysqli_close($conexion);
    }





    /*****************************************
     *              Generadores y Cadenas    *
     *****************************************/

    //Quita todos los añadidios a las vocale como tildes, dieresis, etc...
    function normalizar_cadena($cadena) {
        $no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ä","ë","ï","ö","ü","Ä","Ë","Ï","Ö","Ü","ñ","Ñ","À","È","Ì","Ò","Ù","à","è","ì","ò","ù","ç","Ç","â","ê","î","ô","û","Â","Ê","Î","Ô","Û");
        $permitidas= array    ("a","e","i","o","u","A","E","I","O","U","a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U");
        $texto = str_replace($no_permitidas, $permitidas ,$cadena);
        return $texto;
    }

    //Genera un ID para un usuario de tipo USUARIO
    function generador_id($nombre, $primer_apellido, $segundo_apellido, $dni){

        //Genero ID dni
        $dni_generado = substr($dni, -5, 1).substr("$dni", -2);

        //Genero ID nombre y normalizamos
        $nombre_generado = mb_substr("$nombre", -2).mb_substr($segundo_apellido,0, 2).mb_substr($primer_apellido,0, 2);
        $nombre_generado = normalizar_cadena($nombre_generado);


        //Concateno las pastes generadas
        $id = $nombre_generado.$dni_generado;

        //Devuelvo el ID en minúsculas
        return mb_strtolower($id);

    }

    //Genera un ID para un usuario de tipo PROFESIONAL
    function generador_id_profesional($cod, $nombre, $primer_apellido, $segundo_apellido, $dni){

        //Genero un Id standar y concateno con el COD tipo PROFESIONAL
        $id = generador_id($nombre, $primer_apellido, $segundo_apellido, $dni);

        return mb_strtolower ($cod.$id);
    }

    //Genera un ID para un RECURSO.
    function generador_id_recurso($propietario, $dpto, $localizacion, $dia, $hora){

        $nombre=str_replace(' ', '', $propietario);
        $dpto=str_replace(' ', '', $dpto);
        $localizacion=str_replace(' ', '', $localizacion);
        //Obtengo las partes que necesito de cada dato
        $propietario_generado = mb_substr($nombre, -3);
    
        $dpto_generado = mb_substr($dpto, -1);
    
        $dia_generado = mb_substr($dia, -2);
    
        $hora_generada = mb_substr($hora, 0,2);
    
    
        //Concateno las pastes generadas
        $id = $propietario_generado.$dpto_generado.$localizacion.$dia_generado.$hora_generada;
    
        //Normalizo el ID
        $id = normalizar_cadena($id);
    
        //Devuelvo el ID en minúsculas
        return  mb_strtolower($id);
    
    }

    function genarador_id_departamento($nombre_dpto){
        $id_generado = mb_substr($nombre_dpto,0, 3);
        
        return $id_generado;
        
    }




    /*****************************************
     *       Generadores de visualizacion    *
     *****************************************/
    //Genera la tabla de RECURSOS DISPONIBLES
    function mostrar_recursos()
    {
        $consulta = "SELECT * FROM recursos WHERE DATE(fecha) > DATE(NOW()) OR DATE(fecha) = DATE(NOW()) AND TIME(hora_fin) > TIME(NOW())ORDER BY fecha ASC ";
        //(Fecha > CURRENT_DATE) OR ((Fecha = CURRENT_DATE) AND (Fecha Final >= CURRENT_DATE))
        // $consulta = "SELECT * FROM recursos WHERE DATE(fecha) = DATE(NOW()) AND TIME(hora_inicio) <= TIME(NOW()) AND TIME(hora_fin) > TIME(NOW())";

        $resultados = get_consulta($consulta);
    
        $num_filas = get_num_filas($resultados);

        echo "<form id='recursos'>";
            echo "<table style='width:100%'>";
                echo "<tr id='recursos_cabecera'>";
                    echo "<td><strong>ID</strong></td>";
                    echo "<td><strong>Nombre</strong></td>";
                    echo "<td><strong>Fecha</strong></td>";
                    echo "<td><strong>H.Inicio</strong></td>";
                    echo "<td><strong>H.Fin</strong></td>";
                    echo "<td>
                            <img type='image' name='refrescar_recursos' class='icono' src='img/iconos/refresco.png' title='Refrescar tabla de recursos' alt='Refrescar' onClick='refresca_recursos_ajax();recurso=``'>
                         </td>";


        echo "</tr>";

                for ($i = 0; $i < $num_filas; $i++) {
                    $fila = get_fila($resultados);
                    echo "<tr>";
                        echo "<td>" . $fila["id_recurso"] . "</td>";
                        echo "<td>" . $fila["nombre"] . "</td>";
                        echo "<td>" . $fila["fecha"] . "</td>";
                        echo "<td>" . $fila["hora_inicio"] . "</td>";
                        echo "<td>" . $fila["hora_fin"] . "</td>";

                        echo "<td><input type='radio' name='recurso' value=".$fila['id_recurso']." onchange='id_recurso=this.value'></td>";
                    echo "</tr>";
                }
        echo "</table>";
        echo "</form>";
    }

    //Lista el recurso correspondiente al ID_RECURSO que se le pasa.
    function listar_recurso($id_recurso){
        $consulta="SELECT * FROM recursos WHERE id_recurso = '$id_recurso'";

        $resultados = get_consulta($consulta);
        

        $num_filas = get_num_filas($resultados);
        $fila = get_fila($resultados);

        $profesional = get_profesional($fila['id_profesional']);


        if($num_filas > 0){
            echo "<ul>";
            echo "<li>ID: ".$fila[0]."</li>";
            echo "<li>Nombre: ".$fila[1]."</li>";
            echo "<li>Descripción: ".$fila[2]."</li>";
            echo "<li>Asignatura: ".$fila[3]."</li>";
            echo "<li>Despacho: ".$fila[4]."</li>";
            echo "<li>Fecha: ".$fila[5]."</li>";
            echo "<li>Comienzo: ".$fila[6]."</li>";
            echo "<li>Finalización: ".$fila[7]."</li>";
            echo "<li>Responsable: ".$profesional."</li>";
            echo "<li>Departamento: ".$fila[9]." </li>";
            echo "</ul>";
        }

    }




/**********Visualización***********************/


    function visualizacion(){
        //Usuarios de los recursos actuales.
        $consulta = "SELECT * FROM colas WHERE  id_recurso IN 
                    (SELECT id_recurso FROM recursos WHERE DATE(fecha) = DATE(NOW()) AND TIME(hora_inicio) <= TIME(NOW()) AND TIME(hora_fin) > TIME(NOW()))
                    ORDER by colas.posicion_cola, colas.id_recurso ASC";

        $consulta2 = "SELECT id_recurso FROM recursos WHERE DATE(fecha) = DATE(NOW()) AND TIME(hora_inicio) <= TIME(NOW()) AND TIME(hora_fin) > TIME(NOW()) ORDER BY id_recurso ASC";


        $resultados2 = get_consulta($consulta2);
        $num_recursos = get_num_filas($resultados2);

        $resultados = get_consulta($consulta);
        $num_usuarios = get_num_filas($resultados);

        echo "<table id='visualizacion'  >";
        echo "<tr>";
        echo "<th>Usuario: </th>";
        echo "<th>Despacho:</th>";
        echo "<th>Hora:</th>";
        echo "</tr>";
        if($num_usuarios > 0) {
            //Validamos que haya usuarios en cola (Columnas id_usuario e id_recurso)


            for ($i = 0; $i < $num_recursos; $i++) {
                $recurso = get_fila($resultados2);
                $id_recurso = $recurso[0];
                $contador = 0;
                $contador1 = 0;

                $consulta3="SELECT * FROM colas WHERE  id_recurso='$id_recurso' AND estado_usuario = '0' AND hora_llamada !='' ORDER  BY posicion_cola asc";
                $usuarios_recurso = get_consulta($consulta3);

                $consulta4 = "SELECT * FROM colas WHERE  id_recurso='$id_recurso' AND estado_usuario = '2' ORDER  BY hora_llamada asc";
                $usuarios_llamados = get_consulta($consulta4);

                //Muestras los 4 ultimos llamados

                $num_usuarios_llamados = get_num_filas($usuarios_llamados);
                $puntero = $num_usuarios_llamados - 15;
                mysqli_data_seek($usuarios_llamados, $puntero);
                $usuario_llamado = get_fila($usuarios_llamados);
                while($id_recurso == $usuario_llamado[0] && $contador1 <15 ){
                    echo "<tr>";
                    echo "<td>".$usuario_llamado[1].$contador1."</td>";
                    echo "<td>" . get_despacho($usuario_llamado[0]) . "</td>";
                    echo "<td>" .$usuario_llamado[5]. "</td>";
                    echo "</tr>";

                    $contador1 ++;
                    $usuario_llamado = get_fila($usuarios_llamados);

                }


                //muestra los 2 siguientes que seran llamados
                $usuario = get_fila($usuarios_recurso);
                while($id_recurso == $usuario[0] && $contador <1){
                    echo "<tr>";
                    echo "<td>$usuario[1]</td>";
                    echo "<td>" . get_despacho($usuario[0]) . "</td>";
                    echo "<td>" .$usuario[5]. "</td>";
                    echo "</tr>";
                    $contador ++;
                    $usuario = get_fila($usuarios_recurso);

                }


            }
        }
        echo "</table>";


    }

    function visualizacion_avisos(){
        
        echo "<table id='visualizacion' >";
            echo "<tr>";
            echo "<td colspan='4'>";
            echo "<p>Aviso:<marquee id='wind'  behavior=\"scroll\" direction=\"left\"></marquee></p>";
            echo "</td>";
            echo "</tr>";
        echo "</table>";
    }

    function get_avisos(){
        $consulta = "SELECT nombre, mensaje FROM recursos WHERE mensaje != '' AND id_recurso IN (
                      SELECT id_recurso FROM recursos WHERE DATE(fecha) = DATE(NOW()) AND TIME(hora_inicio) <= TIME(NOW()) AND TIME(hora_fin) > TIME(NOW()))";
        $resultados = get_consulta($consulta);
        $mensajes='';

        //Si hay mensajes
        $num_filas = get_num_filas($resultados);
        if($num_filas >0 ){

            for($i=0; $i < $num_filas; $i++){
                $fila = get_fila($resultados);

                $mensajes = $mensajes.'&nbsp;&nbsp;'.' RECURSO: '.$fila[0].': '.$fila[1]. '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            }
        }
        else
            $mensajes = 'Bienvenido, No hay mensajes.';

        echo $mensajes;
    }

    function set_hora_llamada($id_usuario, $id_recurso){
        $consulta = "UPDATE colas SET hora_llamada = TIME(NOW()) WHERE id_usuario = '$id_usuario' AND id_recurso='$id_recurso'";
        get_consulta($consulta);
    }

    /*****************************************
     *       Generadores de formulario       *
     *****************************************/
    function generar_formulario_inscripcion(){
        echo'
            <!--Formulario de inscripción-->
            <form id = "inscripcion" >
                <table>
                    <tr>
                        <td>
                            <label>Nombre: </label>
                            <input id = "inscripcion"  type="text" id="nombre" name="nombre" value="" />
                            <script>var nombre = document.getElementById("nombre").value;</script>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <label>Primer apellido: </label>
                            <input id = "inscripcion"  type="text" id="primer_apellido" name="primer_apellido" value=""/>
                            <script>var primer_apellido = document.getElementById("primer_apellido").value;</script>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <label>Segundo apellido </label>
                            <input  id = "inscripcion"  type="text" id="segundo_apellido" name="segundo_apellido" value=""/>
                            <script> var segundo_apellido = document.getElementById("segundo_apellido").value;</script>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <label>Dni: </label>
                            <input  id = "inscripcion"  type="text" id="dni" name="dni" value="" placeholder="Formato 00000000A"/>
                            <script>var dni = document.getElementById("dni").value;</script>
                        </td>
                    </tr>
                        <td>
                             <script>var recurso = id_recurso;</script>
                            <input name="enviar" type="button"  value = "Enviar" 
                                onclick = "validar_formulario_inscripcion(id_recurso)"
                            />
                            
                        </td>
                    </tr>
                </table>
            </form >
        ';

    }

    function generar_formulario_localizador($funcion){
    echo'
            <!--Formulario localizador-->
            <form id = "localizador" >
                <table>
                    <tr>
                        <td>
                            <label>Localizador: </label>
                            <input type="text" id="localizador" name="localizador" value="" />
                            <script>var localizador = document.getElementById("localizador").value;</script>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input name="enviar" type="button"  value = "Enviar" 
                                onclick = "validar_formulario_localizador(id_recurso, '.$funcion.')"
                            />
                        </td>
                    </tr>
                </table>
            </form >
        ';

}

    //Administración de usuarios
    function generar_formulario_alta_usuario(){
        echo "<p><strong>Alta de nuevo usuario</strong></p>";
        echo'
            <!--Formulario Altas-->
            <form id = "localizador" >
                <table style="width:80%">
                    <tr>
                        <td>
                            <label>Nombre: </label>
                            <input type="text" id="nombre" name="nombre" value="" />
                            <script>var nombre = document.getElementById("nombre").value;</script>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Primer apellido: </label>
                            <input type="text" id="primer_apellido" name="primer_apellido" value="" />
                            <script>var primer_apellido = document.getElementById("primer_apellido").value;</script>
                        </td>                     
                    </tr>
                    <tr>
                        <td>
                            <label>Segundo apellido: </label>
                            <input type="text" id="Segundo_apellido" name="segundo_apellido" value="" />
                            <script>var segundo_apellido = document.getElementById("segundo_apellido").value;</script>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Dni: </label>
                            <input type="text" id="dni" name="dni" value="" />
                            <script>var dni = document.getElementById("dni").value;</script>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Email: </label>
                            <input type="email" id="email" name="email" value="" />
                            <script>var email = document.getElementById("email").value;</script>
                        </td>
                    </tr>
                    <tr>   
                        <td>
                            <label>Login: </label>
                            <input type="text" id="login" name="login" value="" />
                            <script>var login = document.getElementById("login").value;</script>
                        </td>
                    </tr>
                    <tr>    
                        <td>
                            <label>Contraseña: </label>
                            <input type="password" id="pass" name="pass" value="" />
                            <script>var pass = document.getElementById("pass").value;</script>
                        </td>
                    </tr>
                    <tr>    
                        <td>
                            <label>Repita la contraseña: </label>
                            <input type="password" id="pass2" name="pass2" value="" />
                            <script>var pass2 = document.getElementById("pass2").value;</script>
                        </td>
                    </tr>
                    <tr>    
                        <td>
                            <label>Perfil: </label>
                            <select type="text" id="codigo" name="codigo" value="">
                                <option selected="selected" value="" ></option>
                                <option value="prf">Profesor</option>
                                <option value="adm">Administrador</option>
                            </select>
                            <script>var codigo = document.getElementById("codigo").value;</script>
                        </td>                   
                    </tr>
                    <tr>    
                        <td>
                            <label>Departamento: </label>
        ';
                            generar_select_dpto_general();
        echo '
                        </td>          
                    </tr>
                    <tr>
                        <td>
                            <input name="enviar" type="button"  value = "Enviar" onclick="get_formulario_usuario_ajax()"/>
                        </td>
                    </tr>
                </table>
            </form >
        ';
        
    }

    function generar_formulario_baja_consultar_usuario(){
        echo'
                <!--Formulario Bajas-->
                <form id = "localizador" >
                    <table style="width:80%">
                        <tr>
                            <td>
                                <label>Dni: </label>
                                <input type="text" id="dni" name="dni" value="" />
                                <script>var dni = document.getElementById("dni").value;</script>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input name="enviar" type="button"  value = "Enviar" 
                                    onclick = "get_formulario_localizador_ajax()"
                                />
                            </td>
                        </tr>
                    </table>
                </form >
            ';

    }

    //Carga en el formulario los datos para ser modificados
    function generar_formulario_modificar_usuario(){
        echo'
                <!--Formulario Modificar-->
                <form id = "localizador" >
                    <table style="width:80%">
                        <tr>
                            <td>
                                <label>Nombre: </label>
                                <input type="text" id="nombre" name="nombre" value="" />
                                <script>var nombre = document.getElementById("nombre").value;</script>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Primer apellido: </label>
                                <input type="text" id="primer_apellido" name="primer_apellido" value="" />
                                <script>var primer_apellido = document.getElementById("primer_apellido").value;</script>
                            </td>                     
                        </tr>
                        <tr>
                            <td>
                                <label>Segundo apellido: </label>
                                <input type="text" id="Segundo_apellido" name="Segundo_apellido" value="" />
                                <script>var Segundo_apellido = document.getElementById("Segundo_apellido").value;</script>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Dni: </label>
                                <input type="text" id="dni" name="dni" value="" />
                                <script>var dni = document.getElementById("dni").value;</script>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Email: </label>
                                <input type="text" id="email" name="email" value="" />
                                <script>var email = document.getElementById("email").value;</script>
                            </td>
                        </tr>
                        <tr>   
                            <td>
                                <label>Login: </label>
                                <input type="text" id="login" name="login" value="" />
                                <script>var login = document.getElementById("login").value;</script>
                            </td>
                        </tr>
                        <tr>    
                            <td>
                                <label>Contraseña: </label>
                                <input type="text" id="pass" name="pass" value="" />
                                <script>var pass = document.getElementById("pass").value;</script>
                            </td>
                        </tr>
                        <tr>    
                            <td>
                                <label>Repita la contraseña: </label>
                                <input type="text" id="pass" name="pass" value="" />
                                <script>var pass = document.getElementById("pass").value;</script>
                            </td>
                        </tr>
                        <tr>    
                            <td>
                                <label>Perfil: </label>
                                <select type="text" id="codigo" name="codigo" value="">
                                    <option selected="selected" value="" ></option>
                                    <option value="prf">Profesor</option>
                                    <option value="adm">Administrador</option>
                                </select>
                                <script>var codigo = document.getElementById("codigo").value;</script>
                            </td>                   
                        </tr>
                        <tr>    
                            <td>
                                <label>Departamento: </label>
            ';
        generar_select_dpto_general();
        echo '
                            </td>          
                        </tr>
                        <tr>
                            <td>
                                <input name="enviar" type="button"  value = "Enviar" 
                                    onclick = "get_formulario_localizador_ajax()"
                                />
                            </td>
                        </tr>
                    </table>
                </form >
            ';

    }

    //Administración de RECURSOS
    function generar_formulario_alta_recurso(){
        echo'
                <!--Formulario Altas-->
                <form id = "localizador" >
                    <table style="width:80%">
                        <tr>
                            <td>
                                <label>Nombre: </label>
                                <input type="text" id="nombre" name="nombre" value="" />
                                <script>var nombre = document.getElementById("nombre").value;</script>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Descripción: </label>
                                <input type="text" id="descripcion" name="descripcion" value="" />
                                <script>var descripcion = document.getElementById("descripcion").value;</script>
                            </td>                     
                        </tr>
                        <tr>    
                            <td>
                                <label>Asignatura: </label>
        ';
                                generar_select_asignatura_general();
        echo '
                            </td>          
                        </tr>
                        <tr>
                            <td>
                                <label>Despacho: </label>
                                <input type="number" id="despacho" name="despacho" min="0" max="100" value="" />
                                <script>var despacho = document.getElementById("despacho").value;</script>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Fecha: </label>
                                <input type="date" id="fecha" name="fecha" value="" />
                                <script>var fecha = document.getElementById("fecha").value;</script>
                            </td>
                        </tr>
                        <tr>   
                            <td>
                                <label>Hora inicio: </label>
                                <input type="time" id="h_inicio" name="h_inicio"  max="22:00:00" min="08:00:00" step="1" />
                                <script>var h_inicio = document.getElementById("h_inicio").value;</script>
                            </td>
                        </tr>
                        <tr>    
                            <td>
                                <label>Hora fin: </label>
                                <input type="time" id="h_fin" name="h_fin"  max="22:00:00" min="08:00:00" step="1"/>
                                <script>var h_fin = document.getElementById("h_fin").value;</script>
                            </td>
                        </tr>
                        <tr>    
                            <td>
                                <label>Dni: </label>
                                <input type="text" id="dni" name="dni" value="" />
                                <script>var dni = document.getElementById("dni").value;</script>
                            </td>
                        </tr>
                        <tr>    
                            <td>
                                <label>Departamento: </label>
        ';
                                generar_select_dpto_general();
        echo '
                            </td>          
                        </tr>
                        <tr>
                            <td>
                                <label>Aviso: </label>
                                <input type="text" id="aviso" name="aviso" value="" />
                                <script>var aviso = document.getElementById("aviso").value;</script>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input name="enviar" type="button"  value = "Enviar" 
                                    onclick = "get_formulario_localizador_ajax()"
                                />
                            </td>
                        </tr>
                    </table>
                </form >
            ';

    }

    function generar_formulario_baja_consultar_recurso(){
    //Se selecciona

    }

    //Carga en el formulario los datos para ser modificados
    function generar_formulario_modificar_recurso(){
        echo'
                    <!--Formulario Altas-->
                    <form id = "localizador" >
                        <table style="width:80%">
                            <tr>
                                <td>
                                    <label>Nombre: </label>
                                    <input type="text" id="nombre" name="nombre" value="" />
                                    <script>var nombre = document.getElementById("nombre").value;</script>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Descripción: </label>
                                    <input type="text" id="descripcion" name="descripcion" value="" />
                                    <script>var descripcion = document.getElementById("descripcion").value;</script>
                                </td>                     
                            </tr>
                            <tr>    
                                <td>
                                    <label>Asignatura: </label>
            ';
        generar_select_asignatura_general();
        echo '
                                </td>          
                            </tr>
                            <tr>
                                <td>
                                    <label>Despacho: </label>
                                    <input type="number" id="despacho" name="despacho" value="" />
                                    <script>var despacho = document.getElementById("despacho").value;</script>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Fecha: </label>
                                    <input type="date" id="fecha" name="fecha" value="" />
                                    <script>var fecha = document.getElementById("fecha").value;</script>
                                </td>
                            </tr>
                            <tr>   
                                <td>
                                    <label>Hora inicio: </label>
                                    <input type="time" id="h_inicio" name="h_inicio" value="" />
                                    <script>var h_inicio = document.getElementById("h_inicio").value;</script>
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Hora fin: </label>
                                    <input type="time" id="h_fin" name="h_fin" value="" />
                                    <script>var h_fin = document.getElementById("h_fin").value;</script>
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Dni: </label>
                                    <input type="text" id="dni" name="dni" value="" />
                                    <script>var dni = document.getElementById("dni").value;</script>
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Departamento: </label>
            ';
        generar_select_dpto_general();
        echo '
                                </td>          
                            </tr>
                            <tr>
                                <td>
                                    <label>Aviso: </label>
                                    <input type="text" id="aviso" name="aviso" value="" />
                                    <script>var aviso = document.getElementById("aviso").value;</script>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input name="enviar" type="button"  value = "Enviar" 
                                        onclick = "get_formulario_localizador_ajax()"
                                    />
                                </td>
                            </tr>
                        </table>
                    </form >
                ';
    
    }

    /*****************************************
     *             Gestion de usuarios        *
     *****************************************/
    function añadir_usuario($datos_usuario){
        $datos_usuario = explode(',',$datos_usuario);

        $nombre = $datos_usuario[0];
        $primer_apellido = $datos_usuario[1];
        $segundo_apellido = $datos_usuario[2];
        $dni = $datos_usuario[3];
        $email = $datos_usuario[4];
        $login = $datos_usuario[5];
        $pass = $datos_usuario[6];
        $codigo = $datos_usuario[7];
        $departamento = $datos_usuario[8];
        $id_usuario = generador_id_profesional($codigo, $nombre, $primer_apellido, $segundo_apellido, $dni);

        $consulta = "INSERT INTO `profesionales` (`id_profesional`, `cod_profesional`, `nombre`, `primer_apellido`, `segundo_apellido`, `dni`, `email`, `login`, `pass`, `departamento`) 
                    VALUES ('$id_usuario', '$codigo', '$nombre', '$primer_apellido', '$segundo_apellido', '$dni', '$email', '$login', md5('$pass'), '$departamento')";
        
        if(get_consulta($consulta))
            echo "Usuario ".$nombre." ".$primer_apellido." ".$segundo_apellido." creado correctamente.";
        else
            echo "Ocurrio un error al crear el usuario ".$nombre." ".$primer_apellido." ".$segundo_apellido.".";
    }

    /*****************************************
     *             Gestion de cola            *
     *****************************************/
    function inscribir_usuario($usuario){
        //Convertir el array JAVASCRIPT a array PHP
        $datos = explode(',',$usuario);

        $nombre = $datos[0];
        $primer_apellido = $datos[1];
        $segundo_apellido = $datos[2];
        $dni = $datos[3];
        $id_recuro = $datos[4];
        
        //Busco el usuario en la tabla por DNI
        $consulta="SELECT id_usuario FROM usuarios WHERE dni = '$dni' ";
        $resultados = get_consulta($consulta);
        $num_filas = get_num_filas($resultados);

        if($num_filas == 0) {
            $id_usuario = generador_id($nombre, $primer_apellido, $segundo_apellido, $dni);
            $consulta = "INSERT INTO `usuarios` (`id_usuario`, `nombre`, `primer_apellido`, `segundo_apellido`, `dni`) 
                                  VALUES ('$id_usuario', '$nombre', '$primer_apellido', '$segundo_apellido', '$dni')";
            get_consulta($consulta);

        }
        else {
            $fila = get_fila($resultados);
            $id_usuario = $fila[0];
        }

        //Comprobamos que no exista en la cola para ese recurso.
        $consulta2="SELECT id_usuario FROM colas WHERE id_usuario = '$id_usuario' AND id_recurso = '$id_recuro'";
        $resultados2 = get_consulta($consulta2);
        $num_filas2 = get_num_filas($resultados2);
        if($num_filas2 > 0 ){
            echo "Ya está inscrito en el recurso ".$id_recuro." con el identificador ".$id_usuario;

        }
        else {
            $posicion = get_turno($id_recuro);

            $consulta = "INSERT INTO `colas` (`id_recurso`, `id_usuario`, `posicion_cola`, `estado_usuario`) VALUES ('$id_recuro', '$id_usuario', '$posicion', '0');";


            if (get_consulta($consulta)) {
                $turno = get_posicion($id_usuario, $id_recuro);
                echo "Su inscripción en el recurso " . $id_recuro . " ha sido realizada correcatmente. Se le ha asignado el localizador " . $id_usuario . " " . $turno;

            } else {
                echo "Ocurrio un error al inscribirle en el recurso " . $id_recuro;

            }
        }
    }

    function informar_posicion($datos){
        $datos = explode(',',$datos);

        $id_usuario = $datos[0];
        $id_recurso = $datos[1];

        $mensaje = get_posicion($id_usuario, $id_recurso);

        echo $mensaje;
    }

    //Consultar si el usuario esta inscrito en mas de un recurso
    function consultar_multinscripcion_usuario($id_usuario){
        $consulta =  "SELECT id_recurso  FROM colas where id_usuario = '$id_usuario'";
        $resultados = get_consulta($consulta);
        $num_filas = get_num_filas($resultados);
        if($num_filas > 0)
            return 1;
        else
            return 0;
    } 

    function verificar_localizador($id_usuario, $id_recurso){
        $consulta =  "SELECT *  FROM colas where id_recurso = '$id_recurso' AND id_usuario = '$id_usuario'";
        $resultados = get_consulta($consulta);
        $num_filas = get_num_filas($resultados);

        if($num_filas > 0)
            return true;
        else
            return false;

    }

    function eliminar_usuario_cola($datos){
        $datos = explode(',',$datos);
        
        //Sí localizador existe para ese recurso
        if (verificar_localizador($datos[0], $datos[1])) {
            $consulta = "DELETE FROM colas where id_usuario = '$datos[0]' AND id_recurso = '$datos[1]'";
            if(!consultar_multinscripcion_usuario($datos[0])) {
                $consulta2 = "DELETE FROM usuarios where id_usuario = '$datos[0]'";
                get_consulta($consulta2);
            }
            if(get_consulta($consulta)){
                echo "Operación realizada correctamente";
            }
            else{
                echo "Ocurrio un error durante la operación";
            }
        }
        else{
            echo "No existe coincidencia";
        }


    }

    function eliminar_usuario_cola_formularios($id_usuario, $id_recurso){


        //Sí localizador existe para ese recurso
        if (verificar_localizador($id_usuario, $id_recurso)) {
            $consulta = "DELETE FROM colas where id_usuario = '$id_usuario' AND id_recurso = '$id_recurso'";
            get_consulta($consulta);

            if(!consultar_multinscripcion_usuario($id_usuario)) {
                $consulta2 = "DELETE FROM usuarios where id_usuario = '$id_usuario'";
                get_consulta($consulta2);
            }
        }

    }

    function eliminar_recurso_formulario($id_recurso){
        $consulta = "SELECT id_usuario FROM colas WHERE id_recurso = '$id_recurso'";
        $resultados = get_consulta($consulta);
        $num_filas = get_num_filas($resultados);
        if($num_filas > 0){
            for($i=0; $i<$num_filas; $i++){
                $id_usuario = get_fila($resultados);
                eliminar_usuario_cola_formularios($id_usuario[0], $id_recurso);
            }
        }


        $consulta2 = "DELETE FROM recursos WHERE id_recurso = '".$id_recurso."'";
        $rs = get_consulta($consulta2);
        return $rs; 
    }

    function eliminar_usuario_propietario($id_propietario){
        $consulta = "SELECT id_recurso FROM recursos WHERE id_profesional = '$id_propietario'";
        $resultados = get_consulta($consulta);
        $num_filas = get_num_filas($resultados);

        if($num_filas >  0){
            for($i=0; $i<$num_filas; $i++){
                $id_recurso = get_fila($resultados);
                eliminar_recurso_formulario($id_recurso[0]);
            }
        }

        $consulta2 = "DELETE FROM profesionales WHERE id_profesional= '$id_propietario'";
        $rs1 = get_consulta($consulta2);
        return $rs1;
    }

    function generar_gestor_recurso($id_propietario){
        $datos_recurso = get_recursos_activos_id($id_propietario);
        if($datos_recurso) {
            echo "<strong>Recurso:</strong> ".$datos_recurso[1];
        
            $datos_siguiente = get_siguiente($datos_recurso[0]);
        
            if($datos_siguiente){
                $datos_usuario = get_datos_cliente($datos_siguiente[0]);
                set_hora_llamada($datos_usuario[0],$datos_recurso[0]);

                echo"<ul>";
                    echo "<li><strong>Usuario:</strong></li>";
                    echo "<ul>";
                        echo "<li><strong>ID:</strong> ".$datos_usuario[0]."</li>";
                        echo "<li><strong>Nombre:</strong> ".$datos_usuario[1].' '.$datos_usuario[2].' '.$datos_usuario[3]."</li>";
                        echo "<li><strong>Dni:</strong> ".$datos_usuario[4]."</li>";
                    echo "</ul>";
                echo "</ul>";
                echo "</br></br></br></br>";
                $consulta = "SELECT en_atencion FROM colas WHERE id_usuario = '$datos_usuario[0]'";
                $resultado = get_consulta($consulta);
                $fila = get_fila($resultado);
                //Obtenemos el campo en espera
                echo '<table>
                <tr>
                    <form>
                        <td>
                            <input  type="button" value="Siguiente" onclick = "cerrar_atencion_ajax(`'.$datos_usuario[0].'`,`'.$id_propietario.'`)"/>
                       </td>
                       <td>
                       </td>
                       <td>';
                if($fila[0]){
                    echo' <input class="seguir_esperando" type="button" value="Mantener en espera.." onclick = "poner_espera_ajax(`'.$datos_recurso[0].'`,`'.$fila[0].'`,`'.$datos_usuario[0].'`,`'.$id_propietario.'`)"/>';
                } 
                else{
                    echo'  <input type="button" value="Poner en espera"  onclick = "poner_espera_ajax(`'.$datos_recurso[0].'`,`'.$fila[0].'`,`'.$datos_usuario[0].'`,`'.$id_propietario.'`)"/>';
                }
                echo' </td>
                </form>
                </tr>
                </table>';
            }
            else
                echo "<br> No hay usuarios en espera. <br>Intentelo de nuevo mas tarde.";
        }
        else
            echo "No existen recursos para gestionar.";
    }

    function colar_siguiente($id_recurso, $id_usuario, $num){


        $consulta = "SELECT id_usuario, posicion_cola FROM colas WHERE id_recurso = '$id_recurso' AND estado_usuario = '0' ORDER BY posicion_cola ASC";
        $resultados = get_consulta($consulta);
        $num_filas = get_num_filas($resultados);

        $fila = get_fila($resultados);

        //Obtenemos la posición actual (El id_usuario se nos pasa en la llamada)
        $posicion = $fila[1];


        $fila2 = get_fila($resultados);
        $fila3 = get_fila($resultados);

        //Orden cambio doble
        if($num == 2) {
            if ($num_filas > 2) {

                $id_usuario_siguiente = $fila3[0];
                $posicion_usuario_siguiente = $fila3[1];


                $consulta1 = "UPDATE colas SET posicion_cola = '$posicion' WHERE id_usuario = '$id_usuario_siguiente'";
                $consulta2 = "UPDATE colas SET posicion_cola = '$posicion_usuario_siguiente' WHERE id_usuario = '$id_usuario'";


                if (get_consulta($consulta1) && get_consulta($consulta2))
                    echo "Usuario puesto en espera correctamente";
                else
                    echo "Ocurrio un error al poner al usuario en espera";
            }else
                if($num_filas > 1)
                    colar_siguiente($id_recurso, $id_usuario, 1);


        }
        elseif ($num == 1){
            if ($num_filas > 1) {


                $id_usuario_siguiente = $fila2[0];
                $posicion_usuario_siguiente = $fila2[1];


                $consulta1 = "UPDATE colas SET posicion_cola = '$posicion' WHERE id_usuario = '$id_usuario_siguiente'";
                $consulta2 = "UPDATE colas SET posicion_cola = '$posicion_usuario_siguiente' WHERE id_usuario = '$id_usuario'";

                if (get_consulta($consulta1) && get_consulta($consulta2))
                    echo "Usuario puesto en espera correctamente";
                else
                    echo "Ocurrio un error al poner al usuario en espera";
            }
        }
    };

    function cerrar_atencion($id_usuario, $id_propietario){
        $recurso = get_recursos_activos_id($id_propietario);

        $consulta="UPDATE colas SET estado_usuario= 2 WHERE id_usuario = '$id_usuario' AND id_recurso = '$recurso[0]'";

        if(get_consulta($consulta)){
            $siguiente = get_siguiente($recurso[0]);
            set_hora_llamada($siguiente[0], $recurso[0]);
        echo "Operación realizada correctamente.";
        }
        else
        echo "La operación no fue posible.";


    }

    function poner_espera($id_recurso, $estado_espera, $id_usuario) {

        if($estado_espera) {
            colar_siguiente($id_recurso, $id_usuario,2);
        }
        else{
            colar_siguiente($id_recurso, $id_usuario,1);
            $consulta = "UPDATE colas SET en_atencion = '1' WHERE id_usuario = '$id_usuario' AND id_recurso='$id_recurso'";
            get_consulta($consulta);
        }

        $consulta1 = "UPDATE colas SET hora_llamada = NULL WHERE id_usuario = '$id_usuario' AND id_recurso='$id_recurso'";
        get_consulta($consulta1);

        //set_hora_llamada($id_usuario, $id_recurso);

        $siguiente  = get_siguiente($id_recurso);

        set_hora_llamada($siguiente[0], $id_recurso);
    }

    function mostrar_cola_recurso_activo($id_propietario)
        {
            $fila = get_recursos_activos_id($id_propietario);
            if ($fila) {
                mostrar_cola_recurso($fila[0]);
            }
            else
                echo "No Existen recursos activos en estos momentos";

        }

    function mostrar_cola_recurso($id_recurso){
        $datos_recurso = get_datos_recurso($id_recurso);
        if ($datos_recurso) {
            $consulta = "SELECT * FROM colas WHERE id_recurso = '$id_recurso' ORDER BY posicion_cola ASC";
            $datos_cola = get_consulta($consulta);
            if ($datos_cola) {
                echo '<table class="mostrar_cola">';
                    echo "<tr>";
                        echo "<th>Id: </th>";
                        echo "<th>Nombre: </th>";
                        echo "<th>Primer apellido: </th>";
                        echo "<th>Segundo apellido: </th>";
                        echo "<th>Dni: </th>";
                        echo "<th>Posición: </th>";
                        echo "<th style='text-align: center'>Estado:</th>";
                    echo "</tr>";
                    //Usuarios en cola.
                    $num_filas = get_num_filas($datos_cola);

                    for($i=0; $i<$num_filas; $i++){
                        $usuario_cola = get_fila($datos_cola);

                        $consulta2 = "SELECT * FROM usuarios WHERE id_usuario = '$usuario_cola[1]'";
                        $datos_usuario = get_consulta($consulta2);
                        $usuario = get_fila($datos_usuario);


                        echo "<tr >";
                            echo "<td>" .$usuario['id_usuario']. "</td>";
                            echo "<td>" .$usuario['nombre']. "</td>";
                            echo "<td>" .$usuario['primer_apellido']. "</td>";
                            echo "<td>" .$usuario['segundo_apellido']. "</td>";
                            echo "<td>" .$usuario['dni']. "</td>";
                            echo "<td>" .$usuario_cola['posicion_cola']. "</td>";
                            if($usuario_cola['estado_usuario']==0)
                                echo "<td>Aún por atender</td>";
                            else
                                echo "<td>Atendido</td>";
                            if($usuario_cola['en_atencion']==1 && $usuario_cola['estado_usuario']==0)
                                echo "<td>Mantenido en Atención</td>";
                        echo "</tr>";

                    }
                echo "</table>";
            } else
                echo "No Existen usuarios en cola en estos momentos";
        } else
              echo "Recurso ".$id_recurso." no encontrado";
    }
    ?>