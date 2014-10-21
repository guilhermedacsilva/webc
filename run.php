<?php
require_once "include_me.php";

if (!isLogged() || !isset($_POST['code'])) exit;

