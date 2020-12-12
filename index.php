<?php


session_start();

?>



<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<style>
body {
    color: beige;
font-family: Verdana, sans-serif;
 background: rgb(29,29,36);
background: linear-gradient(90deg, rgba(29,29,36,1) 0%, rgba(2,2,34,1) 35%, rgba(6,55,65,1) 100%); 

}



 /* unvisited link */
a:link {
  color: beige;
}

/* visited link */
a:visited {
  color: #eae6ca;
}

/* mouse over link */
a:hover {
  color: #cc6666;
}

/* selected link */
a:active {
  color: #a19d94;
} 


</style>
</head>
<body>
<h1> CENTRO DE CAPTURA DE FALTAS  (v 0.2)</h1>
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Medoo\Medoo;

// clear get 
if(isset($_GET)){
    foreach($_GET as $k => $v){
        
        $cleaned =addslashes( $v);
        $cleaned = strip_tags  ( $cleaned);
           
        $_GET[$k]=$cleaned; 
    }
}



					## on mac                             ## on linux
if( $_SERVER['REMOTE_ADDR'] =="::1" || $_SERVER['REMOTE_ADDR']=="192.168.64.1"   ){ 

    echo "<h5> THE IP ADDRESS IS: ". $_SERVER['REMOTE_ADDR'] . "</h5>";
    // only report if you are in the localhost	
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $database = new Medoo([
        'database_type' => 'mysql',
        'database_name' => 'alumnos',
        //'server' => '192.168.64.3',
        'server' => 'localhost',
        'username' => 'root',
        //'password' => '',
        'password' => ''
    ]);
		} else {
                    $database = new Medoo([
                      'database_type' => 'mysql',
                      'database_name' => 'id14934383_mydatabase',
                      'server' => 'localhost',
                      'username' => 'id14934383_databaseuser',
                      'password' => '?028iwYPEvR!eYWy',
                    ]);
		}


if(!isset(  $_SESSION['log'] ) ){

    if( isset($_GET['usuario']) && isset($_GET['contrasena']) ){
        if(  $_GET['usuario'] == 'adriana2021' && $_GET['contrasena'] == 'p0rfirio_'){
            echo " <p> <i> Ingreso exitoso </i> ";
             echo "<h1> ¿Por donde quieres empezar ?</h1>"; 
            $_SESSION['log'] = 1;
        }
    }else {


        echo "

        <form method='get'>
        <p> Usuario:
        <input type='text' name='usuario'> 
        <p> Contraseña:
        <input type='password' name='contrasena'> 
        <p>
        <input type='submit' value='Enviar'>

        ";
    }
}
    


