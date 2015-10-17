<?
//CONFIGURA��ES DO BD MYSQL                               
$servidor    =  "localhost";                              
$usuario     =  "root";                                   
$senha       =  "";                                       
$bd          =  "otrs";                               
//T�TULO DO RELAT�RIO                                     
$titulo      =  "Exemplo de Gera��o de PDF";                 
//LOGO QUE SER� COLOCADO NO RELAT�RIO                     
$imagem      =  "logo_mc.png";                      
//ENDERE�O DA BIBLIOTECA FPDF                             
$end_fpdf    =  "/Applications/XAMPP/xamppfiles/htdocs/GerarPDF/fpdf";              
//NUMERO DE RESULTADOS POR P�GINA                         
$por_pagina  =  13;                                       
//ENDERE�O ONDE SER� GERADO O PDF                         
$end_final   =  "/Applications/XAMPP/xamppfiles/htdocs/GerarPDF/MC.pdf";  
//TIPO DO PDF GERADO                                      
//F-> SALVA NO ENDERE�O ESPECIFICADO NA VAR END_FINAL     
$tipo_pdf    =  "F";                                      

//CONECTA COM O MYSQL
$conn   =   mysql_connect($servidor, $usuario, $senha);
$db     =   mysql_select_db($bd, $conn);    
$sql    =   mysql_query("SELECT A.ID, A.NOME, A.ASSUNTO FROM colunistas A", $conn);
$row    =   mysql_num_rows($sql);           

//VERIFICA SE RETORNOU ALGUMA LINHA
if(!$row) { echo "N�o retornou nenhum registro"; die; }

//CALCULA QUANTAS P�GINAS V�O SER NECESS�RIAS
$paginas   =  ceil($row/$por_pagina);        

//PREPARA PARA GERAR O PDF
define("FPDF_FONTPATH", "$end_fpdf/font/");
require_once("$end_fpdf/fpdf.php");        
$pdf   =   new FPDF();

//INICIALIZA AS VARI�VEIS
$linha_atual =  0;
$inicio      =  0;

//P�GINAS
for($x=1; $x<=$paginas; $x++) {
   //VERIFICA
   $inicio      =  $linha_atual;
   $fim         =  $linha_atual + $por_pagina;
   if($fim > $row) $fim = $row;
   
   $pdf->Open();                    
   $pdf->AddPage();                 
   $pdf->SetFont("Arial", "B", 10); 
   
   //MONTA O CABE�ALHO              
   $pdf->Image($imagem, 0, 2);
   $pdf->Ln(2);
   $pdf->Cell(185, 8, "P�gina $x de $paginas", 0, 0, 'R');          
   
   //QUEBRA DE LINHA
   $pdf->Ln(20);
   
   $pdf->Cell(15, 8, "", 1, 0, 'C');          
   $pdf->Cell(85, 8, "COLUNISTA", 1, 0, 'L'); 
   $pdf->Cell(85, 8, "ASSUNTO", 1, 1, 'L');   
   
   //EXIBE OS REGISTROS      
   for($i=$inicio; $i<$fim; $i++) {
      $pdf->Cell(15, 8, mysql_result($sql, $i, "ID"), 1, 0, 'C');      
      $pdf->Cell(85, 8, mysql_result($sql, $i, "NOME"), 1, 0, 'L');    
      $pdf->Cell(85, 8, mysql_result($sql, $i, "ASSUNTO"), 1, 1, 'L'); 
	  $linha_atual++;
   }//FECHA FOR(REGISTROS - i)
}//FECHA FOR(PAGINAS - x)

//SAIDA DO PDF
$pdf->Output("$end_final", "$tipo_pdf");
?>