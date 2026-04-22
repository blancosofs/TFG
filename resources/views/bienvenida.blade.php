<div style="font-family: sans-serif; color: #333; max-width: 600px;">
    <h1 style="color: #4f46e5;">¡Bienvenido a Edunoly!</h1>
    <p>Hola, <strong>{{ $user->name }}</strong>.</p>
    <p>Tu centro educativo ya está registrado en nuestra plataforma. Aquí tienes tus credenciales de acceso como <strong>Coordinador</strong>:</p>
    
    <div style="background: #f3f4f6; padding: 20px; border-radius: 10px; margin: 20px 0;">
        <p style="margin: 0;"><strong>Email:</strong> {{ $user->email }}</p>
        <p style="margin: 0;"><strong>Contraseña temporal:</strong> <span style="color: #ef4444; font-weight: bold;">{{ $pass }}</span></p>
    </div>

    <p>Te recomendamos cambiar la contraseña una vez entres al sistema.</p>
    <a href="{{ url('/login') }}" style="display: inline-block; padding: 10px 20px; background: #4f46e5; color: white; text-decoration: none; border-radius: 5px;">Acceder al Panel</a>
</div>