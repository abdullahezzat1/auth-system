<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @include('style')
  <title>Reset your password</title>
</head>
<body>
  <div class="container">
    <h1 style="text-align:center">Reset your password</h1>
    @if ($errors->any())
      @foreach ($errors->all() as $error)
        <p class="error">{{ $error }}</p>
      @endforeach
    @endif
    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif
    <div class="row">
      <div class="col">
        <form action="{{env('APP_URL')}}/account/password/reset" method="POST">
          @csrf
          <input type="hidden" name="token" value="{{$token}}">
          <input type="text" placeholder="Email" name="email" value="{{ old('email') }}">
          <input type="password" placeholder="New password" name="password">
          <input type="password" placeholder="Repeat new password" name="password_confirmation">
          <input type="submit" value="Submit">
        </form>
      </div>
    </div>
  </div>
</body>
</html>
