<?php

class Busqueda extends TPage
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
			else
			{
			$session->close();
			//Conteo Registros
			$sql="SELECT COUNT(*) FROM declaracion where estatus<>'A'";
			$search=$this->CargarData($sql);
			$this->lbl_registros->Text="Registros Encontrados: ".$search[0]["COUNT(*)"];
   
			//Busqueda de Registros
			$session=new THttpSession;
			$session->open();
			$session['sql']="SELECT declaracion.id_declara, declaracion.fecha_declara, declaracion.folios, declaracion.sueldo_mensual, declaracion.patrimonio, declaracion.cedula_func, funcionario.nombres, funcionario.apellidos, organismo.nombre, declaracion.profesion, declaracion.cargo, declaracion.fecha_cargo FROM declaracion, organismo, funcionario WHERE declaracion.id_organismo = organismo.id_organismo AND declaracion.cedula_func = funcionario.cedula AND declaracion.estatus<>'A';";
			$search=$this->CargarData($session['sql']);
			$this->DataGrid->DataSource=$search;
			$this->DataGrid->dataBind();
			$session->close();
			}
		}   
	}
	public function editItem($sender,$param)
	{
		$id=$this->DataGrid->DataKeys[$param->Item->ItemIndex];
		$this->Response->redirect($this->Service->constructUrl('IncluirDecla',array('id'=>$id)));
	}
	
	public function anularItem($sender,$param)
	{
		$id=$this->DataGrid->DataKeys[$param->Item->ItemIndex];
		$this->Response->redirect($this->Service->constructUrl('Anular',array('id'=>$id)));
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

	public function changePage($sender,$param)
	{
			$this->DataGrid->CurrentPageIndex=$param->NewPageIndex;
			$session=new THttpSession;
			$session->open();
			$search=$this->CargarData($session['sql']);
			$this->DataGrid->DataSource=$search;
			$this->DataGrid->dataBind();
			$session->close();
	}

	public function pagerCreated($sender,$param)
	{
		$param->Pager->Controls->insertAt(0,'Pagina: ');
	}

	public function BuscarReg($sender,$param)
	{
		$valor=$this->txt_cadena->Text;
		
		if($this->rad_cedula->Checked)
		{

			if (!intval($valor)=="0") 
			{
			
				//Conteo Registros
				$sql="SELECT COUNT(*) FROM declaracion where cedula_func = $valor AND estatus<>'A'";
				$search=$this->CargarData($sql);
				$this->lbl_registros->Text="Registros Encontrados: ".$search[0]["COUNT(*)"];
		
				//Busqueda de Registros
				$session=new THttpSession;
				$session->open();
				$session['sql']="SELECT declaracion.id_declara, declaracion.fecha_declara, declaracion.folios, declaracion.sueldo_mensual, declaracion.patrimonio, declaracion.cedula_func, funcionario.nombres, funcionario.apellidos, organismo.nombre, declaracion.profesion, declaracion.cargo, declaracion.fecha_cargo FROM declaracion, organismo, funcionario WHERE declaracion.id_organismo = organismo.id_organismo AND declaracion.cedula_func = funcionario.cedula AND funcionario.cedula = $valor AND declaracion.estatus<>'A'";
				$search=$this->CargarData($session['sql']);
				$this->DataGrid->DataSource=$search;
				$this->DataGrid->dataBind();
				$session->close();

			}

		}
		
		if($this->rad_nombre->Checked)
		{
		//Conteo Registros
		$sql="SELECT COUNT(*) FROM funcionario where nombres like '%$valor%' AND estatus<>'A'";
		$search=$this->CargarData($sql);
		$this->lbl_registros->Text="Registros Encontrados: ".$search[0]["COUNT(*)"];
		
		//Busqueda de Registros
		$session=new THttpSession;
		$session->open();
		$session['sql']="SELECT declaracion.id_declara, declaracion.fecha_declara, declaracion.folios, declaracion.sueldo_mensual, declaracion.patrimonio, declaracion.cedula_func, funcionario.nombres, funcionario.apellidos, organismo.nombre, declaracion.profesion, declaracion.cargo, declaracion.fecha_cargo FROM declaracion, organismo, funcionario WHERE declaracion.id_organismo = organismo.id_organismo AND declaracion.cedula_func = funcionario.cedula AND funcionario.nombres like '%$valor%' AND declaracion.estatus<>'A' ";
		$search=$this->CargarData($session['sql']);
		$this->DataGrid->DataSource=$search;
		$this->DataGrid->dataBind();
		$session->close();
		
		}

		if($this->rad_apellidos->Checked)
		{
		//Conteo Registros
		$sql="SELECT COUNT(*) FROM funcionario where apellidos like '%$valor%' AND estatus<>'A'";
		$search=$this->CargarData($sql);
		$this->lbl_registros->Text="Registros Encontrados: ".$search[0]["COUNT(*)"];
		
		//Busqueda de Registros
		$session=new THttpSession;
		$session->open();
		$session['sql']="SELECT declaracion.id_declara, declaracion.fecha_declara, declaracion.folios, declaracion.sueldo_mensual, declaracion.patrimonio, declaracion.cedula_func, funcionario.nombres, funcionario.apellidos, organismo.nombre, declaracion.profesion, declaracion.cargo, declaracion.fecha_cargo FROM declaracion, organismo, funcionario WHERE declaracion.id_organismo = organismo.id_organismo AND declaracion.cedula_func = funcionario.cedula AND funcionario.apellidos like '%$valor%' AND declaracion.estatus<>'A'";
		$search=$this->CargarData($session['sql']);
		$this->DataGrid->DataSource=$search;
		$this->DataGrid->dataBind();
		$session->close();
		}
		
		if($this->rad_declara->Checked)
		{
			if (!intval($valor)=="0") 
			{
				//Conteo Registros
				$sql="SELECT COUNT(*) FROM declaracion where id_declara = $valor AND estatus<>'A'";
				$search=$this->CargarData($sql);
				$this->lbl_registros->Text="Registros Encontrados: ".$search[0]["COUNT(*)"];

				//Busqueda de Registros
				$session=new THttpSession;
				$session->open();
				$session['sql']="SELECT declaracion.id_declara, declaracion.fecha_declara, declaracion.folios, declaracion.sueldo_mensual, declaracion.patrimonio, declaracion.cedula_func, funcionario.nombres, funcionario.apellidos, organismo.nombre, declaracion.profesion, declaracion.cargo, declaracion.fecha_cargo FROM declaracion, organismo, funcionario WHERE declaracion.id_organismo = organismo.id_organismo AND declaracion.cedula_func = funcionario.cedula AND id_declara = $valor AND declaracion.estatus<>'A'";
				$search=$this->CargarData($session['sql']);
				$this->DataGrid->DataSource=$search;
				$this->DataGrid->dataBind();
				$session->close();
			}
		}
	}
}
?>