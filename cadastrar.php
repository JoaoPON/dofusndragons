<?php
require_once "conexao.php";
require_once "crud.php";

$dados = [];

if (isset($_POST['nome'])) $dados['nome'] = trim($_POST['nome']);
if (isset($_POST['email'])) $dados['email'] = trim($_POST['email']);
if (isset($_POST['foto_url']) && trim($_POST['foto_url']) !== "") {
    $dados['foto_url'] = trim($_POST['foto_url']);
} else {
    $dados['foto_url'] = "https://cdn-icons-png.flaticon.com/512/12225/12225935.png";
}
if (isset($_POST['senha'])) {
    $dados['senha'] = password_hash($_POST['senha'], PASSWORD_DEFAULT);
}

$conexao = conectar();
cadastrar($conexao, $dados);
?>
