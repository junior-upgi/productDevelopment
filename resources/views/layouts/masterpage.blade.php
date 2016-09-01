<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>開發案管理系統</title>
	<script>
		var url = "{{url('/')}}";
	</script>

	<!--<link href="/css/app.css" rel="stylesheet">-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="{{url('/')}}/css/bootstrap.css">
	<!--<link rel="stylesheet" href="{{url('/')}}/css/bootstrap-theme.css">-->
	
	<link rel="stylesheet" href="{{url('/')}}/css/bootstrap-datetimepicker.min.css">
	<link rel="stylesheet" href="{{url('/')}}/css/jquery-ui.css">
	<link rel="stylesheet" href="{{url('/')}}/css/sweetalert.css">
	<!-- Fonts -->
	<!--<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>-->
	
	<script src="{{url('/')}}/script/jquery-3.1.0.min.js"></script>
	<script src="{{url('/')}}/script/jquery-ui.js?x=1"></script>
	<script src="{{url('/')}}/script/sweetalert.js"></script>
	<script src="{{url('/')}}/script/bootstrap.js"></script>
	<script src="{{url('/')}}/script/jquery.blockUI.js"></script>
	<script src="{{url('/')}}/script/jquery.form.min.js"></script>
	<script src="{{url('/')}}/script/bootstrap-datetimepicker.min.js"></script>
	<script src="{{url('/')}}/script/bootstrap-datetimepicker.zh-TW.js"></script>
	<script src="{{url('/')}}/script/master.js"></script>
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
					<a class="navbar-brand" href="/" style="padding:5px;">
						<img alt="UPGI" src="{{url('/')}}/img/upgi.png" style="height:40px;"/>
					</a>
					<a class="navbar-brand" href="/">
						統義玻璃 開發案管理系統
					</a>
				</div>
				<div class="collapse navbar-collapse navbar-left">
					<div class="nav navbar-nav">
						<li>
							<a href="{{url('/')}}/Project/ProjectList">專案管理</a>
						</li>
						<li>
							<a href="{{url('/')}}/SysOption/StaffList">人員資料維護</a>
						</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								報表<span class="caret"></span>
							</a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{url('/')}}/Report/ProjectExecuteRate">專案進度表</a></li>
							</ul>
						</li>
					</div>
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