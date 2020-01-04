<?php
class MainLayout extends TTemplateControl
{
	public function logout($param)
	{
	$session=new THttpSession;
	$session->open();
	$session['logged']="0";
	$session->close();
	$this->Response->redirect($this->Service->constructUrl('Login'));
	}
}
?>
