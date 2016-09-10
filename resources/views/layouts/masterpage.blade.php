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
	<script src="{{url('/')}}/script/master.js?x=1"></script>
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
					<a class="navbar-brand" href="{{url('/')}}/" style="padding:5px;">
						<img alt="UPGI" src="{{url('/')}}/img/upgi.png" style="height:40px;"/>
					</a>
					<a class="navbar-brand" href="{{url('/')}}/">
						統義玻璃 開發案管理系統
					</a>
				</div>
				@php
					if (Auth::check()) {
						Auth::user()->authorization === '1' ? $UserRole = ' disabled' : $UserRole = '';
					}
				@endphp
				<div class="collapse navbar-collapse navbar-left">
					<div class="nav navbar-nav">
						<li class="@yield('project')">
							<a href="{{url('/')}}/Project/ProjectList">專案管理</a>
						</li>
						@if (Auth::check())
							@if (Auth::user()->authorization === '99')
								<li class="@yield('sysoption')">
									<a href="{{url('/')}}/SysOption/StaffList">人員資料維護</a>
								</li>
							@endif
						@endif
						<li class="dropdown @yield('report')">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								報表<span class="caret"></span>
							</a>
							<ul class="dropdown-menu" role="menu">
								<li class="@yield('report.projectExecuteRate')"><a href="{{url('/')}}/Report/ProjectExecuteRate">專案進度表</a></li>
								<li class="@yield('report.productExecuteRate')"><a href="{{url('/')}}/Report/ProductExecuteRate">產品進度表</a></li>
							</ul>
						</li>
					</div>
				</div>
				<div class="collapse navbar-collapse navbar-right">
					<div class="nav navbar-nav">
						<li class="dropdown">
							@if(Auth::check())
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
									<span class="glyphicon glyphicon-user"></span>
								</a>
								<ul class="dropdown-menu" role+"menu">
									<span style="margin-left:20px;">{{ Auth::user()->mobileSystemAccount}}您好</span>
									<li class="divider"></li>
									<li><a href="{{url('/')}}/logout">登出</a></li>
								</ul>
							@else
								<a href="{{url('/')}}/login">登入</a>
							@endif
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