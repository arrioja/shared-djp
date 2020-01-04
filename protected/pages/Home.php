<?php

/*require_once('http://192.168.1.1/~amarin/webcentra/fpdf/fpdf.php');*/
class Home extends TPage
{
	public function pdf($param)
	{

$pdf=new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,400,'¡Hola, Mundo!');
$pdf->Output();


	}
    
	public function onLoad($param)
	{
		if (!$this->IsPostBack) 
		{
		
		$session=new THttpSession;
		$session->open();
		$session->close();
			if ($session['logged']!="1")
			{
			$this->Response->redirect($this->Service->constructUrl('Login'));
			}
		
		}
    }


}


?>
