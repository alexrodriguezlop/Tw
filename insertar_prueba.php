<?php
include "/funciones/libreria.php";

//Profesionales
$consulta = "INSERT INTO `profesionales` (`id_profesional`, `cod_profesional`, `nombre`, `primer_apellido`, `segundo_apellido`, `dni`, `email`, `login`, `pass`, `departamento`) 
            VALUES ('admangoro23j', 'adm', 'Juan', 'Rodríguez', 'Gonzalez', '13582733j', 'juan@email.com', 'juanx', md5('juanx'), 'lsi')
                    ,('prfiocoru59k', 'prf', 'Antonio', 'Ruíz', 'Cordonie', '13685319k', 'antonio@email.com', 'antoniox', md5('antoniox'), 'dec')
                    ,('prfassama32l', 'prf', 'Lucas', 'Martos', 'Sanchez', '24753313l', 'lucas@email.com', 'lucasx', md5('lucasx'), 'atc')
                    ,('admidmoji24f', 'adm', 'David', 'Jimenez', 'Montilla', '13182264f', 'david@email.com', 'davidx', md5('davidx'), 'adm')";
get_consulta($consulta);


//Usuarios
$consulta = "INSERT INTO `usuarios` (`id_usuario`, `nombre`, `primer_apellido`, `segundo_apellido`, `dni`) 
            VALUES ('polasa32h', 'Juan', 'Rodríguez', 'Gonzalez', '13582733j')
                    ,('esdaca24k', 'Antonio', 'Ruíz', 'Cordonie', '13685319k')
                    ,('sarauun71e','Lucas', 'Martos', 'Sanchez', '24753313l')
                    ,('rajaca18e', 'David', 'Jimenez', 'Montilla', '13182264f')
                    ,('rojoca18q', 'pepe', 'sanchez', 'martos', '47845712o')
                    ,('sedoja22l', 'luis', 'Ruíz', 'sanchez', '75541144u')
                    ,('waqwhb31r','sara', 'roloz', 'suna', '14752488t')
                    ,('opfgrd55e', 'marta', 'sanchez', 'lopez', '88554712d')
                     ,('reghmh12p', 'elena', 'sanchez', 'riviera', '74123658e')
                     ,('iodesa46y', 'sonia', 'Ruíz', 'jimenez', '78852145P')
                      ,('poltsc46i', 'jorge', 'perez', 'Gonzalez', '74213581w')";

get_consulta($consulta);


//Recursos
$consulta = "INSERT INTO `recursos` (`id_recurso`, `nombre`, `descripcion`, `asignatura`, `localizacion_recurso`, `fecha`, `hora_inicio`, `hora_fin`, `id_profesional`, `departamento`) 
            VALUES ('59kc240812', 'Correción de trabajos finales', 'corregire los trabajos finales de la asignatura', 'tw','24D', '2016-06-15', '00:00:00', '20:00:00',  'prfiocoru59k', 'dec')
                 ,('321c181817', 'Revisión parcial ', 'Estare disponible para la revision del primer parcial', 'tdrc', '18A', '2016-06-13', '00:00:00', '20:00:00', 'prfassama32l', 'atc')";

                
                
get_consulta($consulta);





//Cola
$consulta = "INSERT INTO `colas` (`id_recurso`, `id_usuario`, `posicion_cola`, `estado_usuario`) 
            VALUES ('59kc240812', 'polasa32h', '1', '0')
                    ,('59kc240812','sarauun71e','2', '0')
                    ,('59kc240812', 'rajaca18e', '3', '0')
                    ,('59kc240812','rojoca18q', '4', '0')
                    ,('59kc240812','sedoja22l', '5', '0')
                    ,('59kc240812','waqwhb31r','6', '0')
                    ,('59kc240812','opfgrd55e', '7', '0')
                    ,('59kc240812','reghmh12p', '8', '0')
                    ,('59kc240812','iodesa46y', '9', '0')
                    ,('59kc240812','poltsc46i', '10', '0')
                    ,('59kc240812','esdaca24k', '11', '0')
                    ,('321c181817', 'polasa32h', '1', '0')
                    ,('321c181817','sarauun71e','2', '0')
                    ,('321c181817', 'rajaca18e', '3', '0')
                    ,('321c181817','rojoca18q', '4', '0')
                    ,('321c181817','sedoja22l', '5', '0')
                    ,('321c181817','waqwhb31r','6', '0')
                    ,('321c181817','opfgrd55e', '7', '0')
                    ,('321c181817','reghmh12p', '8', '0')
                    ,('321c181817','iodesa46y', '9', '0')
                    ,('321c181817','poltsc46i', '10', '0')
                    ,('321c181817','esdaca24k', '11', '0')";


get_consulta($consulta);

echo "Si no se han reportado errores, todo ha sido un exito!";
?>