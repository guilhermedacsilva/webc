<?php
session_start();
define('CM','libs/cm/');
define('CM_LIB',CM.'lib/');
define('CM_MODE',CM.'mode/');
define('CM_MODE_CLIKE',CM_MODE.'clike/');
define('CM_ADDON',CM.'addon/');
define('CM_ADDON_EDIT',CM_ADDON.'edit/');
define('CM_ADDON_HINT',CM_ADDON.'hint/');
define('CM_THEME',CM.'theme/');

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

function pdoInsertCode($code, $input, $console, $compiled, $forbidden) {
	global $pdo;
	$stat = $pdo->prepare('INSERT INTO code(text,input,console,compiled,forbidden,date_create,user_id) VALUES (?,?,?,?,?,?,?)');
	$stat->execute(array(
		$code,
		$input,
		$console,
		$compiled,
		$forbidden,
		date("Y-m-d H:i:s"),
		$_SESSION['id']
	));
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

function saveAllUserData($code, $input, $console, $compiled, $forbidden) {
	if (!$code) {
		return;
	}
	if ($input == null) $input = "";
	if ($console == null) $console = "";
	pdoInsertCode($code, $input, $console, $compiled, $forbidden);

}

function runGCC($source, $target) {
	$descriptor = array(
	   2 => array("pipe", "w")  //stderr
	);

	$process = proc_open("gcc $source -o $target -Wall",
		$descriptor,
		$pipes
		);

	if (is_resource($process)) {
		$erros = stream_get_contents($pipes[2]);
		fclose($pipes[2]);
		proc_close($process);
		if ($erros) {
			return $erros;
		}
	}
	return "";
}

function runProgram($file, $data) {
	$descriptor = array(
	   0 => array("pipe", "r"),  //stdin
	   1 => array("pipe", "w")  //stdout
	);
	$process = proc_open("$file",
		$descriptor,
		$pipes
		);

	$output = '';

	if (is_resource($process)) {
		stream_set_blocking($pipes[0], 0);
		stream_set_blocking($pipes[1], 0);

		if ($data) {
			fwrite($pipes[0], $data."\n");
		}
		
		usleep(500000);
		$output = stream_get_contents($pipes[1]);

		fclose($pipes[0]);
		fclose($pipes[1]);
		proc_close($process);
		return $output;
	}
	return '';
}

function verifyFobiddenCode($code) {
	$regexArray = array("/# *include *<[^>]*>/", "/<[^>]*>/");
	$okArray = array('<stdio.h>');
	$resp = verifyForbidden($code, $regexArray, $okArray, NULL);

	$regexArray = array("/[a-zA-Z]+\\s*\\(/m", "/^[a-zA-Z]+/");
	$errorArray = array('fopen','remove','rename','freopen','fread','tmpfile','fwrite');
	$resp = $resp && verifyForbidden($code, $regexArray, NULL, $errorArray);
	return $resp;
}

function verifyForbidden($code, $regexArray, $okArray, $errorArray) {
	preg_match_all($regexArray[0], $code, $foundArray);
	
	if (!$foundArray) {
		return false;
	}

	foreach ($foundArray[0] as $found) {
		preg_match($regexArray[1], $found, $include);
		$include = $include[0];
		if ($okArray && !in_array($include, $okArray)) {
			return false;
		}
		if ($errorArray && in_array($include, $errorArray)) {
			return false;
		}
	}
	return true;
}
