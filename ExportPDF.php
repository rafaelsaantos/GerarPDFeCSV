<?php         
$servidor    =  "localhost";                              
$usuario     =  "root";                                   
$senha       =  "";                                       
$bd          =  "otrs";                               
                                 
$titulo      =  "Exemplo de Geração de PDF";                 
$imagem      =  "logo_mc.png";                      
$end_fpdf    =  "/Applications/XAMPP/xamppfiles/htdocs/GerarPDFeCSV/lib-fpdf";              
$por_pagina  =  13;                                             
$end_final   =  "/Applications/XAMPP/xamppfiles/htdocs/GerarPDF/MC.pdf";  
$tipo_pdf    =  "F";                                      

$conn   =   mysql_connect($servidor, $usuario, $senha);
$db     =   mysql_select_db($bd, $conn);    
$sql    =   mysql_query("SELECT A.ID, A.NOME, A.ASSUNTO FROM colunistas A", $conn);
$row    =   mysql_num_rows($sql);           

if(!$row) { echo "Não retornou nenhum registro"; die; }

$paginas   =  ceil($row/$por_pagina);        

define("FPDF_FONTPATH", "$end_fpdf/font/");
require_once("$end_fpdf/fpdf.php");        
$pdf   =   new FPDF();

$linha_atual =  0;
$inicio      =  0;

for($x=1; $x<=$paginas; $x++) {
   $inicio      =  $linha_atual;
   $fim         =  $linha_atual + $por_pagina;
   if($fim > $row) $fim = $row;
   
   $pdf->Open();                    
   $pdf->AddPage();                 
   $pdf->SetFont("Arial", "B", 10); 
            
   $pdf->Image($imagem, 0, 2);
   $pdf->Ln(2);
   $pdf->Cell(185, 8, "Página $x de $paginas", 0, 0, 'R');          
   
   $pdf->Ln(20);
   
   $pdf->Cell(15, 8, "", 1, 0, 'C');          
   $pdf->Cell(85, 8, "COLUNISTA", 1, 0, 'L'); 
   $pdf->Cell(85, 8, "ASSUNTO", 1, 1, 'L');   
   
   for($i=$inicio; $i<$fim; $i++) {
      $pdf->Cell(15, 8, mysql_result($sql, $i, "ID"), 1, 0, 'C');      
      $pdf->Cell(85, 8, mysql_result($sql, $i, "NOME"), 1, 0, 'L');    
      $pdf->Cell(85, 8, mysql_result($sql, $i, "ASSUNTO"), 1, 1, 'L'); 
	  $linha_atual++;
   }
}

$pdf->Output("$end_final", "$tipo_pdf");
?>