<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" >
<com:THead Title="WebCentra - Subsitema de Declaraciones juradas">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="content-language" content="es"/>
</com:THead>

<body>
	<com:TForm>
		<div id="header">
			<div class="logo">
				<img src="<%~ header.png %>" alt="header" />
			</div>
		</div>
   
   
		<div id="main" >
		<com:TContentPlaceHolder ID="Main" />
		</div>
   
   
   
		<div id="footer">
		<br><br>
   
		Copyright &copy; 2008 <a href="http://www.cgesucre.gob.ve">Contraloria General del Estado Sucre</a>.
   
		<br/><br/>
   
		<%= Prado::poweredByPrado() %>
  
		</div>
   
   
	</com:TForm>
   
	</body>

</html>