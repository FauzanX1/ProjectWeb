<?php
require_once '../control/connection.php';

session_start();
session_destroy();
header("Location: login.php");
exit();
?>