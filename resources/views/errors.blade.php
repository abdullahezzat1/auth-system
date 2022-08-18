@if(session('error1'))
<p class="error">Invalid Email address</p>

@endif



@if(session('error2'))
<p class="error">Invalid first name. Only letters are allowed.</p>

@endif


@if(session('error11'))
<p class="error">Invalid last name. Only letters are allowed.</p>

@endif



@if(session('error3'))
<p class="error">
  Password must have length of 8 to 64 characters<br>
  Password must contain at least one lowercase letter<br>
  Password must contain at least one uppercase letter<br>
  Password must contain at least one special character<br>
</p>

@endif



@if(session('error4'))
<p class="error">Passwords do not match</p>

@endif



@if(session('error5'))
<p class="error">Incorrect email or password</p>

@endif



@if(session('error6'))
<p class="error">Email does not exist!</p>

@endif



@if(session('error7'))
<p class="error">Email already exists!</p>

@endif



@if(session('error8'))
<p class="error">Incorrect password</p>

@endif



@if(session('error9'))
<p class="error">Invalid password reset token</p>

@endif



@if(session('error10'))
<p class="error">Server error! Please try again.</p>

@endif



@if(session('error12'))
<p class="error">Wrong email or password.</p>

@endif

@if(session('error13'))
<p class="error">Please log in first.</p>

@endif
