<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Verify Email</title>
	@include('style')
</head>
<body>
	<div class="container">
		@if(session('success'))
			<p class="success">{{ session('success') }}</p>
		@endif
		<h2>Please verify your email address by clicking on the link sent to your email.</h2>
		<p><a href="{{ env('APP_URL') }}/account/email/resend-verification">Resend Email Notification</a></p>
	</div>
</body>
</html>