if( isset($_SESSION['log'])  && $_SESSION['log'] == 1 ){
    $img_dim =80;

    if( isset( $_GET['guardar'])){
        guardar_datos($database, $_GET);
    }



        echo "<table valign='top'> <tr> <td valign='top'> ";
    echo " <center> 
    Buscar por:
    <table bgcolor=beige> <tr> 
            <td><a href='?modo=docente'> 

               <img src='teacher.png' width=$img_dim height=$img_dim>
              <br>  Docente </a></td> </tr>
        <tr>  
        <td> <a href='?modo=grupo'>
        
        <img src='grupo.png' width=$img_dim height=$img_dim>
             <br>  Grupo </a></td></tr>
    <tr>    
     <td> <a href='?modo=alumno'>

        <img src='student.png' width=$img_dim height=$img_dim>
        <br>  Alumno </a></td>
    <tr>
        <td>
            <a href='?modo=reporte'>

            <img src='rep.png' width=$img_dim height=$img_dim>
        <br>  Reportes </a>
        </td>
    </tr>
    <tr>
        <td>
            <a href='?modo=autocaptura'>

            <img src='robot1.png' width=$img_dim height=$img_dim>
        <br>  Auto<br> Captura  </a>
        </td>
    </tr>

    <tr>
        <td>
            <a href='?modo=exportar'>

            <img src='excel_icon.png' width=$img_dim height=$img_dim>
        <br>  Exportar </a>
        </td>
    </tr>

<tr>
        <td>
            <a href='?modo=configurar'>

            <img src='config.png' width=$img_dim height=$img_dim>
        <br>  Configurar </a>
        </td>
    </tr>



    </tr></table>
    </td>
    ";


    if(isset($_GET['modo']) && $_GET['modo']=='docente'  ){
       
        $data = $database->query('select distinct nombre from docentes order by nombre asc')->fetchAll();
        echo " <td valign='top'> ";
        foreach($data as $row){
            echo " <p><a href='?modo=docente&entrada=".urlencode($row['nombre']) . "'> $row[nombre] </a>";
        }
        echo "</td>";

        if( isset($_GET['entrada'])){
            echo "<td valign='top'>";
            

            echo " <p> Seleccionado:<br> $_GET[entrada]";
            $docente = $_GET['entrada'];


            $data_grupos = $database->query("select distinct grupo from docentes 
                                                where nombre='$docente' order by grupo asc ")->fetchAll();
            foreach($data_grupos as $row){
                
                echo " <p><a href='?modo=docente&entrada="
                    .urlencode($docente)."&entrada_2="
                    .urlencode($row['grupo']) . "'> $row[grupo] </a>";
            }

            echo "</td>";
        }

        if( isset($_GET['entrada_2'])){
            echo " <td valign='top'> ";
            $grupo = $_GET['entrada_2'];
            echo "<p>Grupo:<br> $grupo";
            $data_alumnos = $database->query("select nombre from alumnos 
                                        where grupo ='$grupo' order by nombre asc")->fetchAll();
            
            foreach($data_alumnos as $row){
                
                echo " <p><a href='?modo=docente&entrada="
                    .urlencode($docente)."&entrada_2="
                    .urlencode($grupo) . "&entrada_3="
                    .urlencode($row['nombre']). "'> $row[nombre] </a>";
            }
            
            echo "</td>";
        }
        
        if( isset($_GET['entrada_3'])){

            $alumno = $_GET['entrada_3'];

            put_form($database, $alumno, $grupo, $docente, 'docente','');           

        }
        echo "</tr></table>";
    }elseif(isset($_GET['modo']) && $_GET['modo']=='grupo'  ){
        $data_grupos = $database->query("select distinct grupo from alumnos 
                                             order by grupo asc ")->fetchAll();


            echo "<td>";

            $docente = 'x';
            foreach($data_grupos as $row){
                
                echo " <p><a href='?modo=grupo&entrada="
                    .urlencode($docente)."&entrada_2="
                    .urlencode($row['grupo']) . "'> $row[grupo] </a>";
            }

            echo "</td>";


        if( isset($_GET['entrada_2'])){
            echo " <td valign='top'> ";
            $grupo = $_GET['entrada_2'];
            echo "<p>Grupo:<br> $grupo";
            $data_alumnos = $database->query("select nombre from alumnos 
                                        where grupo ='$grupo' order by nombre asc")->fetchAll();
            
            foreach($data_alumnos as $row){
                
                echo " <p><a href='?modo=grupo&entrada="
                    .urlencode($docente)."&entrada_2="
                    .urlencode($grupo) . "&entrada_3="
                    .urlencode($row['nombre']). "'> $row[nombre] </a>";
            }
            
            echo "</td>";
        }


        if( isset($_GET['entrada_3'])){

            $alumno = $_GET['entrada_3'];

            put_form($database, $alumno, $grupo, $docente, 'grupo','');           

        }
        echo "</tr></table>";




    }elseif(isset($_GET['modo']) && $_GET['modo']=='alumno'  ){


        echo "<td valign='top'>";
        echo "
            <form method='get'>
            <input type='hidden' name='modo' value='alumno'>
            <p> Ingrese nombre de alumno (o parte del nombre):<p><input type='text' name='busqueda' size='60' >
            <p> <input type='submit' value='Buscar!' >
            "  ;  
        if(isset($_GET['busqueda'])){
            $busqueda= $_GET['busqueda'];
            $busqueda = strtoupper($busqueda);
            echo "<p> $busqueda ";
            $data = $database->query("select * from alumnos  where nombre like '%$busqueda%'")->fetchAll();
            echo "<p> <i> Resultados: </i>";
            foreach($data as $key => $row){
                echo "<p> $row[grupo] <a href='?modo=alumno&nombre=".
                            urlencode($row['nombre']) ."&grupo=".
                            urlencode($row['grupo'])."&busqueda=".
                            urlencode($_GET['busqueda'])."'> $row[nombre] </a> ";    
            }
        }
        if( isset($_GET['nombre'])){
            $alumno = $_GET['nombre'];
            $grupo = $_GET['grupo'];
            $docente= '';
            $busqueda=$_GET['busqueda'];
            put_form($database, $alumno, $grupo, $docente, 'alumno', $busqueda);           
        }

        echo "</td>";
    }elseif(isset($_GET['modo']) && $_GET['modo']=='reporte'  ){

        if(isset($_GET['guardar_renglon'])){
            $res = $database->query("update reportes 
                SET
                fecha_inicial ='$_GET[fecha_inicial]',
                docente ='$_GET[docente]',
                materia ='$_GET[materia]',
                observaciones ='$_GET[observaciones]'

                where id = $_GET[guardar_renglon]

                ");

            $codigo =  $res->errorCode();
            if($codigo=='00000'){
                echo "<p>Se ha actualizado exitosamente.";
            }else{

                echo " <p Hubo un error, reportar lo siguiente: ";
                print_r($_GET);
            }
        }
        echo "<td valign='top'>";
        echo "<table border=1>
            <tr> <td> GRUPO </td>
            <td> NOMBRE </TD>
            <td> FECHA INICIAL </td> 
            <td>DOCENTE</td> 
            <td>MATERIA</td> 
            <td>ASUNTO</td> 
            <td> </td>
            </tr> 
";
            $data = $database->query("select * from reportes")->fetchAll();
            foreach( $data as $key => $row){
                echo "<tr> <form method='get'>"; 
                echo "<td> $row[grupo] </td>";    
                echo "<td> $row[nombre] </td>";    
                echo "<td> <input type='text' name='fecha_inicial' value='$row[fecha_inicial]'> </td>";    
                echo "<td> <input type='text' name='docente' value='$row[docente]'>  </td>";    
                echo "<td> <input type='text' name='materia' value='$row[materia]'>  </td>";    
                echo "<td> <input type='text' name='observaciones' value='$row[observaciones]'>  </td>";    
                echo "<td> <input type='submit' value='Guardar'></td>";    
                echo "<input type='hidden' name='modo'  value='reporte'>";
                echo "<input type='hidden' name='guardar_renglon'  value='$row[id]'>";
                echo "</form> </tr>"; 
            }

            echo "</td>";
    
    }elseif(isset($_GET['modo']) && $_GET['modo']=='autocaptura'  ){
        echo "<td><h2> Ingresa reporte (individual por alumno)en texto  </h2>
          <h3> *BAJO CONSTRUCCION* Por lo pronto solo detecta alumnos y docentes en el texto ingresado... intenta poner un mensaje en donde aparezcan los nombres de los docentes que esten en la base de datos y algunos alumnos  </h3> 
                       <p>

           <form method='get'> 
            <textarea name='text-input' rows=10 cols = 100>

 [12:51 PM, 12/10/2020] BRUNO DIAZ: La alumna Castillo de Lerin Camila 1C, estuvo platicando con alguien más en su casa al momento de tomar clase.  CÍVICA Y ÉTICA 10 de diciembre.

             </textarea>
            <input type='hidden' name='modo' value='autocaptura'>          
              <br>
            <input type='submit'>
            </form>

            ";


            if( isset($_GET['text-input'])){
                $entrada = $_GET['text-input'];
                //echo " <p> INGRESO: $entrada ";
                $resultado = procesar_entrada_chat( $database, $entrada );
            }



            echo "</td>   ";
            
    }elseif(isset($_GET['modo']) && $_GET['modo']=='exportar'  ){
        echo "<td>   ";
        echo "<h3> Seleccionar formato de salida: </h3>";


        //print_r($_GET);

        $data = [['name'=>'modo', 'type'=> 'hidden', 'value'=>'exportar', 'description'=>''],
            ['name'=>'fecha_inicial', 'type'=>'date', 'description'=>'Fecha inicial  ', 'value'=>'2020-02-1'],
            ['name'=>'fecha_final', 'type'=>'date', 'description'=>'Fecha final  ', 'value'=>'2020-02-1'],
            ['name'=>'formato', 'type'=> 'radio',  
            'value'=>'concentrado_completo', 'description'=>'Concentrado completo (ya! listo)'],
            ['name'=>'formato', 'type'=> 'radio', 
            'value'=>'concentrado_docentes', 'description'=>'Concentrado para docentes ( bajo construccion)']

        ];
        put_item_form($data);

        if( isset($_GET['formato'])){
            echo "<p> SELECCIONADO: " . $_GET['formato'];
            $fecha_inicial = $_GET['fecha_inicial'];
            $fecha_final = $_GET['fecha_final'];
            echo "<p> Fechas: ( $fecha_inicial - $fecha_final  )";
            $res = exportar($database, $_GET['formato'], $fecha_inicial, $fecha_final);
            echo "<p> <a href='$res'> DESCARGAR </a>";
        }




        echo "</td>   ";
        
    
    }elseif(isset($_GET['modo']) && $_GET['modo']=='configurar'  ){
        echo "<td> <h2> Configurar </h2>"    ;
        echo '<h3> Agregar Docentes</h3>';
        $datos_config_maestro = [['name'=>'modo', 'type'=> 'hidden', 'value'=>'configurar', 'description'=>''],
            ['name'=>'nombre', 'type'=>'text', 'description'=>'Nombre: ', 'value'=>''],
            ['name'=>'materia', 'type'=>'text', 'description'=>'Materia: ', 'value'=>''],
            ['name'=>'grupo_1', 'type'=>'checkbox', 'description'=>'1A', 'value'=>'1A'],
            ['name'=>'grupo_2', 'type'=>'checkbox', 'description'=>'1B', 'value'=>'1A'],

        ];
        $n = 3;
        foreach( ['1C','1D','1E','2A','2B', '2C','2D', '2E', '3A', '3B', '3C','3D', '3E'] as $grupo ){
            array_push( $datos_config_maestro, 
            
                ['name'=>"grupo_$n", 'type'=>'checkbox', 'description'=> $grupo , 'value'=> $grupo  ]
            
            );
            $n++;
        }
        
        put_item_form($datos_config_maestro);



        if( isset( $_GET['nombre'])  ){

                print_r($_GET);

            if( $_GET['nombre'] != '' && $_GET['materia'] != '' && count($_GET)> 3 ){

                $nombre = $_GET['nombre'];
                $materia = $_GET['materia'];
                foreach($_GET as $k => $v){
                    if(strpos($k , 'grupo'  ) === 0){
                        echo " <p> Quieres indicar que $nombre da $materia al grupo $v ";
                        $res = $database->query("insert into docentes 
                                                    (nombre, materia, grupo) values
                                                    ('$nombre', '$materia', '$v')
                            ") ;
                        if( $res->errorCode() == '00000'){
                            echo " <b> GRABADO CON EXITO </B>";
                        }else {
                            echo " <b> ERROR al grabar, intentar de nuevo </b>";
                        }
                    }
                }
            }else{
                echo "<h2> DATOS INSUFICIENTES </H2>";
            }
        }
        echo "</td>";
    }
}

function put_item_form($data){
    echo " <form method='get'> ";
    foreach($data as  $row){
        if($row['description'] != ''){
            echo "<p> $row[description]";
        }
        echo "<input type='$row[type]' name='$row[name]'  value='$row[value]'>";
    }

    echo "<p><input type='submit'>";
    echo "</form>";
}


function put_form($database, $alumno, $grupo, $docente, $modo, $busqueda){
echo " <td valign='top'> ";

            echo "Alumno:<br> $alumno <br>";
            echo " 
             <form method='get' >


            <p> Fecha:
            <p>
                         <input type='date' name='fecha_inicial'
                   value='2020-12-2'>

       

    "; 
            
            
            
            if( $docente == 'x' ){
                echo "<p> Nombre docente: <input type='text' name=docente > 
                      <p> Especificar materias: <p> <input type='text' name='materia-especifica'>  

                        <p> Materia: (default: Todas) 
                        <p><input type='radio' name='materia' value='todas'>Todas
                        <p><input type='radio' name='materia' value='varias'>Varias

                    ";
            }else {
                
                echo "<p> Nombre docente: <input type='text' name=docente value='$docente'>";
                echo " <input type='hidden' name='entrada' value='$docente'>";
                $datos = $database->query("select distinct materia from docentes where nombre='$docente' 
                            and grupo='$grupo' order by materia desc ")->fetchAll();


                if( count( $datos) == 1 ){

                echo "<P> LA MATERIA ES:<h4 style='color:red'> ". $datos[0]['materia'] . " </h4>
                
                      <input type='hidden' name='materia-especifica' value='" . $datos[0]['materia'] . " '>  ";
                }elseif( count($datos)>1){
                    echo "<p>  Seleccionar materia: <br>";
                    $n = 0;
                    foreach( $datos as $ren ){
                        if($n == 0 ){

                        echo "<input type='radio' style='color:red' name='materia-especifica' value='$ren[materia]' checked>
                                 $ren[materia]<br> ";
                        }else {

                        echo "<input type='radio' style='color:red' name='materia-especifica' value='$ren[materia]'>
                                 $ren[materia]<br> ";
                        }
                        $n++;
                
                    }
                }
                
            }
            
        echo "
            <p>Asunto: 
            <p><input type='checkbox' name='o_justificado' value='Justificado'> Justificado
            <p><input type='checkbox' name='o_nojustificado' value='No Justificado'>No Justificado
            <p><input type='checkbox' name='o_enfermedad' value='Enfermedad'> Enfermedad
            <p><input type='checkbox' name='o_conexion' value='Fallas de Conectividad'> Fallas en la red 
            <p><input type='checkbox' name='o_zoom' value='Problemas con ZOOM '> Problemas con ZOOM 
            <p><input type='checkbox' name='o_microfono' value='Fallas de Micrófono'> Fallas de Micrófono
            <p><input type='checkbox' name='o_camara' value='Fallas de Cámara'> Fallas de Cámara
            <p><input type='checkbox' name='o_dispositivo' value='Fallas de dispositivo'> Fallas de dispositivo 
            <p><input type='checkbox' name='o_computadora' value='Problemas con computadora'> Problemas con computadora 
            <p><input type='checkbox' name='o_familiares' value='Motivos familiares'> Motivos familiares
            <p><input type='checkbox' name='o_malestar' value='Sentirse mal'>  Sentirse mal
            <p><input type='checkbox' name='o_imprevisto' value='Imprevisto'> Imprevisto 
            <p><input type='checkbox' name='o_evento' value='Evento familiar'> Evento familiar 
            <p><input type='checkbox' name='o_celebracion' value='Festejo' > Festejo 
            <p><input type='checkbox' name='o_covid' value='Relativo al COVID '> Relativo al COVID 
            <p><input type='checkbox' name='o_cita' value='Cita medica'> Cita medica
            <p><input type='checkbox' name='o_luz' value='No hay la luz'> No hay la luz 
    
            <input type='hidden' name='guardar' value='1'>

            <input type='hidden' name='modo' value='$modo'>
            <input type='hidden' name='entrada_2' value='$grupo'>
            <input type='hidden' name='entrada_3' value='$alumno'>
            <input type='hidden' name='busqueda' value='$busqueda'>
            <p>Especificar otras observaciones: <p> <input type='text' name='observaciones'>
            <p> <input type='submit' value='Enviar'>
        </form>    
                ";


            echo "</td>";

}

function guardar_datos($database, $arr){
    $alumno = $arr['entrada_3'] ;
    $grupo= $arr['entrada_2'] ;
    if($arr['fecha_inicial'] != ''){
        $fecha_inicial = $arr['fecha_inicial'];
    }else {


        $datetime = new DateTime();
        $timezone = new DateTimeZone('America/Tijuana');
        $datetime->setTimezone($timezone);
        $fecha_inicial=  $datetime->format('Y-m-d');
        //echo "<h5> Usando la fecha de hoy: $fecha_inicial</h5>";
        
    }

    $arr['materia-especifica'] == trim( $arr['materia-especifica']  );

    if(isset($arr['materia'])){
        $materia = $arr['materia'];
    }else{
        if($arr['materia-especifica'] !=''){
            $materia = $arr['materia-especifica'];
        }else {
            $materia = 'Todas';
         //   echo "<h5>Todas las materias</h5>";
        }
    }
    if(isset($arr['docente'] )){
        $docente = $arr['docente'];
    }else {
        if(isset($arr['entrada'])){
            $docente = $arr['entrada'];
        }else {
            $docente = " ";
        }        
    }
    $observaciones = '';
    foreach( $arr as $key =>$val){
        if($key[0] == 'o' && $key[1]=='_'){
//            echo "<p>OBS: $val";
            $observaciones .= $val . ". ";
        }

    }
    if( $arr['observaciones']!='' ){
        $observaciones .= $arr['observaciones'].'. ';
    }
    $observaciones = htmlspecialchars( $observaciones );
    $query = "insert into reportes ( nombre, grupo, fecha_inicial, materia, docente, observaciones)
                                    values 
                                    ('$alumno','$grupo', '$fecha_inicial', 
                            '$materia', '$docente', '$observaciones')";

    $res = $database->query($query);
    $codigo = $res->errorCode();
    if($codigo == '00000'){

        echo "<h5> Se ha guardado exitosamente el reporte </h5>";
    }else {
        echo "<h5> ERROR($codigo) al guardar el reporte... </h5>";
        echo "<p> Reportar lo siguiente: $query ";
    }
}



function procesar_entrada_chat($database, $entrada){

    $unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );



        $entrada = strtr( $entrada, $unwanted_array );

$cuerpo_texto = preg_replace("/[^A-Za-z0-9 ]/", ' ', $entrada);


//echo "<p> El texto es : $cuerpo_texto";
    $palabras_arreglo = preg_split( "/\s+|,/", $cuerpo_texto);

    $arr_alumnos= array();
    $arr_docentes= array();

    $N = count( $palabras_arreglo);
    for($i = 0 ; $i <$N ; $i++){
        $v = $palabras_arreglo[$i];
        if( $i < $N-1){
        $w = $palabras_arreglo[$i+1];
        }else{
            $w = 'xx';
        }

        //echo "<p>--->FRAGMENTO:  $v";

        if( $v == ''){
         //   echo " ... this is empty";
        }elseif
         ($v == ' '){
          //  echo " ... that's a space";
        }else{

            $data_al = $database->query("select * from alumnos where nombre REGEXP '\\\\b$v\\\\b' and nombre REGEXP '\\\\b$w\\\\b'
                                                ")->fetchAll();
           // print_r($data_al);
            $data_doc = $database->query("select distinct nombre from docentes where nombre 
                            REGEXP '\\\\b$v\\\\b' and nombre REGEXP '\\\\b$w\\\\b'
                                                ")->fetchAll();
            // print_r($data_doc);
            if( count($data_doc) > 0){

                foreach( $data_doc as $row){
                    if( !in_array( $row['nombre'], $arr_docentes) ){

                        array_push( $arr_docentes, $row['nombre']);
                    }
                }
            }


            if( count($data_al) > 0){

                foreach( $data_al as $row){

                    if( !in_array( $row['nombre'], $arr_alumnos) ){
                        array_push( $arr_alumnos, $row['nombre']);
                    }
                }
            }



        }

    }

    echo "<h4> Resultados: </h4> <p> Encontre estos docentes: ";
    foreach( $arr_docentes as $docente ){
        echo " <p> $docente";
    }
    echo " <p> Tambien a estos alumnos: ";
    foreach( $arr_alumnos as $alumno  ){
        echo " <p> $alumno";
    }
    
}


function exportar($database, $tipo, $fecha_inicial, $fecha_final){
    $salida = 'archivo_exportado.xlsx';
    $styleArray = [
        'font' => [
            'color'=> [ 'argb' => 'FFFFFFFF'],
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
        ],
        'borders' => [
            'outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => 'FF000000'],
            ],
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => [
                'argb' => 'FF2f5496',
            ],
        ],
    ];


    $styleArray_celda_interna = [
        'font' => [
            'color'=> [ 'argb' => 'FF000000'],
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
        ],
        'borders' => [
            'outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => 'FF000000'],
            ],
        ],
    ];


    $styleArray_celda_fondo_rojo = [
        'borders' => [
            'outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => 'FF000000'],
            ],
        ],

        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => [
                'argb' => 'FFFF0000',
            ],
        ],
    ];

    $styleArray_celda_fondo_amarillo = [
        'borders' => [
            'outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => 'FF000000'],
            ],
        ],

        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => [
                'argb' => 'FFFFFF00',
            ],
        ],
    ];


    if($tipo == 'concentrado_completo'){
        echo " <p> Concentrado.. completo" ;

        setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
        $dias_iniciales = ['D', "L", "M", "MX", 'J', 'V', 'S'];
        $date_i = new DateTime($fecha_inicial);
        $date_f = new DateTime($fecha_final);

        $format_date_str = 'Y-m-d';

        $interval_day = new DateInterval("P1D");


        $date_diff= $date_f->diff( $date_i );
        $cantidad_de_dias = $date_diff->format('%d');
        //echo " Hay esta cantidad de dias: ".$cantidad_de_dias;

        //echo "<br><br>";
        $spreadsheet = new Spreadsheet();

        $data_reportes = $database->query("select * from reportes where fecha_inicial >= '$fecha_inicial' 
                                    and fecha_inicial <= '$fecha_final'")->fetchAll();


        //print_r($data_reportes);

        $data_alumnos = $database->query("select * from alumnos order by grupo asc, nombre asc")->fetchAll();

        $grupo = '-1';
        //$sheet = $spreadsheet->getActiveSheet();
        $sheet_index = -1;
        $col_fechas = 3;



        // atravesar cada alumno
        foreach($data_alumnos as $k => $rows){

            $date_i = new DateTime($fecha_inicial);
           // echo "<P> $k - $rows[grupo] - $rows[nombre]";
            $reportes = array();
            ///////////////////////////////////////////////
            if ( $grupo != $rows['grupo'] ){   // hay un grupo nuevo, crear encabezado de hoja nueva
                $fechas_en_intervalo = array();
                $numero_lista = 1;
                $grupo=$rows['grupo'];
                $sheet_index++;
                $spreadsheet->createSheet();
                $spreadsheet->setActiveSheetIndex($sheet_index);
                $spreadsheet->getActiveSheet()->setTitle( $grupo);
                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(35);


                $arreglo_cols_dia = array();  // para almacenar las columnas en donde se debe marcar separacion
                                              // en donde se coloca dia de la semana                                
                //
                // el encabezado         
                //

                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('logo');
                $drawing->setDescription('logo');
                $drawing->setPath('iman.jpg'); // put your path and image here
                $drawing->setCoordinates('A1');
                $drawing->setOffsetX(10);
                $drawing->setOffsetY(10);
                $drawing->setWidth(100);
                $drawing->setWorksheet($spreadsheet->getActiveSheet());

                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('encabezado');
                $drawing->setDescription('encabezado');
                $drawing->setPath('encabezado.png'); // put your path and image here
                $drawing->setCoordinates('C1');
                $drawing->setOffsetX(10);
                $drawing->setOffsetY(10);
                //$drawing->setWidth(100);
                $drawing->setWorksheet($spreadsheet->getActiveSheet());

                $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(100);



                $row_number = 5;

                // los textos en las celdas del encabezado
                //

                $spreadsheet->setActiveSheetIndex($sheet_index)->setCellValueByColumnAndRow($col_fechas-1,
                             $row_number-3, "REPORTE DIARIO DE INCIDENCIAS -  GRUPO: $grupo" );

                $spreadsheet->setActiveSheetIndex($sheet_index)->setCellValueByColumnAndRow($col_fechas-1,
                             $row_number-2, "PREFECTURA" );


                $spreadsheet->setActiveSheetIndex($sheet_index)->mergeCells("D2:G2");

                $spreadsheet->setActiveSheetIndex($sheet_index)->setCellValueByColumnAndRow($col_fechas+1,
                             $row_number-3, "SEMANA DEL $fecha_inicial al $fecha_final " );


                $spreadsheet->getActiveSheet()->getCellByColumnAndRow(
                             9 ,
                             $row_number-3)->getStyle()->applyFromArray($styleArray_celda_fondo_rojo);

                $spreadsheet->getActiveSheet()->getCellByColumnAndRow(
                             9 ,
                             $row_number-2)->getStyle()->applyFromArray($styleArray_celda_fondo_amarillo);


                $spreadsheet->setActiveSheetIndex($sheet_index)->setCellValueByColumnAndRow( 10 ,
                             $row_number-3, "Alumnos con problema grave de asistencia" );

                $spreadsheet->setActiveSheetIndex($sheet_index)->setCellValueByColumnAndRow( 10 ,
                             $row_number-2, "Alumnos que no encienden cáara" );


                // 
                // los titulos de las columnas
                //
                $spreadsheet->setActiveSheetIndex($sheet_index)->setCellValueByColumnAndRow($col_fechas-1,
                             $row_number-1, "NOMBRE" );

                $dia_semana = $dias_iniciales[   $date_i->format('w')]; 
                $spreadsheet->setActiveSheetIndex($sheet_index)->setCellValueByColumnAndRow($col_fechas,
                             $row_number-1, "$dia_semana"  );


                // almacenar en el arreglo la posicion de la columna 
                array_push( $arreglo_cols_dia, $col_fechas);

                        $spreadsheet->setActiveSheetIndex($sheet_index)->setCellValueByColumnAndRow($col_fechas + 1,
                            $row_number-2,  $date_i->format('d-M-Y') );



                $spreadsheet->setActiveSheetIndex($sheet_index)->setCellValueByColumnAndRow($col_fechas+1,
                             $row_number-1, "ASUNTO");

                $spreadsheet->setActiveSheetIndex($sheet_index)->setCellValueByColumnAndRow($col_fechas+2,
                             $row_number-1, "MATERIA");

                $spreadsheet->setActiveSheetIndex($sheet_index)->setCellValueByColumnAndRow($col_fechas+3,
                             $row_number-1, "DOCENTE");

                /// los formatos
                //
                // el cuadro del numero
                $spreadsheet->getActiveSheet()->getCellByColumnAndRow(
                             $col_fechas-2,
                             $row_number-1)->getStyle()->applyFromArray($styleArray);
                    // el cuadro del nombre
                $spreadsheet->getActiveSheet()->getCellByColumnAndRow(
                             $col_fechas-1,
                             $row_number-1)->getStyle()->applyFromArray($styleArray);

                    //las fechas
                $spreadsheet->getActiveSheet()->getCellByColumnAndRow(
                             $col_fechas,
                             $row_number-1)->getStyle()->applyFromArray($styleArray);

                $spreadsheet->getActiveSheet()->getCellByColumnAndRow(
                             $col_fechas+1,
                             $row_number-1)->getStyle()->applyFromArray($styleArray);

                $spreadsheet->getActiveSheet()->getCellByColumnAndRow(
                             $col_fechas+2,
                             $row_number-1)->getStyle()->applyFromArray($styleArray);

                $spreadsheet->getActiveSheet()->getCellByColumnAndRow(
                             $col_fechas+3,
                             $row_number-1)->getStyle()->applyFromArray($styleArray);

                array_push( $fechas_en_intervalo, $date_i->format($format_date_str));
                $col_pos = 1;
                // el resto de las fechas
                for($n_dia = 1; $n_dia <= $cantidad_de_dias; $n_dia++) {
                       
                    $date_new= $date_i->add( new DateInterval("P1D") );
                    $dia_semana = $dias_iniciales[   $date_new->format('w')]; 
                    if( $dia_semana != 'D' && $dia_semana != 'S'){

                        $spreadsheet->setActiveSheetIndex($sheet_index)->setCellValueByColumnAndRow($col_fechas + $col_pos*4,
                            $row_number-1, "$dia_semana"  );

                        // almacenar en el arreglo la posicion de la columna 
                            array_push( $arreglo_cols_dia, $col_fechas + $col_pos*4 );


                        $spreadsheet->setActiveSheetIndex($sheet_index)->setCellValueByColumnAndRow($col_fechas + $col_pos*4+1,
                            $row_number-2,  $date_new->format('d-M-Y') );

                        array_push( $fechas_en_intervalo, $date_i->format($format_date_str));

                        $spreadsheet->setActiveSheetIndex($sheet_index)->setCellValueByColumnAndRow($col_fechas+1 + $col_pos*4,
                            $row_number-1, "ASUNTO");

                        $spreadsheet->setActiveSheetIndex($sheet_index)->setCellValueByColumnAndRow($col_fechas+2+ $col_pos*4,
                            $row_number-1, "MATERIA");

                        $spreadsheet->setActiveSheetIndex($sheet_index)->setCellValueByColumnAndRow($col_fechas+3+ $col_pos*4,
                            $row_number-1, "DOCENTE");


                        // los formatos para el resto tambien ...
                 $spreadsheet->getActiveSheet()->getCellByColumnAndRow(
                             $col_fechas + $col_pos*4,
                             $row_number-1)->getStyle()->applyFromArray($styleArray);

                $spreadsheet->getActiveSheet()->getCellByColumnAndRow(
                             $col_fechas+1 + $col_pos*4,
                             $row_number-1)->getStyle()->applyFromArray($styleArray);

                $spreadsheet->getActiveSheet()->getCellByColumnAndRow(
                             $col_fechas+2 + $col_pos*4,
                             $row_number-1)->getStyle()->applyFromArray($styleArray);

                $spreadsheet->getActiveSheet()->getCellByColumnAndRow(
                             $col_fechas+3 + $col_pos*4,
                             $row_number-1)->getStyle()->applyFromArray($styleArray);





                        $col_pos++;

                    }
                }


                //echo "<h1> This has this amount of columns: ";
                //echo $spreadsheet->getActiveSheet()->getHighestColumn();
               // echo "</h1>";
                // para cambiar el tamanio de cada columna a AutoSize
                for ($i = 'A'; $i != $spreadsheet->getActiveSheet()->getHighestColumn(); $i++) {
                 //   $spreadsheet->getColumnDimension($i)->setAutoSize(TRUE); 
                
                $spreadsheet->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
                } 




            }/////////// fin del if cuando hay un nuevo grupo



            // colocar nombre de alumno
            $spreadsheet->setActiveSheetIndex($sheet_index)->setCellValueByColumnAndRow(
                        2, 
                        $row_number,  
                        $rows['nombre']);


            // el numero de lista
            $spreadsheet->setActiveSheetIndex($sheet_index)->setCellValueByColumnAndRow(
                                                            1,
                                                            $row_number, $numero_lista); 
            $numero_lista++;

            // poner el formato al numero de lista
                $spreadsheet->getActiveSheet()->getCellByColumnAndRow(
                             1,
                             $row_number)->getStyle()->applyFromArray($styleArray);

            // poner el formato del nombre de la lista
                $spreadsheet->getActiveSheet()->getCellByColumnAndRow(
                             2,
                             $row_number)->getStyle()->applyFromArray($styleArray_celda_interna);






            //  crear arreglo reportes si el alumno tiene reportes
            $reportes = array();
            foreach( $data_reportes as $kr => $rep_rows){
                if( $rep_rows['nombre'] == $rows['nombre']){   // si hay una coincidencia entre el alumno y los reportes del grupo
                    if(!isset($reportes[$rep_rows['fecha_inicial']])){
                        $reportes[ $rep_rows['fecha_inicial']] = array( $rep_rows );// put in array all of them
                    }else {
                        $reportes_mismo_dia = $reportes[ $rep_rows['fecha_inicial']];

                        array_push( $reportes_mismo_dia, $rep_rows);
                        $reportes[ $rep_rows['fecha_inicial']] = $reportes_mismo_dia;
                    }
                }
            }
            // cada reporte esta en un arreglo para que haya la posibilidad de poner mas reportes por dia


            // si este alumno tiene mas de un dia con reporte o mas, colocarlos 
            if( count($reportes) > 0  ){

                //echo "<p> [grupo: $grupo]este alumno tiene # dias con reportes: " . count($reportes);
                //echo "<p> tiene reportes: " ;
                //

                ksort($reportes); // ordenar por fecha, las llaves *keys* son las fechas

                foreach(array_keys($reportes)  as $k => $val){
                    //echo "<p> $k - $val";
                    foreach( $reportes[$val] as $k_rep => $v_rep){
                        // echo "<p>------> ". $v_rep['observaciones'];
                    }
                }
               // echo "<pre>";
                //print_r($reportes);
               // echo "</pre>";

                ///////////
                //echo " <h1> important ------ </h1>";
                // print_r($fechas_en_intervalo);
                //echo " <h1> endimportant ------ </h1>";

                //echo "<h3> Estas son las fechas: </h3>"    ;
                //echo "<pre>";
               // print_r($fechas_en_intervalo);
                //echo "</pre>";

                // 
                // atravesar ls fechas
                //

                foreach( $fechas_en_intervalo as $index => $fecha_target){

                    //echo "<p> ~~~~~~~~~~~~~ index: $index , fecha_target : $fecha_target";
                    if(in_array(  $fecha_target, array_keys($reportes)  )){

                        $multipes_observaciones = $reportes[ $fecha_target ];
                        //print_r($multipes_observaciones);
                 //       echo "<br> <br>";
                        foreach( $multipes_observaciones as $k_rep => $v_rep  ){
                  //          echo " <p> BOING index $index <br>";
                            // 
                            // colocar la informacion del reporte en la posicion correspondiente a la fecha
                            //
                            $spreadsheet->setActiveSheetIndex($sheet_index)->setCellValueByColumnAndRow(
                                4 + $index*4,
                                $row_number, $v_rep['observaciones']);

                            $spreadsheet->setActiveSheetIndex($sheet_index)->setCellValueByColumnAndRow(
                                5 + $index*4,
                                $row_number, $v_rep['materia']);

                            $spreadsheet->setActiveSheetIndex($sheet_index)->setCellValueByColumnAndRow(
                                6 + $index*4,
                                $row_number, $v_rep['docente']);




                            // pintar la columna con el numero de lista de color
                            $spreadsheet->getActiveSheet()->getCellByColumnAndRow(
                                $col_fechas-2,
                                $row_number)->getStyle()->applyFromArray($styleArray);

                            // poner el formato del nombre de la lista (Los que no tienen nombre)
                            $spreadsheet->getActiveSheet()->getCellByColumnAndRow(
                                2,
                                $row_number)->getStyle()->applyFromArray($styleArray_celda_interna);

                            // tambien pintar todas las columnas de los dias por cada renglon

                            foreach($arreglo_cols_dia as   $columna_por_pintar){

                                $spreadsheet->getActiveSheet()->getCellByColumnAndRow(
                                    $columna_por_pintar,
                                    $row_number)->getStyle()->applyFromArray($styleArray);
                                // al igual que las celdas vacias o con texto interno con informacion...
                                $spreadsheet->getActiveSheet()->getCellByColumnAndRow(
                                    $columna_por_pintar+1,
                                    $row_number)->getStyle()->applyFromArray($styleArray_celda_interna);

                                $spreadsheet->getActiveSheet()->getCellByColumnAndRow(
                                    $columna_por_pintar+2,
                                    $row_number)->getStyle()->applyFromArray($styleArray_celda_interna);

                                $spreadsheet->getActiveSheet()->getCellByColumnAndRow(
                                    $columna_por_pintar+3,
                                    $row_number)->getStyle()->applyFromArray($styleArray_celda_interna);

                            }



                            $row_number++;  // incrementar renglon cada vez que haya un reporte
                        }
                    }                

                }

            }else{ // si no tiene reportes: incrementar una sola vez el renglon

             $row_number++; // incrementar renglon para ir al siguiente alumno
            }







                // tambien pintar todas las columnas de los dias por cada renglon

                foreach($arreglo_cols_dia as   $columna_por_pintar){

                    $spreadsheet->getActiveSheet()->getCellByColumnAndRow(
                     $columna_por_pintar,
                     $row_number-1)->getStyle()->applyFromArray($styleArray);
                
                    // al igual que las celdas vacias o con texto interno con informacion...
                    $spreadsheet->getActiveSheet()->getCellByColumnAndRow(
                     $columna_por_pintar+1,
                     $row_number-1)->getStyle()->applyFromArray($styleArray_celda_interna);

                    $spreadsheet->getActiveSheet()->getCellByColumnAndRow(
                     $columna_por_pintar+2,
                     $row_number-1)->getStyle()->applyFromArray($styleArray_celda_interna);

                    $spreadsheet->getActiveSheet()->getCellByColumnAndRow(
                     $columna_por_pintar+3,
                     $row_number-1)->getStyle()->applyFromArray($styleArray_celda_interna);


                }



            

        }
        // termin'o  de atrevsar cada alumno

       $spreadsheet->setActiveSheetIndex(0);


       $writer = new Xlsx($spreadsheet);
       $writer->save($salida);
    }elseif($tipo == 'concentrado_docentes'){

    }
    return $salida;
}


?>
