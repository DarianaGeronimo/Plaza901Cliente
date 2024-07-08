<?php
session_start();
error_reporting(E_ALL ^ (E_WARNING | E_NOTICE));
date_default_timezone_set('America/Mazatlan');
$fechaactual = date('d-m-Y H:i:s');
require_once('tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf = new TCPDF('P', 'mm', 'LETTER', true, 'UTF-8', false);

// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Dariana');
$pdf->setTitle('Ejemplo');
$pdf->setSubject('Equipo');
$pdf->setKeywords('Ejemplo');


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
$html = ' 
 
<style>
body {
    font-family: Arial, sans-serif;
}
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 11pt;
    margin: 20px 0;
}
th, td {
    border: 1px solid #ddd;
    padding: 8px;
}
th {
    background-color: #4CAF50;
    color: white;
}
td {
    background-color: #f9f9f9;
}
tr:nth-child(even) td {
    background-color: #f2f2f2;
}
tr:hover td {
    background-color: #ddd;
}
.header {
    background-color: #4CAF50;
    color: white;
    text-align: center;
    font-size: 14pt;
    padding: 10px 0;
}
.subheader {
    background-color: #4CAF50;
    color: white;
    text-align: center;
    font-size: 12pt;
    padding: 8px 0;
}
.center {
    text-align: center;
}
</style>





<table border="1" style="font-size: 11pt">

<tr>

<td colspan="3" class="header">
	<img src="images/logoDari (1).jpg" width="70" height="60" Vspace="8px">

 
</td>
</tr>
<tr>
<td>
</td>
<td>Num. pedido</td>
<td>fecha inicio</td>
</tr>
<tr>
<td colspan="3" class="subheader">PEDIDO</td>
</tr>
<tr>
<td>direccionPedido</td>
<td colspan="2"></td>
</tr>
<tr>
<td>idDetallePedido</td>
<td colspan="2"></td>
</tr>
<tr>
<td>metodoPago</td>
<td colspan="2"></td>
</tr>
<tr>
<td colspan="3" class="center">Precio Producto:</td>
</tr>
<tr>
<td colspan="3" class="subheader">Detalle pedido</td>
</tr>
<tr>
<td>fechaEntrega</td>
<td>idDetallPedido</td>
</tr>
<tr>
<td>detalleProducto</td>
<td colspan="2"></td>
</tr>
<tr>
<td>cantidadProducto</td>
<td colspan="2"></td>
</tr>
<tr>
<td colspan="3" class="center">totalPedido :</td>
</tr>
</table>

';







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


// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('preboleta'.$claveperiodo.'_'.$matricula, 'I');

//============================================================+
// END OF FILE
//============================================================+

?>
