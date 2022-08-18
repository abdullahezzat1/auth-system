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
    <h1 style="text-align:center">Welcome {{session('user_first_name')}} {{session('user_last_name')}}</h1>
    @include('errors')
    @include('success')
    <div class="row">

      <div class="col">
        <h3>Change profile information</h3>
        <form action="change-profile-info" method="POST">
          @csrf
          <input type="text" placeholder="Edit your first name" name="first_name" value="{{session('user_first_name')}}">
          <input type="text" placeholder="Edit your last name" name="last_name" value="{{session('user_last_name')}}">
          <input type="submit" value="Submit">
        </form>
      </div>

      <div class="col">
        <h3>Change password</h3>
        <form action="change-password" method="POST">
          @csrf
          <input type="password" placeholder="Current password" name="current_password">
          <input type="password" placeholder="New password" name="new_password">
          <input type="password" placeholder="Repeat new password" name="repeat_new_password">
          <input type="submit" value="Submit">
        </form>
      </div>

      <div class="col">
        <h3>Logout</h3>
        <form action="logout" method="POST">
          @csrf
          <input type="submit" value="Logout">
        </form>
      </div>
    </div>
  </div>
</body>
</html>
