<?php
session_start();
error_reporting(E_ALL ^ (E_WARNING | E_NOTICE));
setlocale(LC_ALL,"es_MX");
setlocale(LC_TIME, "spanish");
date_default_timezone_set('America/Mazatlan');
$fechaactual = date('d-m-Y H:i:s');
//require_once('conexion/conexion.php');
//require_once('tcpdf_include.php');
require_once('conexion/conexion.php');
require_once('tcpdf.php');

$idgrupoestadia= $_SESSION['idgrupoestadia'];
$numparcial=$_REQUEST['num'];
 $consulta1 	="SELECT idgrupoestadia, cveperiodo as claveperiodo, cvedocente as clavepersona, cvegrupo as clavegrupo, cvemateria as clavemateria, 
 concat(nombre,' ', IFNULL(apellidoPaterno,''),' ',IFNULL(apellidoMaterno,'')) as nombre,
 estatuscal,cvecarrera as clavecar,descripcion, asignatura,  
    (select nombreGrupo from grupos where grupos.clavecarrera = cvecarrera 
    and grupos.claveperiodo = cveperiodo and grupos.clave = cvegrupo) as grupo
     FROM grupoestadia inner join personas 
 on grupoestadia.cvedocente=personas.clave 
 inner join carrera on carrera.clave=grupoestadia.cvecarrera
 inner join asignaturas on asignaturas.clave = grupoestadia.cvemateria 
 where idgrupoestadia ='$idgrupoestadia'";
$rs1   		= $mysqli->query($consulta1) ;
  
   $filas1 	    = mysqli_fetch_assoc($rs1);
   $totalalumnos = mysqli_num_rows($rs1);
   $estatusfinal = $filas1['estatuscal'];

   $asignatura = $filas1['asignatura'];
   $clavemateria = $filas1['clavemateria'];
   $claveperiodo = $filas1['claveperiodo'];  
   $clavepersona = $filas1['clavepersona'];
   $descripcion = $filas1['descripcion'];
   $clavegrupo = $filas1['clavegrupo'];
   $nombre = $filas1['nombre'];
   $grupo = $filas1['grupo'];
   $clavecarrera = $filas1['clavecar'];
  // $numfecha="fechaparcial".$numparcial;
$fechaparcial = date("d-m-Y");
 $consulta2 	= "select distinct alumnos.matricula, nombre, concat(IFNULL(apellidoPaterno,''),' ',IFNULL(apellidoMaterno,'')) as apellido, 
 grupoestadia.estatuscal, email,calificacion.final,calificacion.calificacion, calificacion.cveasignatura, 
 grupoestadia.cvemateria, 
 (select distinct estatus from carreraalumno where periodoactual=alumnos.claveperiodo and matricula= alumnos.matricula) as estatus 
 from alumnos inner join personas on personas.clave = alumnos.matricula inner join carrera on alumnos.clavecar = carrera.clave 
 inner join grupoestadia on grupoestadia.cveperiodo = alumnos.claveperiodo and grupoestadia.cvecarrera = alumnos.clavecar 
 and grupoestadia.cvegrupo = alumnos.clavegrupo left join calificacion on alumnos.matricula = calificacion.matricula 
 and alumnos.claveperiodo = calificacion.cveperiodo and alumnos.clavecar = calificacion.cvecarrera and alumnos.clavegrupo = calificacion.cvegrupo 
 and grupoestadia.cvemateria = calificacion.cveasignatura 
 inner join estadiaalumno on grupoestadia.idgrupoestadia=estadiaalumno.idgrupoestadia and estadiaalumno.matricula=alumnos.matricula
  where grupoestadia.cveperiodo = '$claveperiodo' and carrera.clave = '$clavecarrera' and alumnos.clavegrupo ='$clavegrupo' 
  and grupoestadia.cvemateria = '$clavemateria' and grupoestadia.cvedocente=$clavepersona
   order by apellido, nombre";
    $rs2   		= $mysqli->query($consulta2) ;
    //$filas2 	    = mysqli_fetch_assoc($rs2);
    $totaldoc = mysqli_num_rows($rs2);	 


// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf = new TCPDF('P', 'mm', 'LETTER', true, 'UTF-8', false);

// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('KYRDA');
$pdf->setTitle('PARCIALES');
$pdf->setSubject('SIGU-UTSV');
$pdf->setKeywords('UTSV, SIGU');


// set default header data
//$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//$pdf->setHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->setFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->setFont('Helvetica', '', 6);
$pdf->SetMargins(7, 7, 7, false); 
$pdf->SetAutoPageBreak(true, 10); 
$pdf->setLineWidth(0.508);
$pdf->setFooterMargin();


