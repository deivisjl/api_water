<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de morosos</title>
    <style>
        .table-custom{
            width: 100%;
        }
        .table{
            width: 100%;
        }
        .text-center{
            text-align: center;
        }
        .table-bordered {
            border: 1px solid #000;
        }
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
            padding: 8px;
            line-height: 1.42857143;
            vertical-align: top;
            border-top: 1px solid #000;
        }
        .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
            border: 1px solid #000;
        }
        .table {
            border-spacing: 0;
            border-collapse: collapse;
        }
    </style>
</head>
<body>
    <table class="table-custom">
        <tr style="text-align: center">
            <td>Listado de morosos al <strong>{{ $hasta }}</strong></td>
        </tr>
    </table>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 8%;"></th>
                <th style="width: 10%;">Convenio</th>
                <th style="width: 30%">Titular</th>
                <th style="width: 10%;">DPI</th>
                <th style="width: 20%;">Ubicación</th>
                <th style="width: 15%">Estado</th>
            </tr>
        </thead>
        <tbody>
            @if(sizeof($registros) > 0)
                @foreach ($registros as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->no_convenio }}</td>
                        <td>{{ $item->nombres }} {{ $item->apellidos }}</td>
                        <td>{{ $item->dpi }}</td>
                        <td>{{ $item->sector }}</td>
                        <td>{{ $item->estado }}</td>
                    </tr>
                @endforeach
            @else
                    <tr style="text-align:center">
                        <td colspan="6">No se encontraron registros</td>
                    </tr>
            @endif
        </tbody>
    </table>
</body>
</html>