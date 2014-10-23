<?php
session_start();
define('CM','libs/cm/');
define('CM_LIB',CM.'lib/');
define('CM_MODE',CM.'mode/');
define('CM_MODE_CLIKE',CM_MODE.'clike/');
define('CM_ADDON',CM.'addon/');
define('CM_ADDON_EDIT',CM_ADDON.'edit/');
define('CM_ADDON_HINT',CM_ADDON.'hint/');

try {
	$pdo = new PDO('mysql:host=localhost;port=3306;dbname=webc', 'root', '123mudar', array( PDO::ATTR_PERSISTENT => false));
	$pdo->exec("SET NAMES 'utf8';");
	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (Exception $e) {
	echo 'Database error'.
	exit;
}

function pdoCreateLogin($ra, $name) {
	global $pdo;
	$stat = $pdo->prepare('INSERT INTO user(ra, name) VALUES (:ra, :name)');
	$stat->execute(array(':ra' => $ra, ':name' => $name));
	return $pdo->lastInsertId();
}

function pdoLogin($ra, $name) {
	global $pdo;
	$stat = $pdo->prepare('SELECT * FROM user WHERE ra = :ra');
	$stat->execute(array(':ra' => $ra));
	$result = $stat->fetchAll();
	if (count($result) == 0) {
		$id = pdoCreateLogin($ra, $name);
		return array('id' => $id,
			'ra' => $ra,
			'name' => $name);
	}
	return $result[0];
}

function isLogged() {
	return isset($_SESSION['name']) && isset($_SESSION['ra']);
}

function redirectIfNotLogged() {
	if (!isLogged()) {
		header('Location: index.php');
	}
}

function tryLogin() {
	$_SESSION = array();
	if (isset($_POST['name']) && isset($_POST['ra']) ) {
		$_SESSION = pdoLogin($_POST['ra'],$_POST['name']);
		header('Location: ide.php');
		exit;
	}
}

function runGCC($source, $target) {
	$descriptor = array(
	   2 => array("pipe", "w")  //stderr
	);

	$process = proc_open("gcc $source -o $target",
		$descriptor,
		$pipes
		);

	if (is_resource($process)) {
		$erros = stream_get_contents($pipes[2]);
		fclose($pipes[2]);
		proc_close($process);
		if ($erros) {
			echo $erros;
			return false;
		}
	}
	return true;
}

function runProgram($file) {
	$descriptor = array(
	   0 => array("pipe", "r"),  //stdin
	   1 => array("pipe", "w")  //stdout
	);

	$process = proc_open("$file",
		$descriptor,
		$pipes
		);

	$output = '';
	$outputOld = '';

	if (is_resource($process)) {
		stream_set_blocking($pipes[1], 0);

		for ($i = 0; $i < 3; $i++) {
			sleep(1);
			$output .= stream_get_contents($pipes[1]);
			if ($output != $outputOld) {
				break;
			}
		}
		$outputOld = $output;

		//fwrite($pipes[0], "88\n");
		
		fclose($pipes[0]);
		fclose($pipes[1]);
		proc_close($process);
		return $output;
	}
	return '';
}

function printProcessOutput($output) {
	foreach ($output as $line) {
		echo $line . "\n";
	}
}
