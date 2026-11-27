<?php
require_once "conexao.php";
require_once "crud.php";
verificarLogin();

$pdo = conectar();
$idUsuario = (int) $_SESSION['usuario_id'];
$idCampanha = isset($_GET['id']) ? (int) $_GET['id'] : 0;

excluirCampanha($pdo, $idCampanha, $idUsuario);
?>
