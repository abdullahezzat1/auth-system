<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @include('style')
  <title>App</title>
</head>
<body>
  <div class="container">
    <h1 style="text-align:center">Welcome {{ $first_name }} {{ $last_name }}</h1>
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
        <h3>Change profile information</h3>
        <form action="{{ env('APP_URL') }}/account/info/change" method="POST">
          @csrf
          <input type="text" placeholder="Edit your first name" name="first_name" value="{{ $first_name }}">
          <input type="text" placeholder="Edit your last name" name="last_name" value="{{ $last_name }}">
          <input type="submit" value="Submit">
        </form>
      </div>

      <div class="col">
        <h3>Change password</h3>
        <form action="{{ env('APP_URL') }}/account/password/change" method="POST">
          @csrf
          <input type="password" placeholder="Current password" name="current_password">
          <input type="password" placeholder="New password" name="new_password">
          <input type="password" placeholder="Repeat new password" name="new_password_confirmation">
          <input type="submit" value="Submit">
        </form>
      </div>

      <div class="col">
        <h3>Logout</h3>
        <form action="{{ env('APP_URL') }}/account/logout" method="POST">
          @csrf
          <input type="submit" value="Logout">
        </form>
      </div>
    </div>
  </div>
</body>
</html>
