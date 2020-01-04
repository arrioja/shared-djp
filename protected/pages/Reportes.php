<?php


class Reportes extends TPage
{
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


	public function buscar_lapso($sender, $param)
	{
		$fecha_inicial=new DateTime($this->dat_desde->Text);
		$fecha_final=new DateTime($this->dat_hasta->Text);
		if (($fecha_final > $fecha_inicial) || ($fecha_final == $fecha_inicial))
			$this->Response->redirect($this->Service->constructUrl('RepEnvio',array('inicio'=>$fecha_inicial->format('Y-m-d'),'fin'=>$fecha_final->format('Y-m-d'))));
	}

	public function buscar_comprobante($sender, $param)
	{
		$num_declara=$this->txt_numdeclara->Text;
		$sql="select * from declaracion where id_declara=$num_declara";
		$busqueda=$this->CargarData($sql);
		
		if (empty($busqueda))
		{
			
			$this->validator->IsValid=false;
			$this->validator->ErrorMessage="El registro buscado no existe";
		}
		else
		{
			$this->Response->redirect($this->Service->constructUrl('RepDeclara',array('id'=>$this->txt_numdeclara->Text)));
		}
		
		
	}

	public function CargarData($consulta)
	{
		if(!empty($consulta))
		{
			$db = $this->Application->Modules['db1']->DbConnection;
			$db->Active=true;
			$resultado=$db->createCommand($consulta)->query();
			$busqueda=$resultado->readAll();
			$db->Active=false;
			return $busqueda;
		}
	}
}


?>
