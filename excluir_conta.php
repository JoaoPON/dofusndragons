<?php
require_once "conexao.php";
require_once "crud.php";
verificarLogin();

$pdo = conectar();
$idUsuario = (int) $_SESSION['usuario_id'];

excluirConta($pdo, $idUsuario);
?>