// add a page


// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Print a table

// add a page

$pdf->AddPage();


$html = '<h2></h2>
<table border="1" >			
	
<tr>
	<td width="35%" Vspace="5px" height="70px" align="center" valign="middle" style="padding-top:20px" > <BR> <img src="images/ut.jpg" Vspace="5px"  align="center"  height="45px" border="0" /></td>
	<td width="30%"  height="70px"  align="center" valign="middle" padding:"15px" style="padding-bottom:20px"  > <BR> <BR> REPORTE PARCIAL <BR> UTSV-DAC-FO-02 <BR></td>
	<td width="35%" height="70px"  align="center" valign="middle" style="padding-top:20px" ><BR> <BR> </td>

	
</tr>
</table>
<table>
<tr>
		<th align="rigth" >'.$fechaparcial.'</th>
		
	</tr>
</table>';



$html =$html. '<h2></h2>
<table border:"hidden" cellspacing="1" cellpadding="2" style="font-size:10px; font-weight: bold;">
	<tr>
		<th colspan="4" align="center" >UNIVERSIDAD TECNOLOGICA DEL SURESTE DE VERACRUZ</th>
		
	</tr>

	<tr align="center">
		<th colspan="4" align="center">DEPARTAMENTO DE SERVICIOS ESCOLARES</th>
		
	</tr>

	<tr>
		<th  colspan="4"  align="center">ACTA DE CALIFICACIONES DEL CUATRIMESTRE: ENE-ABRIL 2023</th>
	
		
		
	</tr>

	<tr align="center" >
		<th colspan="2" align="right">NOMBRE DE LA MATERIA:'.$asignatura.'</th>		
		<th  colspan="2"align="left">GRUPO:'.$grupo.' </th>
	
		
	</tr>

	<tr align="center">
	<th colspan="2" align="right">CARRERA:</th>
	<th  colspan="2"align="left">'.$descripcion.' </th>
</tr>


<tr align="center">
<th colspan="2" align="right">NOMBRE DEL CATEDRATICO:</th>
<th colspan="2" align="left">'.$nombre.' </th>

</tr>
	
</table>';





$html =$html.'<h2></h2>
<table cellspacing="1" cellpadding="1" height="60px" style="padding-top:5px" style="padding-bottom:5px; font-size:8px;" table-layout: fixed; >
<tr>
<th style="border-collapse:collapse;" ></th>
<th  width="205" style="border-collapse:collapse;" ></th>
<th colspan="6" width="198" align="center" style="font-weight:bold;" border="1"  > PARCIALES</th>
<th rowspan="3" width="40" align="center"  style="font-weight:bold;" border="1"  > FINAL</th>
<th rowspan="3" width="80" align="center"  style="font-weight:bold;" border="1"  > EXTRAORDINARIO</th>
<th rowspan="3" width="50" align="center"  style="font-weight:bold;" border="1"  >ESPECIAL</th>
<th rowspan="3" width="60" align="center"  style="font-weight:bold;" border="1"  >CALIFICACION FINAL</th>



</tr>

<tr>
<th   style="border-collapse:collapse;" ></th>
<th  style="border-collapse:collapse;" ></th>
<th colspan="3" width="99" align="center"  style="font-weight:bold;"  border="1">ORDINARIO</th>
<th colspan="3" width="99" align="center"   style="font-weight:bold;" border="1" >RECUPERACION</th>

</tr>

<tr>
<th align="center" width="15"  style="font-weight:bold;" border="1" >No.</th>
<th align="center" width="50"  style="font-weight:bold;" border="1" >MATRICULA</th>
<th align="center" width="198"  style="font-weight:bold;"  border="1">ALUMNOS</th>
<th align="center" width="33"  style="font-weight:bold;" border="1" >I</th>
<th align="center" width="33" style="font-weight:bold;" border="1">II</th>
<th align="center" width="33" style="font-weight:bold;" border="1">III</th>
<th align="center" width="33" style="font-weight:bold;" border="1" >I</th>
<th align="center" width="33" style="font-weight:bold;" border="1" >II</th>
<th align="center" width="33" style="font-weight:bold;" border="1" >III</th>


</tr>';

