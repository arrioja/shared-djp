<?php
class IncluirOrga extends TPage
{
	public function onLoad($param)
	{
		if (!$this->IsPostBack) 
		{
		$session=new THttpSession;
		$session->open();
			if ($session['logged']!="1")
			{
			$this->Response->redirect($this->Service->constructUrl('Login'));
			}
		$session->close();
		$sql="SELECT AUTO_INCREMENT FROM information_schema.tables WHERE table_name = 'organismo'";
		$search=$this->CargarData($sql);
		$this->txt_codorg->Text=$search[0]["AUTO_INCREMENT"];
		}
		
	}
	public function CargarData($consulta)
	{
		$db = $this->Application->Modules['db1']->DbConnection;
		$db->Active=true;
		$resultado=$db->createCommand($consulta)->query();
		$busqueda=$resultado->readAll();
		$db->Active=false;
		return $busqueda;
	}
	
	public function Aceptar($param)
	{
	}
	

}
?>
