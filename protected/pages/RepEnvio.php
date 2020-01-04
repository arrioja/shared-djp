<?php

class RepEnvio extends TPage
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
				$session->close();
			}
			
			if ((!empty($this->Request['inicio'])) and (!empty($this->Request['fin'])))
			{
				$inicio=$this->Request['inicio'];
				$fin=$this->Request['fin'];
				$consulta_sql="SELECT declaracion.cedula_func as 'Cedula', CONCAT(declaracion.tipo,'-',declaracion.id_declara) as 'No.Decl.', CONCAT(funcionario.apellidos, ' ', funcionario.nombres) as 'Apellidos y Nombres', organismo.nombre as 'Organismo', DATE_FORMAT(declaracion.fecha_declara, '%d/%m/%Y') as 'Fe.Recepc.', declaracion.cargo as 'Cargo', declaracion.folios as 'FL', declaracion.sueldo_mensual as 'S.Mensual', declaracion.patrimonio as 'Patrimonio' FROM declaracion, funcionario, organismo where fecha_declara > '$inicio' AND fecha_declara < '$fin' and declaracion.cedula_func = funcionario.cedula and declaracion.id_organismo = organismo.id_organismo and estatus<>'A' ORDER BY declaracion.id_declara;";
				$resultado_consulta=$this->CargarData($consulta_sql);
				$this->DataGrid->DataSource=$resultado_consulta;
				$this->DataGrid->dataBind();
				
				$fecha_inicio=new DateTime($inicio);
				$fecha_final=new DateTime($fin);
				$this->lbl_lapso->Text="DEL ".$fecha_inicio->format('d/m/Y')." Al ".$fecha_final->format('d/m/Y');
				
				$consulta_sql_firmante="SELECT * from firmante where activo='1';";
				$resultado_consulta_firmantes=$this->CargarData($consulta_sql_firmante);
				
				if(empty($resultado_consulta_firmantes))
				$this->alerta->Visible = true;
				
				$this->lbl_nombre_firmante->Text=strtoupper($resultado_consulta_firmantes[0]['nombre']);
				$this->lbl_cargo->Text=strtoupper($resultado_consulta_firmantes[0]['cargo']);
				
				
				
				
			}
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
}

?>
