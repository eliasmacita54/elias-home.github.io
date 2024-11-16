<?php
session_start();
require 'db.php';

$email = $_POST['email'];
$password = $_POST['password'];

// Verificar o usuário
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    // Gerar um código OTP e salvar no banco de dados
    $otp = rand(100000, 999999);
    $expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));

    $stmt = $pdo->prepare("INSERT INTO otp_codes (user_id, otp_code, expires_at) VALUES (?, ?, ?)");
    $stmt->execute([$user['id'], $otp, $expires_at]);

    // Enviar OTP para o e-mail do usuário
    mail($email, "Seu Código OTP", "Seu código de verificação é: $otp");

    // Armazena o ID do usuário na sessão
    $_SESSION['user_id'] = $user['id'];
    header('Location: verify_otp.php');
} else {
    echo "Email ou senha inválidos";
}
?>
