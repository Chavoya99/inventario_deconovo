<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden de Compra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td, th {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: middle;
        }

        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }

        .header {
            font-size: 16px;
            font-weight: bold;
        }

        .provider {
            background: #d9ddff;
            font-weight: bold;
        }

        .big-box {
            height: 180px;
        }

        .highlight {
            background: #ffe600;
            font-weight: bold;
        }

        .no-border {
            border: none;
        }
    </style>
</head>
<body>

<table>
    <tr>
        <td rowspan="2" class="center header">
            Deconovo<br>
            Recubrimientos
        </td>
        <td class="center">Fecha: {{$orden_compra->fecha_generada->format('d/m/Y')}}</td>
        <td class="center bold">ORDEN DE COMPRA</td>
        <td class="center bold">Folio: {{str_pad($orden_compra->id, 3, "0", STR_PAD_LEFT)}}</td>
    </tr>
    <tr>
        <td class="center bold">Tienda Arbolada</td>
        <td colspan="2" class="provider center">Proveedor: {{ucfirst($proveedor->nombre)}}</td>
    </tr>

    <tr class="center bold">
        <td>Cantidad</td>
        <td>Unidad</td>
        <td>Precio</td>
        <td>Concepto</td>
    </tr>
    @foreach ($productos as $producto)
        <tr class="center">
            <td>{{$producto['pedir']}}</td>
            <td>{{$producto['unidad']}}</td>
            <td></td>
            <td>{{$producto['producto']}}</td>
        </tr>
    @endforeach
    
    <tr>
        <td colspan="4" class="big-box"></td>
    </tr>

    <tr>
        <td colspan="2">
            <b>Recomendación para Almacén:</b><br><br>

            <b>Facturar:</b> Ana Livier<br>
            <b>Uso de CFDI:</b> G01 Adquisición de Mercancías<br>
            <b>Forma de Pago:</b> 99 Por Definir<br>
            <b>Método de pago:</b> PPD Pago en Parcialidades o Diferidos<br><br>

            <span class="highlight">Recepción de mercancía</span>
            <p>Lunes a Viernes 9:30 am. - 12:45 pm. </p>
            <p> 14:00 pm. - 17:30 pm.</p>
        </td>

        <td colspan="2" class="center">
            <b>Atentamente:</b><br><br><br>
            {{$nombre}}<br><br>
            <b>Favor de Enviar Factura al correo:</b>
            <br><u><a href="mailto:{{$correo}}">{{$correo}}</a></u>
            
        </td>
    </tr>
</table>

</body>
</html>