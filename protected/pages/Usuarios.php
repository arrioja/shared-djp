<?php

class Usuarios extends TPage
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
			$sql="SELECT COUNT(*) FROM usuarios";
			$search=$this->CargarData($sql);
			$this->lbl_registros->Text="Registros Encontrados: ".$search[0]["COUNT(*)"];
   
			//Busqueda de Registros
			$session=new THttpSession;
			$session->open();
			$session['sql']="SELECT * from usuarios";
			$search=$this->CargarData($session['sql']);
			$this->DataGrid->DataSource=$search;
			$this->DataGrid->dataBind();
			$session->close();
			}
		}
		$this->alerta->Visible = false;    
	}
	
	public function editItem($sender,$param)
	{
	
		$session=new THttpSession;
		$session->open();
		$search=$this->CargarData($session['sql']);
		$session->close();
		$this->DataGrid->EditItemIndex=$param->Item->ItemIndex;
		$this->DataGrid->DataSource=$search;
		$this->DataGrid->dataBind();
	}
	
		public function saveItem($sender,$param)
	{
		$item=$param->Item;
		$id=$this->DataGrid->DataKeys[$item->ItemIndex];
		$login=$item->login->TextBox->Text;
		$nombre=$item->nombre->TextBox->Text;
		$apellidos=$item->apellidos->TextBox->Text;
		$password=$item->password->TextBox->Text;

		$sql="UPDATE usuarios set nombre='$nombre', apellidos='$apellidos', login='$login', password=MD5('$password') where id_usuario=$id";

		$db = $this->Application->Modules['db1']->DbConnection;
		$db->Active=true;
		$resultado=$db->createCommand($sql)->execute();
		$db->Active=false;

		$session=new THttpSession;
		$session->open();
		$session['sql']="SELECT * from usuarios";
		$search=$this->CargarData($session['sql']);
		$session->close();
		$this->DataGrid->EditItemIndex=-1;
		$this->DataGrid->DataSource=$search;
		$this->DataGrid->dataBind();
	}


	public function itemCreated($sender,$param)
	{
		$item=$param->Item;
		if($item->ItemType==='EditItem')
		{
		// set column width of textboxes
		$item->id_usuario->TextBox->ReadOnly="true";
		$item->login->TextBox->ReadOnly="true";
		$item->password->TextBox->TextMode="Password";
		$item->id_usuario->TextBox->Columns=1;
		
		$item->login->TextBox->Columns=12;
		$item->nombre->TextBox->Columns=12;
		$item->apellidos->TextBox->Columns=12;
		$item->password->TextBox->Columns=12;
		}
		if($item->ItemType==='Item' || $item->ItemType==='AlternatingItem' || $item->ItemType==='EditItem')
		{
			$item->Borrar->Button->Attributes->onclick='if(!confirm(\'¿Está seguro que quiere eliminar el registro?\')) return false;';
		}
	}

	public function cancelItem($sender,$param)
	{
		$session=new THttpSession;
		$session->open();
		$session['sql']="SELECT * from usuarios";
		$search=$this->CargarData($session['sql']);
		$session->close();
		$this->DataGrid->EditItemIndex=-1;
		$this->DataGrid->DataSource=$search;
		$this->DataGrid->dataBind();

	}
	
	
	public function BorrarReg($sender,$param)
	{
		try {
			$id=$this->DataGrid->DataKeys[$param->Item->ItemIndex];
			$sql="DELETE FROM usuarios WHERE id_usuario = $id";
			$db = $this->Application->Modules['db1']->DbConnection;
			$db->Active=true;
			$resultado=$db->createCommand($sql)->execute();
			$db->Active=false;	
			$this->Response->redirect($this->Service->constructUrl('Usuarios'));
			}
		catch (Exception $e) 
		{
			$mensaje_error=$e->getMessage();
			if (isset($mensaje_error))
				{
					$this->alerta->Visible = true;
				}
			$db->Active=false;
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
	
	public function Aceptar($sender,$param)
	{
		

		$login=$this->txt_login->Text;
		$password=$this->txt_pass->Text;
		$nombre=$this->txt_nombre->Text;
		$apellidos=$this->txt_apellidos->Text;
		$sql="INSERT INTO usuarios (id_usuario, login, password, nombre, apellidos, permisos) values ('NULL','$login',MD5('$password'),'$nombre', '$apellidos','NO SE USA');";
		$db = $this->Application->Modules['db1']->DbConnection;
		$db->Active=true;
		$resultado=$db->createCommand($sql)->execute();
		$db->Active=false;	 
		$this->Response->redirect($this->Service->constructUrl('Usuarios'));
	}

	public function ExisteLogin($sender,$param)
	{
		$login=$this->txt_login->Text;
		$sql="SELECT COUNT(*) FROM usuarios WHERE login='$login'";
		$search=$this->CargarData($sql);
		$valor=$search[0]["COUNT(*)"];
		if($valor>0)
		{
			$param->IsValid=false;
			$this->txt_login->Text="";
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