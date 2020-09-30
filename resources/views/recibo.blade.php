<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Recibo de pago</title>
	<style>
		tr > th, tr > td{
			padding: 8px 0;
		}
		.titulo-recibo {
			text-align: center;
		}
		.titulo-recibo > span{
			font-family: Arial;
			font-size: 2rem;
			font-weight: bold;
		}
		.cinta{
			background-color: #000;
			width: auto;
			height: 35px;
		}
		.descripcion-recibo {
			width:100%;
			text-align: center;
			padding-top: 8px;
		}
		.descripcion-recibo > span{
			font-size: 13px;
			font-weight: bold;
			color:#fff;
			background-color: #000;
			width: 100%;
			padding-bottom: 20px;
		}
		.serie{
			font-size: 15px;
			font-weight: bold;
		}
		.titulo{
			font-size: 11px;
			text-align: left;
		}
		
		.text-center{
			text-align: center;
		}
		.table{
			width: 100%;
		}
	</style>

</head>
<body>
	<div style="margin-top: 70px;">
		<img src="{{ asset('img/marca-agua.png') }}" alt="" style="position: absolute; left: 0; right: 0; margin-top: 60px; margin-left: auto; margin-right: auto; width: 100%">
	</div>
	<div class="titulo-recibo">
		<span>FORMA 1-D1</span>
	</div>
	<div class="cinta">
		<div class="descripcion-recibo">
			<span>RECIBO DE CONTRIBUCIONES VOLUNTARIAS Y OTROS INGRESOS</span>
		</div>
	</div>
	<table class="table">
		<tr>
			<td width="33.33%">
				<img src="{{ asset('img/logo.png') }}" alt="" style="height: 80px;">
			</td>
			<td width="33.33%"></td>
			<td width="33.33%" style="text-align: right;">
				<span class="serie">
					SERIE DP
				</span>
				<br/>
				<span class="serie">
					<div>No. <em style="color:#e62b0d;">000000</em></div>
				</span>
			</td>
		</tr>
	</table>
	<!--  -->
	<table class="table">
		<tr class="text-center">
			<td>Comité de Agua Potable, Aldea Platanares</td>
		</tr>
		<tr class="text-center">
			<td>Guazacapán, Santa Rosa</td>
		</tr>
	</table>
	<table class="table">
		<tr style="border-spacing: 10px 5px;">
			<th width="10%" class="titulo">FECHA:</th>
			<td width="30%" style="text-align:left;">28-09-2020</td>
			<th width="50%" class="titulo" style="text-align: right">INGRESO POR Q.:</th>
			<td style="text-align:right;">108.00</td>
		</tr>
		<tr>
			<th width="10%" class="titulo">RECIBI DE:</th>
			<td colspan="3" style="text-align:left;">Juan Francisco Yelmo Morales</td>
		</tr>
		<tr>
			<th class="titulo" style="font-size: 10px;">LA CANTIDAD DE:</th>
			<td colspan="3"> Ciento Ocho Quetzales exactos</td>
		</tr>
		<tr>
			<th width="8%" class="titulo">CONTRIBUCION:</th>
			<td colspan="3" style="text-align:left;">Pago de servicio de agua</td>
		</tr>
		<tr>
			<th width="8%" class="titulo">MES Y AÑO:</th>
			<td colspan="3" style="text-align:left;">Septiembre, 2020</td>
		</tr>
		<tr>
			<th width="100%" colspan="3" class="titulo">AUTORIZACION DE GOBERNACION DEPARTAMENTAL No:</th>
			<td><small>40-2014KNZM</small></td>
		</tr>
		<tr>
			<th width="10%" class="titulo">DE FECHA:</th>
			<td width="10%" style="text-align: left;">15/02/2017</td>
			<th width="80%" colspan="2" class="titulo" style="text-align: right;">No. CUENTANDANCIA O REGISTRO</td>
		</tr>
		<tr>
			<th colspan="3" class="titulo">DE LA CONTRALORIA GENERAL DE CUENTAS:</th>
			<td colspan="1"><small style="font-size: 12px;">06-09-09 F170 L04</small></td>
		</tr>
	</table>
	<div style="height: 40px;"></div>
	<table class="table">
		<tr>
			<td class="text-center titulo"> Sello de la oficina</td>
			<td class="text-center titulo"> Firma tesorero o recaudador autorizado</td>
		</tr>		
	</table>
	<div style="height: 10px;"></div>
	<div  style="border: 1px solid #000; border-radius: 5px;">
		<table class="table">
			<tr>
				<td style="font-size: 10px; text-align: justify;">NOTA: Los recibos deben extenderse en riguroso orden correlativo al momento del pago y no anticipadamente. Circular No. 4734 de la Contraloría General de Cuentas. Los recibos anulados deben agregarse completos a la rendición de cuentas. NO TENDRA VALOR SI CONTIENE BORRONES, TACHADURAS O ENMIENDAS</td>
			</tr>		
		</table>	
	</div>
</body>
</html>