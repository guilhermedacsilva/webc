<?php
require_once "include_me.php";

if (!isLogged() || !isset($_POST['code'])) {
	echo 'WebC.exit';
	exit;
}


$executable = __DIR__ . '/user/'.$_SESSION['id'];
$source = $executable . '.c';

file_put_contents($source, $_POST['code']);

$errors = runGCC($source, $executable);
$forbidden = 0;

$console = "";
if ($errors) {
	$console = $errors;
} else {
	if (verifyFobiddenCode($_POST['code'])) {
		$console = runProgram($executable, $_POST['input']);
	} else {
		$forbidden = 1;
		$console = "O código contém um comando proibido.";
	}
}

saveAllUserData($_POST['code'],$_POST['input'], $console, $errors ? 1 : 0, $forbidden);

echo $console;
