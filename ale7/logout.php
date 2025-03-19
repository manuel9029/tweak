<?php
require_once 'config.php';

// Destruir sesión
session_start();
session_unset();
session_destroy();

// Redirigir a login.php
header("Location: login.php");
exit;
?>
