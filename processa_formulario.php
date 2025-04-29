<?php
// Habilita exibição de erros para facilitar o debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Captura os dados do formulário
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $dataNascimento = trim($_POST['data-nascimento']);
    $genero = trim($_POST['genero']);
    $tipo = trim($_POST['tipo']);
    $mensagem = trim($_POST['mensagem']);

    // Validação básica dos campos
    if (empty($nome) || empty($email) || empty($telefone) || empty($dataNascimento) || empty($genero) || empty($tipo) || empty($mensagem)) {
        die("Por favor, preencha todos os campos obrigatórios.");
    }

    // Conecta ao banco SQLite (arquivo local chamado "formulario.db")
    $db = new SQLite3('formulario.db');

    // Cria a tabela 'Inscrito' caso ainda não exista
    $db->exec("
        CREATE TABLE IF NOT EXISTS Inscrito (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nome TEXT NOT NULL,
            email TEXT NOT NULL,
            telefone TEXT NOT NULL,
            data_nascimento TEXT NOT NULL,
            genero TEXT NOT NULL,
            tipo TEXT NOT NULL,
            mensagem TEXT NOT NULL
        )
    ");

    // Prepara o comando SQL para inserção dos dados
    $stmt = $db->prepare("
        INSERT INTO Inscrito (nome, email, telefone, data_nascimento, genero, tipo, mensagem)
        VALUES (:nome, :email, :telefone, :data_nascimento, :genero, :tipo, :mensagem)
    ");

    // Faz o bind dos parâmetros
    $stmt->bindValue(':nome', $nome, SQLITE3_TEXT);
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->bindValue(':telefone', $telefone, SQLITE3_TEXT);
    $stmt->bindValue(':data_nascimento', $dataNascimento, SQLITE3_TEXT);
    $stmt->bindValue(':genero', $genero, SQLITE3_TEXT);
    $stmt->bindValue(':tipo', $tipo, SQLITE3_TEXT);
    $stmt->bindValue(':mensagem', $mensagem, SQLITE3_TEXT);

    // Executa a inserção
    if ($stmt->execute()) {
        echo "Inscrição realizada com sucesso!";
    } else {
        echo "Erro ao realizar inscrição.";
    }

    // Fecha a conexão
    $db->close();
} else {
    echo "Método de envio inválido.";
}
?>
