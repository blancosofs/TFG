<!DOCTYPE html>
<html>
<head>
    <style>
        .tabla-datos {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
        }
        .tabla-datos td {
            padding: 10px;
            border-bottom: 1px solid #eeeeee;
        }
        .etiqueta {
            font-weight: bold;
            color: #333333;
            width: 30%;
            background-color: #f9f9f9;
        }
        .valor {
            color: #555555;
        }
        .cabecera {
            background-color: #2d3748;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="cabecera">
        Nueva Solicitud de Registro - Edunoly
    </div>
    
    <table class="tabla-datos">
        <tr>
            <td class="etiqueta">Centro Educativo:</td>
            <td class="valor">{{ $datos['centro'] }}</td>
        </tr>
        <tr>
            <td class="etiqueta">Nombre del Solicitante:</td>
            <td class="valor">{{ $datos['nombre'] }} {{ $datos['apellido'] }}</td>
        </tr>
        <tr>
            <td class="etiqueta">Email de contacto:</td>
            <td class="valor">{{ $datos['correo'] }}</td>
        </tr>
        <tr>
            <td class="etiqueta">Teléfono:</td>
            <td class="valor">{{ $datos['telefono'] }}</td>
        </tr>
        <tr>
            <td class="etiqueta">Perfil:</td>
            <td class="valor">{{ ucfirst($datos['perfil']) }}</td>
        </tr>
        <tr>
            <td class="etiqueta">Código Postal:</td>
            <td class="valor">{{ $datos['codigo_Postal'] }}</td>
        </tr>
        <tr>
            <td class="etiqueta">¿Ya es usuario?:</td>
            <td class="valor">{{ strtoupper($datos['seleccion2']) }}</td>
        </tr>
        <tr>
            <td class="etiqueta">Consulta/Mensaje:</td>
            <td class="valor">{{ $datos['textarea'] ?? 'Sin mensaje adicional' }}</td>
        </tr>
    </table>

    <p style="font-size: 12px; color: #999; margin-top: 20px;">
        Este es un mensaje automático enviado desde el formulario de contacto de Edunoly.
    </p>
</body>
</html>