<?php
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}

echo "Bem-vindo ao seu painel!";
?>
