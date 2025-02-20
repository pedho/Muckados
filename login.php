<?php
// Conectar ao banco de dados para verificar se o usuário existe
session_start();
$host = 'localhost';  // ou seu host do banco de dados
$dbname = 'postgres'; // nome do banco de dados
$user = 'postgres'; // usuário do banco de dados
$pass = 'pabd';   // senha do banco de dados

try {
    $con = new PDO('pgsql:host=localhost;port=5432;dbname=postgres', 'postgres', 'pabd');

    // Simulando o login com o usuário e senha
    $usuario_teste = 'pedro';
    $senha_teste = 123;  // Senha que você cadastrou no banco

    // Verificar se o usuário e senha estão corretos
    $sql = "SELECT * FROM Login WHERE usuario = ? AND senha = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(1, $usuario_teste);
    $stmt->bindParam(2, $senha_teste);
    $stmt->execute();
    
    // Se o usuário for encontrado, armazene na sessão
    if ($stmt->rowCount() > 0) {
        $_SESSION['usuario_logado'] = $usuario_teste;
        echo "Login bem-sucedido! Usuário: " . $_SESSION['usuario_logado'];
    } else {
        echo "Usuário ou senha inválidos!";
    }
} catch (PDOException $e) {
    echo "Erro ao conectar com o banco de dados: " . $e->getMessage();
}
?>
