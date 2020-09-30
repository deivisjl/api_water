<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Boleta de registro</title>
</head>
<body>
	<table>
		<tbody style="width: 100%">
			<tr>
				<td>
					<img src="{{ asset('img/logo-oficial.png') }}" alt="" style="height: 70px; display: block; margin: auto;">
				</td>				
			</tr>
			<tr>
				<th colspan="2" style="text-align:center; color: #1565C0;">SISCAP</th>
			</tr>
			<tr>
				<th colspan="2" style="text-align:center; color: #1565C0;">Sistema de Administración de Usuarios</th>
			</tr>
			<tr>
				<th colspan="2" style="text-align:center; color: #1565C0;">Comité de Agua Potable Aldea Platanares</th>
			</tr>
		</tbody>
	</table>
	<hr/>
	<table style="width: 100%">
		<tbody>
			<tr>
				<td style="text-align:left; ;width:25%;">Nombre:</td>
				<td>{{ $datos['usuario']->primer_nombre }} {{ $datos['usuario']->segundo_nombre }} {{ $datos['usuario']->tercer_nombre }} {{ $datos['usuario']->primer_apellido }} {{ $datos['usuario']->segundo_apellido }}</td>
			</tr>
			<tr>
				<td style="text-align:left;">Dirección residencia:</td>
				<td>{{ $datos['usuario']->direccion_residencia }}</td>
			</tr>
			<tr>
				<td style="text-align:left;">Correo electrónico:</td>
				<td>{{ $datos['usuario']->correo_electronico }}</td>
			</tr>
			<tr>
				<td style="text-align:left;">Usuario:</td>
				<td>{{ $datos['usuario']->email }}</td>
			</tr>
			<tr>
				<td style="text-align:left;">Contraseña:</td>
				<td>{{ $datos['pass']}}</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: right;">{{ $datos['fecha'] }}</td>
			</tr>
		</tbody>
	</table>
</body>
</html>
