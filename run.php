<?php
require_once "include_me.php";
//require_once "process_functions.php";

if (!isLogged() || !isset($_POST['code'])) {
	echo 'WebC.exit';
	exit;
}

$executable = __DIR__ . '/user/'.$_SESSION['id'];
$source = $executable . '.c';

file_put_contents($source, $_POST['code']);

$compiled = runGCC($source, $executable);

if ($compiled) {
	echo runProgram($executable);
}


