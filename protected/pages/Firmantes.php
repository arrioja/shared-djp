<?php

class Firmantes extends TPage
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
			$sql="SELECT COUNT(*) FROM firmante";
			$search=$this->CargarData($sql);
			$this->lbl_registros->Text="Registros Encontrados: ".$search[0]["COUNT(*)"];
   
			//Busqueda de Registros
			$session=new THttpSession;
			$session->open();
			$session['sql']="SELECT * from firmante";
			$search=$this->CargarData($session['sql']);
			$this->DataGrid->DataSource=$search;
			$this->DataGrid->dataBind();
			$session->close();
			}
		}   
	}



	public function BorrarReg($sender,$param)
	{
		$id=$this->DataGrid->DataKeys[$param->Item->ItemIndex];
		$sql="DELETE FROM firmante WHERE id_firmante = $id";
		$db = $this->Application->Modules['db1']->DbConnection;
		$db->Active=true;
		$resultado=$db->createCommand($sql)->execute();
		$db->Active=false;	
		$this->Response->redirect($this->Service->constructUrl('Firmantes'));
	}



	public function Aceptar($sender,$param)
	{
		if($this->chk_activo->checked=="true")
			{
				$activo=1;
			}
			else
			{
				$activo=0;
			}
		$nombre=$this->txt_nomape->Text;
		$cargo=$this->txt_cargo->Text;
		$sql="INSERT INTO firmante (id_firmante, nombre, cargo, activo) values ('NULL','$nombre','$cargo','$activo');";

		
		$db = $this->Application->Modules['db1']->DbConnection;
		$db->Active=true;
		$resultado=$db->createCommand($sql)->execute();
		$db->Active=false;	
		$this->Response->redirect($this->Service->constructUrl('Firmantes'));
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
		$item->id_firmante->TextBox->ReadOnly="true";
		$item->id_firmante->TextBox->Columns=1;
		}
		if($item->ItemType==='Item' || $item->ItemType==='AlternatingItem' || $item->ItemType==='EditItem')
		{
			$item->Borrar->Button->Attributes->onclick='if(!confirm(\'¿Está seguro que quiere eliminar el registro?\')) return false;';
		}
	}
	public function ExisteActivo($sender,$param)
	{
		$sql="SELECT COUNT(*) FROM firmante WHERE activo =1";
		$search=$this->CargarData($sql);
		$valor=$search[0]["COUNT(*)"];
		if($valor>0)
		{
			$param->IsValid=false;
			$this->chk_activo->checked="false";
		}	
	}

	public function cancelItem($sender,$param)
	{
		$session=new THttpSession;
		$session->open();
		$session['sql']="SELECT * from firmante";
		$search=$this->CargarData($session['sql']);
		$session->close();
		$this->DataGrid->EditItemIndex=-1;
		$this->DataGrid->DataSource=$search;
		$this->DataGrid->dataBind();

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

	public function saveItem($sender,$param)
	{
		$item=$param->Item;
		$id=$this->DataGrid->DataKeys[$item->ItemIndex];
		$nombre=$item->nombre->TextBox->Text;
		$cargo=$item->cargo->TextBox->Text;

		if($item->activo->CheckBox->Checked=="true")
			{
				$consulta="SELECT COUNT(*) FROM firmante WHERE activo=1";
				$search=$this->CargarData($consulta);
				$valor=$search[0]["COUNT(*)"];
				
				if($valor==0)
				{
					$bolactivo=1;
				}
			}
			else
			{
				$bolactivo=0;
			}
			
		$sql="UPDATE firmante set nombre='$nombre', cargo='$cargo', activo='$bolactivo' where id_firmante=$id";
		
		$db = $this->Application->Modules['db1']->DbConnection;
		$db->Active=true;
		$resultado=$db->createCommand($sql)->execute();
		$db->Active=false;
	
		$session=new THttpSession;
		$session->open();
		$session['sql']="SELECT * from firmante";
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

}
?>