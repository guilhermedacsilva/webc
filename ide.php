<?php
require_once "include_me.php";
redirectIfNotLogged();

?>
<!DOCTYPE html>
<html>
<head>
	<title>Web C</title>
	<meta charset="UTF-8">
	
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/signin.css">
	<link rel="stylesheet" href="css/ide.css">
	<link rel="stylesheet" href="<?= CM_LIB ?>codemirror.css">
	<link rel="stylesheet" href="<?= CM_ADDON_HINT ?>show-hint.css">
	
	<script src="js/jquery-2.1.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="<?= CM_LIB ?>codemirror.js"></script>
	<script src="<?= CM_ADDON_EDIT ?>matchbrackets.js"></script>
	<script src="<?= CM_ADDON_HINT ?>show-hint.js"></script>
	<script src="<?= CM_MODE_CLIKE ?>clike.js"></script>
	<script src="js/webc.js"></script>

</head>
<body>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">Web C</a>
		</div>
		<div class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<li class="active"><a href="#">IDE</a></li>
				<li><a href="#">Compilar e Rodar [F10]</a></li>
			</ul>
		</div><!--/.nav-collapse -->
	</div>
</div>

<div class="container">
	<form role="form" method="post">
		<textarea id="userCode" class="form-control" style="height: 60vh" placeholder="Insira o código fonte aqui."></textarea>
		<br>
		<textarea id="output" class="form-control" rows="4" placeholder="Console" disabled></textarea>
	</form>
</div>

<script>
WebC.console = $('#output');
WebC.editor = CodeMirror.fromTextArea(document.getElementById('userCode'), {
	lineNumbers: true,
	matchBrackets: true,
	mode: "text/x-csrc",
	indentUnit: 4,
	indentWithTabs: true,
	extraKeys: WebC.codemirrorExtraKeys
});
</script>

</body>
</html>
