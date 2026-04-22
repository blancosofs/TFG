<div style="font-family: sans-serif; color: #333;">
    <h2 style="border-bottom: 2px solid #4f46e5; padding-bottom: 10px;">Nueva consulta recibida</h2>
    <p>Has recibido un mensaje desde el formulario de contacto de Edunoly:</p>
    <ul>
        <li><strong>Nombre:</strong> {{ $datos['nombre'] }} {{ $datos['apellido'] }}</li>
        <li><strong>Email:</strong> {{ $datos['correo'] }}</li>
        <li><strong>Perfil:</strong> {{ $datos['perfil'] }}</li>
        <li><strong>Centro:</strong> {{ $datos['centro'] }}</li>
    </ul>
    <hr>
    <p><strong>Mensaje:</strong></p>
    <p style="font-style: italic; background: #f9fafb; padding: 15px;">{{ $datos['textarea'] }}</p>
</div>