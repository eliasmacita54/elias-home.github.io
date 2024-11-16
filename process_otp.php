<?php
session_start();
require 'db.php';

$user_id = $_SESSION['user_id'];
$otp_code = $_POST['otp_code'];

// Verifica o código OTP no banco de dados
$stmt = $pdo->prepare("SELECT * FROM otp_codes WHERE user_id = ? AND otp_code = ? AND expires_at > NOW()");
$stmt->execute([$user_id, $otp_code]);
$otp = $stmt->fetch();

if ($otp) {
    // Apaga o OTP após a verificação para segurança
    $stmt = $pdo->prepare("DELETE FROM otp_codes WHERE user_id = ?");
    $stmt->execute([$user_id]);

    // Define o usuário como autenticado
    $_SESSION['authenticated'] = true;
    header('Location: dashboard.php');
} else {
    echo "Código OTP inválido ou expirado";
}
?>
