CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    foto_url VARCHAR(255) DEFAULT 'https://via.placeholder.com/100?text=DD'
);

CREATE TABLE campanhas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(120) NOT NULL,
    sistema VARCHAR(80) NOT NULL,
    vagas INT NOT NULL DEFAULT 4,
    descricao TEXT,
    id_mestre INT NOT NULL,
    FOREIGN KEY (id_mestre) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE campanha_jogadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_campanha INT NOT NULL,
    id_usuario INT NOT NULL,
    UNIQUE KEY uk_campanha_usuario (id_campanha, id_usuario),
    FOREIGN KEY (id_campanha) REFERENCES campanhas(id) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);
