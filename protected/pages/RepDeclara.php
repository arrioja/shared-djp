<?php

class RepDeclara extends TPage
{
	public function pdf($param)
	{
	}
	
    
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
			
			if (!empty($this->Request['id']))
			{
				$iddeclara=$this->Request['id'];
				$sql="select declaracion.id_declara, declaracion.tipo, declaracion.cedula_func, DATE_FORMAT(declaracion.fecha_declara, '%d/%m/%Y'), declaracion.id_usuario, declaracion.folios, declaracion.observacion, declaracion.anexos, declaracion.cedula_cons, declaracion.nombre, funcionario.nombres, funcionario.apellidos from declaracion, funcionario where funcionario.cedula = declaracion.cedula_func and declaracion.id_declara=$iddeclara";
				$search=$this->CargarData($sql);

				//Se cargan los datos en el formulario
				$this->rep_frecepcion->Text=$search[0]["DATE_FORMAT(declaracion.fecha_declara, '%d/%m/%Y')"];
				$this->rep_ceddeclarante->Text=$search[0]["cedula_func"];
				$this->rep_iddeclara->Text="0".$search[0]["id_declara"];
				$this->rep_nombreape->Text=$search[0]["apellidos"]." ".$search[0]["nombres"];
				$this->rep_numfolio->Text=$search[0]["folios"];
				$this->rep_observaciones->Text=$search[0]["observacion"];
				$this->rep_numanexo->Text=$search[0]["anexos"];
				
				if (!empty($search[0]["cedula_cons"]))
				{
				$this->rep_consignomape->Text=$search[0]["nombre"];
				$this->rep_cedconsigante->Text=$search[0]["cedula_cons"];
				}
				else
				{
				$this->rep_consignomape->Text=$search[0]["apellidos"]." ".$search[0]["nombres"];
				$this->rep_cedconsigante->Text=$search[0]["cedula_func"];
				}
				
				// Se carga el campo con la información del usuario que registro la declaración
				$idusuario=$search[0]["id_usuario"];
				$sql="select * from usuarios where id_usuario = $idusuario";
				$resultado=$this->CargarData($sql);
				$this->rep_funcrecep->Text=$resultado[0]["apellidos"]." ".$resultado[0]["nombre"];

				// Se hace la selección del tipo de declaracion actualmente en vista
				switch($search[0]["tipo"])
					{
					case "I":
					$this->rep_estatus->Text="Inclusión";
					break;
					case "C":
					$this->rep_estatus->Text="Cese";
					break;
					case "A":
					$this->rep_estatus->Text="Actualización";
					break;
					}

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
