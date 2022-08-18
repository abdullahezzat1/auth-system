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
    @include('errors')
    <div class="row">
      <div class="col">
        <form action="{{env('APP_URL')}}/reset-password" method="POST">
          @csrf
          <input type="hidden" name="password_reset_token" value="{{$token}}">
          <input type="password" placeholder="New password" name="new_password">
          <input type="password" placeholder="Repeat new password" name="repeat_new_password">
          <input type="submit" value="Submit">
        </form>
      </div>
    </div>
  </div>
</body>
</html>
