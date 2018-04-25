
<html>
<head>
    <title> Creando la estructura de Mysql </title>
</head>
<body>

<?php

//Datos servidor local(Activar CREATE)
/*
$servername = "localhost";
$username = "user";
$password = "user";
$db = "TWDB";
*/


//Datos servidor remoto (Desactivar CREATE)
$servername = "localhost";
$username = "ejercicio_pw";
$password = "pass_ejercicio_pw";
$db = "75162276r";



// Create connection
$conn = mysqli_connect($servername, $username, $password);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


// Create database
$sql = "CREATE DATABASE ".$db;
if (mysqli_query($conn, $sql)) {
    echo "Base de datos creada correctamente";
} else {
    echo "Error creando la base de datos, error: " . mysqli_error($conn);
}




$sql = "USE"." ".$db;
//select database
if (mysqli_query($conn, $sql)) {
    echo "Base de datos seleccionada correctamente";
} else {
    echo "Error seleccionando la base de datos, error: " . mysqli_error($conn);
}


// Create tabla profesionales
$sql = "CREATE TABLE profesionales( 
           id_profesional VARCHAR(30) NOT NULL UNIQUE,
           cod_profesional CHAR(3) NOT NULL,
           nombre VARCHAR(20) NOT NULL,
           primer_apellido VARCHAR(40) NOT NULL,
           segundo_apellido VARCHAR(40) NOT NULL,
           dni char(9) NOT NULL UNIQUE,
           email varchar(20) NOT NULL UNIQUE , 
           login varchar(20) NOT NULL UNIQUE ,
           pass varchar(32) NOT NULL,
           departamento varchar(20) NOT NULL,
           PRIMARY KEY(id_profesional))
           DEFAULT CHARSET=utf8, COLLATE=utf8_spanish_ci;
	   ";

if (mysqli_query($conn, $sql)) {
    echo "tabla PROFESIONALES  creada correctamente";
} else {
    echo "Error creando la tabla PROFESIONALES, error: " . mysqli_error($conn);
}



// Create tabla usuarios
$sql = "CREATE TABLE usuarios( 
           id_usuario VARCHAR(30) NOT NULL UNIQUE,
           nombre VARCHAR(20) NOT NULL,
           primer_apellido VARCHAR(40) NOT NULL,
           segundo_apellido VARCHAR(40) NOT NULL,
           dni char(9) NOT NULL UNIQUE,
           PRIMARY KEY(id_usuario))
           DEFAULT CHARSET=utf8, COLLATE=utf8_spanish_ci;
	   ";

if (mysqli_query($conn, $sql)) {
    echo "tabla USUARIOS  creada correctamente";
} else {
    echo "Error creando la tabla USUARIOS, error: " . mysqli_error($conn);
}







//Create tabla RECURSOS
$sql = "CREATE TABLE recursos( 
           id_recurso varchar(30) NOT NULL UNIQUE ,
           nombre varchar(40) NOT NULL,
           descripcion VARCHAR(60) NOT NULL,
           asignatura VARCHAR(20) NOT NULL,
           localizacion_recurso VARCHAR(5) NOT NULL,
           fecha DATE NOT NULL, 
           hora_inicio TIME NOT NULL,
           hora_fin TIME NOT NULL,
           id_profesional VARCHAR(30) NOT NULL,
           departamento varchar(20) NOT NULL,
           mensaje VARCHAR(80) NULL,
           PRIMARY KEY(id_recurso, id_profesional))
           DEFAULT CHARSET=utf8, COLLATE=utf8_spanish_ci;
	   ";

if (mysqli_query($conn, $sql)) {
    echo "tabla RECURSOS creada correctamente";
} else {
    echo "Error creando la tabla RECURSOS, error: " . mysqli_error($conn);
}

//Create tabla colas
$sql = "CREATE TABLE colas( 
           id_recurso varchar(30) NOT NULL,
           id_usuario varchar(30) NOT NULL,
           posicion_cola int NOT NULL, 
           estado_usuario int(1) NOT NULL DEFAULT 0,
           en_atencion int(1) NOT NULL DEFAULT 0 ,
           hora_llamada TIME,
           PRIMARY KEY(id_recurso, id_usuario))
           DEFAULT CHARSET=utf8, COLLATE=utf8_spanish_ci;
	   ";

if (mysqli_query($conn, $sql)) {
    echo "tabla COLAS creada correctamente";
} else {
    echo "Error creando la tabla COLAS, error: " . mysqli_error($conn);
}


mysqli_close($conn);

?>
</body>
</html>