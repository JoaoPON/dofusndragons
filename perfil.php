<?php
require_once "conexao.php";
require_once "crud.php";
verificarLogin();

$pdo = conectar();
$idUsuario = (int) $_SESSION['usuario_id'];
$usuario = buscarUsuario($pdo, $idUsuario);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-dark bg-gradiente">
            <a class="navbar-brand font-weight-bold text-warning" href="painel.php">DofusNDragons</a>
            <ul class="nav">
                <li class="nav-item"><a class="nav-link text-white" href="painel.php">Campanhas</a></li>
                <li class="nav-item"><a class="nav-link text-warning" href="logout.php">Sair</a></li>
            </ul>
        </nav>
    </header>

    <div class="container container-box" style="max-width: 600px;">
        <div class="text-center mb-3">
            <img src="<?= htmlspecialchars($usuario['foto_url']) ?>" alt="Avatar" class="avatar-perfil mb-2">
            <h3 class="font-weight-bold"><?= htmlspecialchars($usuario['nome']) ?></h3>
            <p class="text-muted small"><?= htmlspecialchars($usuario['email']) ?></p>
        </div>

        <hr class="linha-tema">

        <form method="post" action="atualizar_perfil.php">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome"
                       value="<?= htmlspecialchars($usuario['nome']) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" class="form-control" id="email" name="email"
                       value="<?= htmlspecialchars($usuario['email']) ?>" required>
            </div>

            <div class="form-group">
                <label for="foto_url">URL da Foto de Perfil</label>
                <input type="url" class="form-control" id="foto_url" name="foto_url"
                       value="<?= htmlspecialchars($usuario['foto_url']) ?>">
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-warning font-weight-bold px-4">Salvar Alterações</button>
            </div>
        </form>

        <hr class="linha-tema">

        <div class="text-center">
            <a href="excluir_conta.php" class="btn btn-outline-danger btn-sm"
               onclick="return confirm('Tem certeza que deseja excluir sua conta permanentemente?');">
                Excluir Minha Conta
            </a>
        </div>
    </div>

    <footer class="text-center text-muted py-4">
        <small>DofusNDragons — Gerenciador de Mesas de RPG</small>
    </footer>
</body>
</html>
