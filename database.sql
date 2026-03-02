-- Criação da tabela de Ocorrências

CREATE TABLE IF NOT EXISTS ocorrencia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_aluno VARCHAR(255) NOT NULL,
    turma VARCHAR(100) NOT NULL,
    professor_responsavel VARCHAR(255) NOT NULL,
    causa TEXT NOT NULL,
    nivel_punicao ENUM('Leve', 'Media', 'Grave', 'Suspensao') NOT NULL,
    data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela: Cursos
CREATE TABLE IF NOT EXISTS curso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    status ENUM('Ativo', 'Inativo') DEFAULT 'Ativo'
);

-- Tabela: Colaborador (Status)
CREATE TABLE IF NOT EXISTS colaborador (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    nif VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    setor VARCHAR(100) NOT NULL,
    status ENUM('Ativo', 'Inativo') DEFAULT 'Ativo',
    permissao ENUM('Admin', 'Professor', 'Usuario') DEFAULT 'Usuario'
);

-- Tabela: Turmas
CREATE TABLE IF NOT EXISTS turma (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    periodo ENUM('Manha', 'Tarde', 'Noite', 'Integral') NOT NULL,
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    curso_id INT NOT NULL,
    colaborador_id INT NULL,
    FOREIGN KEY (curso_id) REFERENCES curso(id) ON DELETE CASCADE,
    FOREIGN KEY (colaborador_id) REFERENCES colaborador(id) ON DELETE SET NULL
);

-- Tabela: Alunos
CREATE TABLE IF NOT EXISTS aluno (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    matricula VARCHAR(50) NOT NULL UNIQUE,
    data_entrada DATE NOT NULL,
    data_saida DATE NULL,
    curso_id INT NOT NULL,
    turma_id INT NULL,
    FOREIGN KEY (curso_id) REFERENCES curso(id) ON DELETE CASCADE,
    FOREIGN KEY (turma_id) REFERENCES turma(id) ON DELETE SET NULL
);
