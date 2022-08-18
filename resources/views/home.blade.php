<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @include('style')
  <title>Home</title>
</head>
<body>
  <div class="container">
    <h1 style="text-align:center">Welcome!</h1>
    @include('errors')
    @include('success')
    <div class="row">
      <div class="col">
        <h2>Sign Up</h2>
        <form method="POST" action="signup">
          @csrf
          <input type="text" placeholder="Email" name="email" value="{{session('email')}}">
          <input type="text" placeholder="First Name" name="first_name" value="{{session('first_name')}}">
          <input type="text" placeholder="Last Name" name="last_name" value="{{session('last_name')}}">
          <input type="password" placeholder="Password" name="password">
          <input type="password" placeholder="Repeat password" name="repeat_password">
          <input type="submit" value="Submit">
        </form>
      </div>
      <div class="col">
        <h2>Login</h2>
        <form method="POST" action="login">
          @csrf
          <input type="text" placeholder="Email" name="email">
          <input type="password" placeholder="Password" name="password">
          <input type="submit" value="Submit">
        </form>
      </div>

      <div class="col">
        <h2>Forgot Password?</h2>
        <form method="POST" action="forgot-password">
          @csrf
          <input type="text" placeholder="Email" name="email">
          <input type="submit" value="Submit">
        </form>
      </div>
    </div>
  </div>
</body>
</html>
