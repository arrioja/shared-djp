<?php

class Anular extends TPage
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
				$sql="SELECT COUNT(*) FROM declaracion where estatus='A'";
				$search=$this->CargarData($sql);
				$this->lbl_registros->Text="Registros Encontrados: ".$search[0]["COUNT(*)"];
	
				//Busqueda de Registros
				$session=new THttpSession;
				$session->open();
				$session['sql']="SELECT declaracion.id_declara, declaracion.fecha_declara, declaracion.folios, declaracion.sueldo_mensual, declaracion.patrimonio, declaracion.cedula_func, funcionario.nombres, funcionario.apellidos, organismo.nombre, declaracion.profesion, declaracion.cargo, declaracion.fecha_cargo FROM declaracion, organismo, funcionario WHERE declaracion.id_organismo = organismo.id_organismo AND declaracion.cedula_func = funcionario.cedula AND declaracion.estatus='A' ORDER BY declaracion.id_declara;";
				$search=$this->CargarData($session['sql']);
				$this->DataGrid->DataSource=$search;
				$this->DataGrid->dataBind();
				$session->close();
			}

			if (!empty($this->Request['id']))
			{
				$this->validador($this->Request['id']);
			}
		}   
	}

	public function Aceptar($sender,$param)
	{
		$id_declaracion=$this->txt_numdeclara->Text;
		$sql="UPDATE declaracion set estatus='A' where id_declara=$id_declaracion";
		$db = $this->Application->Modules['db1']->DbConnection;
		$db->Active=true;
		$resultado=$db->createCommand($sql)->execute();
		$db->Active=false;	
		$this->Response->redirect($this->Service->constructUrl('Anular'));
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

	public function Validator_numdeclara($sender,$param)
	{
		$this->validador($this->txt_numdeclara->Text);
	}

	public function validador($buscar)
	{
	
		$sql="SELECT COUNT(*) FROM declaracion where estatus<>'A' AND id_declara='$buscar'";
		$search=$this->CargarData($sql);
		if($search[0]['COUNT(*)']==1)
		{

			$this->txt_numdeclara->Text=$buscar;

			$sql="SELECT declaracion.id_declara, DATE_FORMAT(declaracion.fecha_declara, '%d/%m/%Y'), declaracion.folios, declaracion.sueldo_mensual, declaracion.patrimonio, declaracion.cedula_func, funcionario.nombres, funcionario.apellidos, organismo.nombre, declaracion.profesion, declaracion.cargo, declaracion.fecha_cargo FROM declaracion, organismo, funcionario WHERE declaracion.id_organismo = organismo.id_organismo AND declaracion.cedula_func = funcionario.cedula AND declaracion.id_declara=$buscar AND declaracion.estatus<>'A';";
			$search=$this->CargarData($sql);
			
			$this->chk_anulada->Enabled="True";
			$this->pnl_datos_temporales->Visible="true";
			$this->lbl_no_declaracion->Text=$search[0]["id_declara"];
			$this->lbl_no_cedula->Text=$search[0]["cedula_func"];
			$this->lbl_nombres->Text=$search[0]["nombres"];
			$this->lbl_apellidos->Text=$search[0]["apellidos"];
			$this->lbl_organismo->Text=$search[0]["nombre"];
			
			$this->btn_aceptar->Attributes->onclick='if(!confirm(\'¿Está seguro que desea anular la declaración?\')) return false;';

			$this->lbl_fecha_declaracion->Text=$search[0]["DATE_FORMAT(declaracion.fecha_declara, '%d/%m/%Y')"];
			$this->lbl_folio->Text=$search[0]["folios"];
			$this->lbl_cargo->Text=$search[0]["cargo"];
			$this->lbl_sueldo_mensual->Text=$search[0]["sueldo_mensual"];
			$this->lbl_patrimonio->Text=$search[0]["patrimonio"];
		}
		else
		{
			$this->ValActive->IsValid=false;
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

}
?>