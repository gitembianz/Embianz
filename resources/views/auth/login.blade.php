<!DOCTYPE html>
<html lang="ro">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		{{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
		<title>Embianz</title>
		<link rel="icon" type="image/x-icon" href="/favicon.png">
		<link rel="stylesheet" href="/dist/css/dashboard.css">
		{{-- <link rel="stylesheet" href="/dist/css/main.css"> --}}
	</head>

	<body>


<body class="gradient--bg">
  <form class="login__form" action="{{ route('login') }}" method="POST">
    @csrf
    {{-- Logo --}}
    <img class="logo" src="/images/dashboard/navbar/logo.png" alt="logo">
    {{-- Login Input --}}
    <label class="login__label" for="email" value="{{ __('Email') }}">
      <input type="text" placeholder=" " id="email" type="email" name="email" :value="old('email')" required autofocus>
      <span>
        <svg><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
        Username / Email
      </span>
    </label>
    {{-- Password Input --}}
    <label class="login__label" for="password" value="{{ __('Password') }}">
      <input type="password" placeholder=" " name="password" id="password" required
      autocomplete="current-password">
      <span>
        <svg><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" /><path d="M12 11m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 12l0 2.5" /></svg>
        Password
      </span>
      <button type="button" id="togglePassword">
        <svg class="login__eye" id="eyeOpen"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
        <svg class="login__eye--closed" id="eyeClosed"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.585 10.587a2 2 0 0 0 2.829 2.828" /><path d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87" /><path d="M3 3l18 18" /></svg>
      </button>
    </label>
    {{-- Validation --}}
    {{-- <span class="login__error">Password is incorrect!</span> --}}
    {{-- Login Button --}}
    <button type="submit" class="login__button">
      Sign In
      <svg><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M13 18l6 -6" /><path d="M13 6l6 6" /></svg>
    </button>
  </form>
  <div class="gradient"></div>
  <script>
    document.getElementById('togglePassword').addEventListener('click', function () {
      const passwordInput = document.getElementById('password');
      const eyeOpen = document.getElementById('eyeOpen');
      const eyeClosed = document.getElementById('eyeClosed');

      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeOpen.style.display = 'none';
        eyeClosed.style.display = 'block';
      } else {
        passwordInput.type = 'password';
        eyeOpen.style.display = 'block';
        eyeClosed.style.display = 'none';
      }
    });
  </script>
</body>
