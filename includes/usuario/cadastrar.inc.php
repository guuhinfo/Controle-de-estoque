<?php
	session_start();

	// para assegurar que o usuário não entrou diretamente pelo link da página sem realizar a autenticação
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		include('../conn.inc.php');

		// garante que o usuario não executará nenhum script malicioso no banco de dados
		$nome = mysqli_real_escape_string($conn, $_POST['nome']);
		$tipo = mysqli_real_escape_string($conn, $_POST['tipo']);
		$usuario = mysqli_real_escape_string($conn, $_POST['usuario']);
		$senha = mysqli_real_escape_string($conn, $_POST['senha']);

		// em caso de erros
		if (empty($nome) || empty($tipo) || empty($usuario) || empty($senha)) {
			header("Location: ../../cadastrar-usuario.php?signup=vazio");
			exit();
		}
		else {
			$sql = "SELECT * FROM usuarios WHERE usuario='$usuario';";
			$res = mysqli_query($conn, $sql);
			$row = mysqli_num_rows($res);

			// se o usuário já estiver cadastrado
			if ($row > 0) {
				header("Location: ../../cadastrar-usuario.php?signup=usuarioinvalido");
				exit();
			}
			else {	
				$hashSenha = password_hash($senha, PASSWORD_DEFAULT); // codifica a senha

				// insere no banco de dados
				$sql = "INSERT INTO usuarios VALUES (default, '$nome', '$usuario', '$hashSenha', '$tipo');"; 
				mysqli_query($conn, $sql); // executa a query

				header("Location: ../../cadastrar-usuario.php?signup=sucesso");

				exit();
			}
		}
	}
	else {
		header("Location: ../painel-admin.php");
		exit();
	}
?>