$contador=0;
$ordinario1="";
$ordinario2="";
$ordinario3="";
	while($filas2 = mysqli_fetch_array($rs2))
		{
			$contador++;
			$matricula=$filas2['matricula'];

            $final="";$extra="";$especial="";
			$cuatrimestral="";
			if($estatusfinal =='CAPTURADO' || $estatusfinal =='VALIDADO'){
                $final=$filas2['final']; 
                if ($filas2['estatus']!=1){ //baja
                        $final="Baja";
                }
                if ($final==-1) $final="NP";		
			}else $cuatrimestral="NC";
            
			
			
			
            
			if($final>=8 and $final<=10)
				$estilocss="color:black";
				else $estilocss="color:red";
			$nombreCompleto=$filas2['apellido'].' '.$filas2['nombre'];
$html =$html.'
<tr height="25">
<th align="center" width="15" border="1" > '.$contador.'</th>
<th align="center" width="50" border="1" > '.$matricula.'</th>
<th align="left" width="198" border="1">'.$nombreCompleto.'</th>
<th align="center" width="33"  border="1"></th>
<th align="center" width="33" border="1" ></th>
<th align="center" width="33" border="1" ></th>
<th align="center" width="33" border="1" ></th>
<th align="center" width="33" border="1" ></th>
<th align="center" width="33" border="1" ></th>


<th align="center" width="40" border="1">'.$final.'</th>
<th align="center" width="80" border="1" ></th>
<th align="center" width="50"  border="1" ></th>
<th align="center" width="60" border="1" style="'.$estilocss.'" ><b>'.$final.'</b></th>
</tr>';
};	
$html =$html. '</table>';
$html =$html. '<h2></h2>
<table border="1" cellspacing="1" cellpadding="1" width="900" height="25" font-size:8px;>
	<tr style="font-size:8px;"  >
		<th width="265" height="15"  style="font-weight:bold;" >TOTAL DE ALUMNOS</th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="40"  height="15"></th>
		<th  width="80"  height="15"></th>
		<th  width="50"  height="15"></th>
		<th  width="60"  height="15"></th>
	
		
	</tr>

	<tr>
	    <th colspan=""  style="font-weight:bold;" >No. ALUMNOS ACREDITADOS</th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="40"  height="15"></th>
		<th  width="80"  height="15"></th>
		<th  width="50"  height="15"></th>
		<th  width="60"  height="15"></th>
	
    </tr>

	<tr>
	<th colspan=""  style="font-weight:bold;" >No. ALUMNOS NO ACREDITADOS</th>
	<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="40"  height="15"></th>
		<th  width="80"  height="15"></th>
		<th  width="50"  height="15"></th>
		<th  width="60"  height="15"></th>

    </tr>  

	<tr>
	<th colspan=""  style="font-weight:bold;" >No. ALUMNOS DESERTORES</th>
	<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="40"  height="15"></th>
		<th  width="80"  height="15"></th>
		<th  width="50"  height="15"></th>
		<th  width="60"  height="15"></th>

    </tr>  

	<tr>
	<th colspan=""  style="font-weight:bold;" >% ALUMNOS ACREDITADOS</th>
	<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="40"  height="15"></th>
		<th  width="80"  height="15"></th>
		<th  width="50"  height="15"></th>
		<th  width="60"  height="15"></th>

    </tr>  

	<tr>
	<th colspan=""  style="font-weight:bold;" >% ALUMNOS NO ACREDITADOS</th>
	<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="40"  height="15"></th>
		<th  width="80"  height="15"></th>
		<th  width="50"  height="15"></th>
		<th  width="60"  height="15"></th>
    </tr>  

	<tr>
	<th colspan=""  style="font-weight:bold;" >% DE DESERCION</th>
	<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="33"  height="15"></th>
		<th  width="40"  height="15"></th>
		<th  width="80"  height="15"></th>
		<th  width="50"  height="15"></th>
		<th  width="60"  height="15"></th>

    </tr>  


	

	
</table>';



$html =$html. '<h1></h1>

<BR> <BR> <BR> <BR>
<table  style="margin-left:10%;">
	<tr style="margin-left:10%;" >
	    <th width="30%" style="font-weight:bold; border-collapse:collapse; text-align:center; " ></th>
		<th width="40%" style="font-weight:bold; border-style:inset; text-align:center; font-size:10px; font-family:"Helvetica"; " >DOCENTE</th>
	    <th width="30%" style="font-weight:bold; border-collapse:collapse; text-align:center;" ></th>
	</tr>
</table>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Print some HTML Cells



// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Print a table

// add a page


// reset pointer to the last page
$pdf->SetMargins(7, 7, 7, false); 
$pdf->SetAutoPageBreak(true, 10); 
$pdf->SetFont('Helvetica', '', 7);
$pdf->lastPage();

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Print all HTML colors

// add a page


// add a page



// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('reporteparcial.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

?>