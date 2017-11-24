<?php
	session_start();

	// para assegurar que o usuário não entrou diretamente pelo link da página sem realizar a autenticação
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		include('../conn.inc.php');

		// garante que o usuario não executará nenhum script malicioso no banco de dados
		$nome = mysqli_real_escape_string($conn, $_POST['nome']);
		$quantidade = mysqli_real_escape_string($conn, $_POST['quantidade']);
		
		// passa para maiusculo
		$nome = mb_strtoupper($nome, "UTF-8");

		// em caso de erros
		if (empty($nome) || empty($quantidade)) {
			header("Location: ../../cadastrar-item.php?cadastro=vazio");
			exit();
		}
		else {
			$sql = "SELECT * FROM estoque WHERE item='$nome';";
			$res = mysqli_query($conn, $sql);
			$row = mysqli_num_rows($res);

			// se o item já estiver cadastrado
			if ($row > 0) {
				header("Location: ../../cadastrar-item.php?cadastro=itemduplicado");
				exit();
			}
			else {	
				// insere no banco de dados
				$sql = "INSERT INTO estoque VALUES (default, '$nome', '$quantidade');"; 
				mysqli_query($conn, $sql); // executa a query				

				header("Location: ../../cadastrar-item.php?cadastro=sucesso");
				exit();
			}
		}
	}
	else {
		header("Location: ../../painel-admin.php");
		exit();
	}
?>
