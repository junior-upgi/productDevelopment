<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>開發案管理系統</title>

	<!--<link href="/css/app.css" rel="stylesheet">-->
	<link rel="stylesheet" href="/css/bootstrap.min.css">
	<link rel="stylesheet" href="/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="/css/bootstrap-datetimepicker.min.css">
	<link rel="stylesheet" href="/css/jquery-ui.min.css">
	<link rel="stylesheet" href="/css/jquery-ui.css">
    <link rel="stylesheet" href="/css/sweetalert.css">

	<!-- Fonts -->
	<!--<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>-->
	
	<script src="/script/jquery-3.1.0.min.js"></script>
	<script src="/script/jquery-ui.js?x=1"></script>
	<script src="/script/bootstrap.min.js"></script>
	<script src="/script/jquery.blockUI.js"></script>
	<script src="/script/jquery.form.min.js"></script>
	<script src="/script/bootstrap-datetimepicker.min.js"></script>
	<script src="/script/bootstrap-datetimepicker.zh-TW.js"></script>
	<script src="/script/sweetalert.js"></script>
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="/">統義玻璃 開發案管理系統</a>
				</div>
			</div>
		</div>
	</nav>
	<div class="container">
		<div class="content">
			@yield('content')
		</div>
	</div>
</body>
</html>