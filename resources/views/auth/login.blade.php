<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edunoly · Acceso</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <script src="{{ asset('js/temas.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('css/temas.css') }}">
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

<a href="{{ route('index') }}" class="btnVolver" aria-label="Volver al inicio">
  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
  </svg>
  Volver al inicio
</a>

<main class="page">
  <div class="login-wrap">
    <div class="card">
      <div class="card-head">
        <h1>Acceso a la plataforma</h1>
        <p>Selecciona tu perfil e introduce tus credenciales</p>
      </div>

      <div class="profile-tabs">
        <div class="tab active" onclick="selectTab(this)"><span>Coordinador</span></div>
        <div class="tab" onclick="selectTab(this)"><span>Docente</span></div>
        <div class="tab" onclick="selectTab(this)"><span>Familiar</span></div>
      </div>

      <div class="card-body">
        <div class="notice">
          Usa las credenciales proporcionadas por el centro.
        </div>

        <form method="POST" action="{{ route('login') }}">
          @csrf <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <div class="input-wrap">
              <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="ejemplo@correo.com" required autofocus />
            </div>
            @if ($errors->has('email'))
                <p class="field-hint" style="color: #ff4d4d;">{{ $errors->first('email') }}</p>
            @endif
          </div>

          <div class="form-group">
            <label for="password">Contraseña</label>
            <div class="input-wrap">
              <input type="password" id="password" name="password" required placeholder="••••••••" />
              <button type="button" class="toggle-pw" onclick="togglePw()">👁️</button>
            </div>
            @if ($errors->has('password'))
                <p class="field-hint" style="color: #ff4d4d;">{{ $errors->first('password') }}</p>
            @endif
          </div>

          <div class="form-row">
            <label class="check-label">
              <input type="checkbox" name="remember"/>
              <span class="check-box"></span> Mantener sesión
            </label>
            <a href="{{ route('password.request') }}" class="link">¿Olvidaste la contraseña?</a>
          </div>

          <button type="submit" class="btn-primary">Acceder</button>
        </form>
      </div>
    </div>
  </div>
</main>

<script src="{{ asset('js/login.js') }}"></script>
</body>
</html>