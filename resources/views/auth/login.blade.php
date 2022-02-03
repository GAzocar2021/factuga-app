@extends('auth.header')

@section('content')
      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <div class="x_panel">
                <div class="x_content">
                    <div class="text-left">
                        <small><strong>Administrador:</strong><br />Correo Electrónico: admin@factugaap.com<br/ >Contraseña: 12345678</small>
                        <br>
                        <br>
                        <br>
                        <small><strong>Cliente 1:</strong><br />Correo Electrónico: cliente1@factugaap.com<br/ >Contraseña: 90123456</small>
                        <br>
                        <small><strong>Cliente 2:</strong><br />Correo Electrónico: cliente2@factugaap.com<br/ >Contraseña: 78901234</small>
                        <br>
                        <small><strong>Cliente 3:</strong><br />Correo Electrónico: cliente3@factugaap.com<br/ >Contraseña: 56789012</small>
                    </div>
                    <hr>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <h1>FactuGApp</h1>
                        <h5>Iniciar Sesión</h5>
                        <div>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Correo Electrónico" required autocomplete="email" autofocus />

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Contraseña" required autocomplete="current-password"/>

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        {{-- <div>
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('Recordarme') }}
                            </label>
                        </div> --}}
                        <div>
                            <button type="submit" class="btn btn-default submit">
                                {{ __('Entrar') }}
                            </button>
                        </div>
                        <div>
                            {{-- @if (Route::has('password.request'))
                                <a class="btn btn-link reset_pass" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif --}}
                        </div>

                        <div class="clearfix"></div>

                        <div class="separator">
                            <p class="change_link">
                            <a href="{{ route('register') }}" class="to_register"> Registrarse </a>
                            </p>

                            <div class="clearfix"></div>
                            <br />

                            <div>
                            <p>©2022 {{ config('app.name', 'FactuGApp') }}</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
          </section>
        </div>
      </div>
  </body>
</html>
@endsection
