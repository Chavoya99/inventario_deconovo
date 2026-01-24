<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden de Compra</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #e5e7eb;
        }
    </style>
</head>
<body>

    <h2>Orden de Compra</h2>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Producto</th>
                <th>Unidad</th>
                <th>Cantidad</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($productos as $i => $producto)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $producto['producto'] }}</td>
                    <td>{{ $producto['unidad'] }}</td>
                    <td>{{ $producto['pedir'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
