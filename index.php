<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
    $nome = (isset($_POST["nome"]) && $_POST["nome"] != null) ? $_POST["nome"] : "";
    $tipo = (isset($_POST["tipo"]) && $_POST["tipo"] != null) ? $_POST["tipo"] : NULL;
    $legenda = (isset($_POST["legenda"]) && $_POST["legenda"] != null) ? $_POST["legenda"] : "";
    $img = (isset($_POST["img"]) && $_POST["img"] != null) ? $_POST["img"] : NULL;
} else if (!isset($id)) {
    // Se não se não foi setado nenhum valor para variável $id
    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $nome = NULL;
    $tipo = NULL;
    $legenda = NULL;
    $img = NULL;
}
try {
	$con = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=postgres', 'postgres', 'pabd');
	
	if ($con) {
		echo "deu certo";
        $comando1 = $con->query("SELECT * FROM produto");
		
		while ($var_linha = $comando1->fetch()) {
			echo $var_linha[1] . " " . $var_linha[2] . " " . $var_linha[3] . " " . "<img src="$var_linha[4]">" . "<br/>";	
		}
	}
} catch (PDOException $e) {
	echo 'DEU ERRADO!!!' . $e;
}
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $nome != "") {
    try {
        $stmt = $con->prepare("INSERT INTO Produto (nome, tipo, legenda, img) VALUES (?, ?, ?, ?)");
        $stmt->bindParam(1, $nome);
        $stmt->bindParam(2, $tipo);
        $stmt->bindParam(3, $legenda);
        $stmt->bindParam(4, $img);
         
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados cadastrados com sucesso!";
                $id = null;
                $nome = null;
                $tipo = null;
                $legenda = null;
                $img = null;
            } else {
                echo "Erro ao tentar efetivar cadastro";
            }
        } else {
               throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: " . $erro->getMessage();
    }
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Muckados</title>
    </head>
    <body>
        <form action="?act=save" method="POST" name="form1" >
          <h1>Adicionar produtos</h1>
          <hr>
          <input type="hidden" name="id" <?php
            if (isset($id) && $id != null || $id != "") {
                echo "value=\"{$id}\"";
            }
            ?>/>
          Nome:
          <input type="text" name="nome" <?php
            if (isset($nome) && $nome != null || $nome != "") {
                echo "value=\"{$nome}\"";
            }
            ?>/>
          <br/>
          Tipo:
          <input type="text" name="tipo" <?php
            if (isset($tipo) && $tipo != null || $tipo != "") {
                echo "value=\"{$tipo}\"";
            }
            ?>/>
          <br/>
          Legenda:
          <input type="text" name="legenda" <?php
            if (isset($legenda) && $legenda != null || $legenda != "") {
                echo "value=\"{$legenda}\"";
            }
            ?>/>
         <br/>
          Endereço da img na pasta:
           <input type="text" name="img" <?php
            if (isset($img) && $img != null || $img != "") {
                echo "value=\"{$img}\"";
            }
            ?>/>
         <br/>
         <input type="submit" value="Salvar" />
         <input type="reset" value="Novo" />
         <hr>

    </body>
</html>
