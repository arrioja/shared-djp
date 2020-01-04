<?php
class IncluirDecla extends TPage
{
	public function onLoad($param)
	{
		if (!$this->IsPostBack) 
		{
		// Si no se pasa nungun parametro por ID se realiza el funcionamiento normal, deshabilitacion de campos para confirmar con la cedula 
		// y el llamado del No. cercano de Declaracion
		$session=new THttpSession;
		$session->open();
		$session->close();
			if ($session['logged']!="1")
			{
			$this->Response->redirect($this->Service->constructUrl('Login'));
			}
			
		$sql="SELECT AUTO_INCREMENT FROM information_schema.tables WHERE table_name = 'declaracion'";
		$search=$this->CargarData($sql);
		$this->txt_numdecla->Text=$search[0]["AUTO_INCREMENT"];
		
		
		//Se habilitan los campos y se traen los datos solo si se acompaña el IncluirDeclara de algun parametro
		if (!empty($this->Request['id']))
		{
		$iddeclara=$this->Request['id'];
		$sql="select declaracion.id_declara, declaracion.tipo, declaracion.cedula_func, DATE_FORMAT(declaracion.fecha_declara, '%d/%m/%Y'), declaracion.profesion, declaracion.id_organismo, declaracion.cargo, DATE_FORMAT(declaracion.fecha_cargo, '%d/%m/%Y'), declaracion.estatus, declaracion.direccion, declaracion.division, declaracion.departamento, declaracion.folios, declaracion.sueldo_mensual, declaracion.patrimonio, declaracion.id_usuario, declaracion.observacion, declaracion.anexos, declaracion.cedula_cons, declaracion.nombre, funcionario.nombres, funcionario.apellidos, funcionario.dirhab, funcionario.telefono, funcionario.profesion from declaracion, funcionario where funcionario.cedula = declaracion.cedula_func and declaracion.id_declara=$iddeclara";
		$search=$this->CargarData($sql);

		// Habilitando los campos para la escritura
		$this->txt_cedula->Enabled="false";
		$this->rad_tipodeclara_cese->Enabled="true";
		$this->rad_tipodeclara_actu->Enabled="true";
		$this->rad_tipodeclara_incl->Enabled="true";
		$this->dta_fechadeclara->Enabled="true";
		$this->txt_nombres->Enabled="true";
		$this->txt_apellidos->Enabled="true";
		$this->ddl_codorg->Enabled="true";
		$this->txt_codorg->Enabled="true";
		$this->txt_dirhab->Enabled="true";
		$this->txt_telefono->Enabled="true";
		$this->txt_profesion->Enabled="true";
		$this->txt_direccion->Enabled="true";
		$this->txt_division->Enabled="true";
		$this->txt_departamento->Enabled="true";
		$this->txt_cargo->Enabled="true";
		$this->dta_fcargo->Enabled="true";
		$this->txt_dirhab->Enabled="true";
		$this->txt_telefono->Enabled="true";
		$this->txt_folio->Enabled="true";
		$this->txt_sueldomen->Enabled="true";
		$this->txt_patri->Enabled="true";
		$this->txt_observa->Enabled="true";
		$this->rad_consigna_si->Enabled="true";
		$this->rad_consigna_no->Enabled="true";
		
		//Se cargan los datos en el formulario
		$this->dta_fechadeclara->Text=$search[0]["DATE_FORMAT(declaracion.fecha_declara, '%d/%m/%Y')"];
		$this->txt_cedula->Text=$search[0]["cedula_func"];
		$this->txt_numdecla->Text=$search[0]["id_declara"];
		$this->txt_nombres->Text=$search[0]["nombres"];
		$this->txt_apellidos->Text=$search[0]["apellidos"];
		$this->txt_dirhab->Text=$search[0]["dirhab"];
		$this->txt_telefono->Text=$search[0]["telefono"];
		$this->txt_profesion->Text=$search[0]["profesion"];
		$this->txt_codorg->Text=$search[0]["id_organismo"];
		$this->txt_direccion->Text=$search[0]["direccion"];
		$this->txt_division->Text=$search[0]["division"];
		$this->txt_departamento->Text=$search[0]["departamento"];
		$this->txt_cargo->Text=$search[0]["cargo"];
		$this->dta_fcargo->Text=$search[0]["DATE_FORMAT(declaracion.fecha_cargo, '%d/%m/%Y')"];
		$this->txt_folio->Text=$search[0]["folios"];
		$this->txt_sueldomen->Text=$search[0]["sueldo_mensual"];
		$this->txt_patri->Text=$search[0]["patrimonio"];
		$this->txt_observa->Text=$search[0]["observacion"];
		
		// Se carga la seleccion de Organismos y se le asigna el organismo correspondiente a la declaracion
		$sql="select * from organismo order by nombre";
		$result=$this->CargarData($sql);
		$this->ddl_codorg->DataSource=$result;
		$this->ddl_codorg->dataBind();
		$this->txt_codorg->Text=$search[0]["id_organismo"];
		$this->ddl_codorg->SelectedValue=$this->txt_codorg->Text;
		
		// Se carga el campo con la información del usuario que registro la declaración
		$idusuario=$search[0]["id_usuario"];
		$sql="select * from usuarios where id_usuario = $idusuario";
		$resultado=$this->CargarData($sql);
		$this->txt_perauto->Text=$resultado[0]["nombre"]." ".$resultado[0]["apellidos"];
		
		// Se hace la selección del tipo de declaracion actualmente en vista
		switch($search[0]["tipo"])
			{
			case "I":
			$this->rad_tipodeclara_incl->Checked="true";
			break;
			case "C":
			$this->rad_tipodeclara_cese->Checked="true";
			break;
			case "A":
			$this->rad_tipodeclara_actu->Checked="true";
			break;
			}
		
		if (empty($search[0]["cedula_cons"]))
			{
			$this->rad_consigna_no->Checked="true";
			}
		else
			{
			$this->rad_consigna_si->Checked="true";
			
			$this->txt_cedcon->Enabled="true";
			$this->txt_nomcon->Enabled="true";
			$this->txt_numanexo->Enabled="true";
					
			$this->txt_cedcon->Text=$search[0]["cedula_cons"];
			$this->txt_nomcon->Text=$search[0]["nombre"];
			$this->txt_numanexo->Text=$search[0]["anexos"];
			
			}

		}

		}
	}
	

	
	public function ddl_codorgChanged($sender,$param)
	{
		$this->txt_codorg->Text=$this->ddl_codorg->SelectedValue;
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
	
	public function CambioCodOrg($consulta)
	{
		$valor=$this->txt_codorg->Text;
		$sql="select * from organismo where id_organismo = $valor";
		$result=$this->CargarData($sql);
   
		if(empty($result))
			{
			$this->vcodorg->IsValid=false;
			$this->txt_codorg->Text="1";
			$this->ddl_codorg->SelectedValue=$this->txt_codorg->Text;
			}
		else
			{
			$this->ddl_codorg->SelectedValue=$this->txt_codorg->Text;
			}
	}
    
	public function Aceptar($param)
	{
	}
	
	public function CambioCedula($param)
	{
		// Se busca la Información del funcionario si existe en la base de datos
		$cedula=$this->txt_cedula->Text;
		$sql="select * from funcionario where cedula = $cedula";
		$search=$this->CargarData($sql);
		$this->txt_nombres->Text=$search[0]["nombres"];
		$this->txt_apellidos->Text=$search[0]["apellidos"];
		$this->txt_dirhab->Text=$search[0]["dirhab"];
		$this->txt_telefono->Text=$search[0]["telefono"];
		$this->txt_profesion->Text=$search[0]["profesion"];

		// Se llena el DropDownList con la información de los Organismos y se pre-selecciona el primer registro.
		$sql="select * from organismo order by nombre";
		$result=$this->CargarData($sql);
		$this->ddl_codorg->DataSource=$result;
		$this->ddl_codorg->dataBind();
		$this->txt_codorg->Text="1";
		$this->ddl_codorg->SelectedValue=$this->txt_codorg->Text;
		
		$this->rad_tipodeclara_incl->Checked="true";
		$this->rad_consigna_no->Checked="true";

		if (empty($search))
		{
		// si la busqueda de los datos del funcionario no arrojo ningun resultado, se abren los campos para agregar los datos.
		
		$this->rad_tipodeclara_cese->Enabled="true";
		$this->rad_tipodeclara_actu->Enabled="true";
		$this->rad_tipodeclara_incl->Enabled="true";
		
		$this->dta_fechadeclara->Enabled="true";
		
		$this->txt_nombres->Enabled="true";
		$this->txt_apellidos->Enabled="true";
		
		$this->ddl_codorg->Enabled="true";
		$this->txt_codorg->Enabled="true";
		
		$this->txt_dirhab->Enabled="true";
		$this->txt_telefono->Enabled="true";
		$this->txt_profesion->Enabled="true";
		
		$this->txt_direccion->Enabled="true";
		$this->txt_division->Enabled="true";
		$this->txt_departamento->Enabled="true";

		$this->txt_cargo->Enabled="true";
		$this->dta_fcargo->Enabled="true";
		
		$this->txt_dirhab->Enabled="true";
		$this->txt_telefono->Enabled="true";
		$this->txt_folio->Enabled="true";

		$this->txt_sueldomen->Enabled="true";
		$this->txt_patri->Enabled="true";
		$this->txt_observa->Enabled="true";
		
		$this->rad_consigna_si->Enabled="true";
		$this->rad_consigna_no->Enabled="true";
		
		}
		else
		{
		$this->rad_tipodeclara_cese->Enabled="true";
		$this->rad_tipodeclara_actu->Enabled="true";
		$this->rad_tipodeclara_incl->Enabled="true";

		$this->dta_fechadeclara->Enabled="true";

		$this->txt_nombres->Enabled="false";
		$this->txt_apellidos->Enabled="false";

		$this->ddl_codorg->Enabled="true";
		$this->txt_codorg->Enabled="true";

		$this->txt_dirhab->Enabled="true";
		$this->txt_telefono->Enabled="true";
		$this->txt_profesion->Enabled="true";

		$this->txt_direccion->Enabled="true";
		$this->txt_division->Enabled="true";
		$this->txt_departamento->Enabled="true";

		$this->txt_cargo->Enabled="true";
		$this->dta_fcargo->Enabled="true";

		$this->txt_dirhab->Enabled="true";
		$this->txt_telefono->Enabled="true";
		$this->txt_folio->Enabled="true";

		$this->txt_sueldomen->Enabled="true";
		$this->txt_patri->Enabled="true";
		$this->txt_observa->Enabled="true";

		$this->rad_consigna_si->Enabled="true";
		$this->rad_consigna_no->Enabled="true";
		}
		
	}
	
		public function RadioSeleccionado($sender,$param)
		{
		if($this->rad_tipodeclara_cese->Checked)
			$this->lab_fcargo->Text="Fecha de Cese del Cargo:";
		if($this->rad_tipodeclara_actu->Checked)
			$this->lab_fcargo->Text="Fecha de Actualización del Cargo:";
		if($this->rad_tipodeclara_incl->Checked)
			$this->lab_fcargo->Text="Fecha de toma del Cargo:";
		
		}
		
		public function CodOrgValidate($sender,$param)
		{

		}


		public function ConsignaSeleccionado($sender,$param)
		{
	
		if($this->rad_consigna_si->Checked)
		{
		$this->txt_cedcon->Enabled="true";
		$this->txt_nomcon->Enabled="true";
		$this->txt_numanexo->Enabled="true";
		}
		if($this->rad_consigna_no->Checked)
		{
		$this->txt_cedcon->Enabled="false";
		$this->txt_nomcon->Enabled="false";
		$this->txt_numanexo->Enabled="false";
		
		$this->txt_cedcon->Text="";
		$this->txt_nomcon->Text="";
		$this->txt_numanexo->Text="";
		}
	}

}
?>
