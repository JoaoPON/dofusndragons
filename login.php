<?php
require_once "conexao.php";
require_once "crud.php";

$email = "";
$senha = "";

if (isset($_POST['email']) && isset($_POST['senha'])) {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
}

$conexao = conectar();
logar($conexao, $email, $senha);
?>
