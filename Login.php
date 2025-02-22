<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = (isset($_POST["nome"]) && $_POST["nome"] != null) ? $_POST["nome"] : "";
    $senha = (isset($_POST["senha"]) && $_POST["senha"] != null) ? $_POST["senha"] : "";
    
} else if (!isset($nome)) {
    $nome = (isset($_GET["nome"]) && $_GET["nome"] != null) ? $_GET["nome"] : "";
    $nome = NULL;
    $senha = NULL;
    
}

try {
    $conexao = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=postgres', 'postgres', 'pabd');
} catch (PDOException $erro) {
    echo "Erro na conexão:" . $erro->getMessage();
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $nome != "") {
    try {
        $stmt = $conexao->prepare("SELECT * FROM login WHERE usuario = :nome AND senha = :senha");
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":senha", $senha);
        $stmt->execute();
         
        if ($stmt->rowCount() > 0) {
           
            $_SESSION['usuario'] = $nome;
            header("Location: pagina_protegida.php");
            exit();
        } else {
            
            $erro = "Usuário ou senha incorretos.";
        }
    } catch (PDOException $erro) {
        echo "Erro na conexão: " . $erro->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Mukados</title>
        <link rel="stylesheet" href="style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    </head>
    <body>
        <form style="width: 40%;margin:auto;margin-top:10%" action="?act=save" method="POST" name="form1" >
          <h1 style="font-style: normal;">Login</h1>
          <div style="display: flex;">
            Usuário:    
            <div><input type="text" name="nome" /></div>
          </div>
          <div style="display:flex">
            Senha: 
            <div><input type="password" name="senha" /></div>
          </div>
          <?php
            if (isset($erro)) {
                echo "<p style='color:red;'>$erro</p>";
            }
          ?>
         <input class="entrar" type="submit" value="entrar" />
        </form>
    </body>
</html>