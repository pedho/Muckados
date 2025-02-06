<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
    $nome = (isset($_POST["nome"]) && $_POST["nome"] != null) ? $_POST["nome"] : "";
    $tipo = (isset($_POST["tipo"]) && $_POST["tipo"] != null) ? $_POST["tipo"] : NULL;
    $legenda = (isset($_POST["legenda"]) && $_POST["legenda"] != null) ? $_POST["legenda"] : "";
    $vid = (isset($_POST["vid"]) && $_POST["vid"] != null) ? $_POST["vid"] : "";
    $vtipo = (isset($_POST["vtipo"]) && $_POST["vtipo"] != null) ? $_POST["vtipo"] : NULL;
    $vmarca = (isset($_POST["vmarca"]) && $_POST["vmarca"] != null) ? $_POST["vmarca"] : "";
    $vcor = (isset($_POST["vcor"]) && $_POST["vcor"] != null) ? $_POST["vcor"] : "";
    $vlegenda = (isset($_POST["vlegenda"]) && $_POST["vlegenda"] != null) ? $_POST["vlegenda"] : "";
    
} else if (!isset($id)) {

    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $nome = NULL;
    $tipo = NULL;
    $legenda = NULL;
    
} else if (!isset($vid)) {
    
    $vid = (isset($_GET["vid"]) && $_GET["vid"] != null) ? $_GET["vid"] : "";
    $tipo = NULL;
    $vmarca = NULL;
    $vcor = NULL;
    $vlegenda = NULL;
}
try {
	$con = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=postgres', 'postgres', 'pabd');
	
	if ($con) {
		echo "deu certo";
        $comando1 = $con->query("SELECT * FROM produto");
        $comando2 = $con->query("SELECT * FROM vestuario");
		
		while ($var_linha = $comando1->fetch()) {
			echo $var_linha[1] . " " . $var_linha[2] . "<br/>";	
		}

        echo "<hr/>";

        while ($var_linha = $comando2->fetch()) {
			echo $var_linha[1] . " " . $var_linha[2] . "<br/>";	
		}
	}
} catch (PDOException $e) {
	echo 'DEU ERRADO!!!' . $e;
}
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save_prod" && $nome != "") {
    try {
        $stmt = $con->prepare("INSERT INTO Produto (nome, tipo, legenda) VALUES (?, ?, ?)");
        $stmt->bindParam(1, $nome);
        $stmt->bindParam(2, $tipo);
        $stmt->bindParam(3, $legenda);
         
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados cadastrados com sucesso!";
                $id = null;
                $nome = null;
                $tipo = null;
                $legenda = null;
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
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save_vest" && $vtipo != "") {
    try {
        $stmt = $con->prepare("INSERT INTO Vestuario (tipo, marca, cor, legenda) VALUES (?, ?, ?, ?)");
        $stmt->bindParam(1, $vtipo);
        $stmt->bindParam(2, $vmarca);
        $stmt->bindParam(3, $vcor);
        $stmt->bindParam(4, $vlegenda);
         
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados cadastrados com sucesso!";
                $vid = null;
                $vtipo = null;
                $vmarca = null;
                $vcor = null;
                $vlegenda = null;
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
        <form action="?act=save_prod" method="POST" name="form1" >
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
         <input type="submit" value="Salvar" />
         <input type="reset" value="Novo" />
         <hr>

        <form action="?act=save_vest" method="POST" name="form2" >
          <h1>Adicionar vestuario</h1>
          <hr>
          <input type="hidden" name="vid" <?php
            if (isset($vid) && $vid != null || $vid != "") {
                echo "value=\"{$vid}\"";
            }
            ?>/>
          Tipo:
          <input type="text" name="vtipo" <?php
            if (isset($vtipo) && $vtipo != null || $vtipo != "") {
                echo "value=\"{$vtipo}\"";
            }
            ?>/>
          <br/>
          Marca:
          <input type="text" name="vmarca" <?php
            if (isset($vmarca) && $vmarca != null || $vmarca != "") {
                echo "value=\"{$vmarca}\"";
            }
            ?>/>
          <br/>
          Cor:
          <input type="text" name="vcor" <?php
            if (isset($vcor) && $vcor != null || $vcor != "") {
                echo "value=\"{$vcor}\"";
            }
            ?>/>
         <br/>
          Legenda:
           <input type="text" name="vlegenda" <?php
            if (isset($vlegenda) && $vlegenda != null || $vlegenda != "") {
                echo "value=\"{$vlegenda}\"";
            }
            ?>/>
         <br/>
         <input type="submit" value="Salvar" />
         <input type="reset" value="Novo" />
         <hr>

    </body>
</html>
