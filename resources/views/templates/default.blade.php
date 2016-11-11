<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Chatty</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
	@include('templates.patials.navigation')

	<div class="container">
		@include('templates.patials.alerts')

		@yield('content')
	</div>
</body>
</html>