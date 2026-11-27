<?php
require_once "conexao.php";
require_once "crud.php";
verificarLogin();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pdo = conectar();
    $idUsuario = (int) $_SESSION['usuario_id'];
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $fotoUrl = trim($_POST['foto_url'] ?? '');

    atualizarPerfil($pdo, $idUsuario, $nome, $email, $fotoUrl);
}
?>
