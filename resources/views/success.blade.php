@if(session('success1'))
<p class="success">Signed up successfully. Please log in.</p>
@endif


@if(session('success2'))
<p class="success">Logged in successfully.</p>
@endif

@if(session('success3'))
<p class="success">An email was sent to {{session('email')}}! Please check your email.</p>
@endif


@if(session('success4'))
<p class="success">Password changed successfully.</p>
@endif


@if(session('success5'))
<p class="success">Profile information changed successfully.</p>
@endif
