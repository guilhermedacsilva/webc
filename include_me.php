<?php
session_start();
define('CM','libs/cm/');
define('CM_LIB',CM.'lib/');
define('CM_MODE',CM.'mode/');
define('CM_MODE_CLIKE',CM_MODE.'clike/');
define('CM_ADDON',CM.'addon/');
define('CM_ADDON_EDIT',CM_ADDON.'edit/');
define('CM_ADDON_HINT',CM_ADDON.'hint/');

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=webc', 'root', '', array( PDO::ATTR_PERSISTENT => false));
$pdo->exec("SET NAMES 'utf8';");
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


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

