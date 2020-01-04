<?php

class BusqOrg extends TPage
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
			$sql="SELECT COUNT(*) FROM organismo";
			$search=$this->CargarData($sql);
			$this->lbl_registros->Text="Registros Encontrados: ".$search[0]["COUNT(*)"];
   
			//Busqueda de Registros
			$session=new THttpSession;
			$session->open();
			$session['sql']="SELECT * from organismo";
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

	public function itemCreated($sender,$param)
	{
		$item=$param->Item;
		if($item->ItemType==='EditItem')
		{
		// set column width of textboxes
		$item->id_organismo->TextBox->ReadOnly="true";
		$item->id_organismo->TextBox->Columns=1;
		$item->nombre->TextBox->Columns=50;
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
		$session['sql']="SELECT * from organismo";
		$search=$this->CargarData($session['sql']);
		$session->close();
		$this->DataGrid->EditItemIndex=-1;
		$this->DataGrid->DataSource=$search;
		$this->DataGrid->dataBind();

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
	
		public function BorrarReg($sender,$param)
	{
		try {
			$id=$this->DataGrid->DataKeys[$param->Item->ItemIndex];
			$sql="DELETE FROM organismo WHERE id_organismo = $id";
			$db = $this->Application->Modules['db1']->DbConnection;
			$db->Active=true;
			$resultado=$db->createCommand($sql)->execute();
			$db->Active=false;	
			$this->Response->redirect($this->Service->constructUrl('BusqOrg'));
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
	
	public function saveItem($sender,$param)
	{
		$item=$param->Item;
		$id=$this->DataGrid->DataKeys[$item->ItemIndex];
		$nombre=$item->nombre->TextBox->Text;

		$sql="UPDATE organismo set nombre='$nombre' where id_organismo=$id";

		$db = $this->Application->Modules['db1']->DbConnection;
		$db->Active=true;
		$resultado=$db->createCommand($sql)->execute();
		$db->Active=false;

		$session=new THttpSession;
		$session->open();
		$session['sql']="SELECT * from organismo";
		$search=$this->CargarData($session['sql']);
		$session->close();
		$this->DataGrid->EditItemIndex=-1;
		$this->DataGrid->DataSource=$search;
		$this->DataGrid->dataBind();
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
		
		if($this->rad_codigo->Checked)
		{

			if (!intval($valor)=="0") 
			{
			
				//Conteo Registros
				$sql="SELECT COUNT(*) FROM organismo where id_organismo = $valor";
				$search=$this->CargarData($sql);
				$this->lbl_registros->Text="Registros Encontrados: ".$search[0]["COUNT(*)"];
		
				//Busqueda de Registros
				$session=new THttpSession;
				$session->open();
				$session['sql']="select * from organismo where id_organismo = $valor";
				$search=$this->CargarData($session['sql']);
				$this->DataGrid->DataSource=$search;
				$this->DataGrid->dataBind();
				$session->close();

			}

		}
		
		if($this->rad_nombre->Checked)
		{
		//Conteo Registros
		$sql="SELECT COUNT(*) FROM organismo where nombre like '%$valor%'";
		$search=$this->CargarData($sql);
		$this->lbl_registros->Text="Registros Encontrados: ".$search[0]["COUNT(*)"];
		
		//Busqueda de Registros
		$session=new THttpSession;
		$session->open();
		$session['sql']="SELECT * from organismo where nombre like '%$valor%' ";
		$search=$this->CargarData($session['sql']);
		$this->DataGrid->DataSource=$search;
		$this->DataGrid->dataBind();
		$session->close();
		
		}
	}

}
?>