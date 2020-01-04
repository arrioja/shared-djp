<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" >
<com:THead Title="WebCentra - Subsitema de Declaraciones juradas">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="content-language" content="es"/>
</com:THead>

<body>
	<com:TForm>
		<div id="header">
			<div class="logo">
				<img src="<%~ header.png %>" alt="header" />
			</div>
			<div class="menu2">
			<ul>
			<li><a class="menu2one" href="#nogo">Datos
			<!--[if IE 7]><!--></a><!--<![endif]-->
				<table><tr><td>
				<ul>
				<li><a href="<%= $this->Service->constructUrl('IncluirDecla') %>">Declaraciones Juradas</a></li>
				<li><a href="<%= $this->Service->constructUrl('Anular') %>">Anular Declaracion</a></li>
				<li><a href="<%= $this->Service->constructUrl('IncluirOrga') %>">Organismos</a></li>
				<li><a href="<%= $this->Service->constructUrl('Firmantes') %>">Firmante</a></li>
				<li><a href="<%= $this->Service->constructUrl('Usuarios') %>">Usuarios</a></li>
				</ul>

				</td></tr></table>
			</ul>
			<ul>
			<li><a class="menu2two" href="#nogo">Busquedas
			<!--[if IE 7]><!--></a><!--<![endif]-->
				<table><tr><td>
				<ul>
				<li><a href="<%= $this->Service->constructUrl('Busqueda') %>">Declaraciones</a></li>
   
				<li><a href="<%= $this->Service->constructUrl('BusqOrg') %>">Organismos</a></li>
				<li><a href="#nogo">Planillas</a></li>
				</ul>
				</td></tr></table>
   
			<!--[if lte IE 6]></a><![endif]-->
			</li>
			<li><a class="menu2three" href="<%= $this->Service->constructUrl('Reportes') %>">Reportes</li>
			<li><com:TLinkButton Text="Salir" OnClick="LogOut" /></li>

				</td></tr></table>
			<!--[if lte IE 6]></a><![endif]-->
			</li>
			</ul>
   
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