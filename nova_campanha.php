<?php
require_once "conexao.php";
require_once "crud.php";
verificarLogin();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pdo = conectar();
    $titulo = trim($_POST['titulo'] ?? '');
    $sistema = trim($_POST['sistema'] ?? '');
    $vagas = max(1, (int) ($_POST['vagas'] ?? 4));
    $descricao = trim($_POST['descricao'] ?? '');
    $idMestre = (int) $_SESSION['usuario_id'];

    criarCampanha($pdo, $titulo, $sistema, $vagas, $descricao, $idMestre);
}

$fotoUsuario = $_SESSION['usuario_foto'];
$nomeUsuario = $_SESSION['usuario_nome'];

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Campanha</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-dark bg-gradiente">
            <a class="navbar-brand font-weight-bold text-warning" href="painel.php">DofusNDragons</a>
            <ul class="nav">
                <li class="nav-item"><a class="nav-link text-white" href="painel.php">Campanhas</a></li>
                <li class="nav-item">
                    <a class="nav-link text-white d-flex align-items-center" href="perfil.php">
                        <img src="<?= $fotoUsuario ?>"
                            alt="Foto de <?= htmlspecialchars($nomeUsuario, ENT_QUOTES, 'UTF-8') ?>"
                            class="rounded-circle mr-2"
                            style="width: 28px; height: 28px; object-fit: cover;">
                        <span><?= htmlspecialchars($nomeUsuario, ENT_QUOTES, 'UTF-8') ?></span>
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link text-warning" href="logout.php">Sair</a></li>
            </ul>
        </nav>
    </header>

    <div class="container container-box" style="max-width: 650px;">
        <h2 class="text-center font-weight-bold">Criar Nova Campanha</h2>
        <hr class="linha-tema">

        <form method="post" action="nova_campanha.php">
            <div class="form-group">
                <label for="titulo">Título da Campanha</label>
                <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Ex.: Heróis de Incarnam" required>
            </div>

            <div class="form-row">
                <div class="form-group col-md-8">
                    <label for="sistema">Sistema / Ambientação</label>
                    <input type="text" class="form-control" id="sistema" name="sistema" placeholder="Ex.: Daggerheart / D&D5e" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="vagas">Número de Vagas</label>
                    <input type="number" class="form-control" id="vagas" name="vagas" min="1" max="15" value="4" required>
                </div>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição / Sinopse</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="4" placeholder="História inicial, dias de sessão, regras..."></textarea>
            </div>

            <hr class="linha-tema">
            <div class="text-center">
                <a href="painel.php" class="btn btn-secondary mr-2">Cancelar</a>
                <button type="submit" class="btn btn-warning font-weight-bold px-4">Salvar Campanha</button>
            </div>
        </form>
    </div>

    <footer class="text-center text-muted py-4">
        <small>DofusNDragons — Gerenciador de Mesas de RPG</small>
    </footer>
</body>
</html>
