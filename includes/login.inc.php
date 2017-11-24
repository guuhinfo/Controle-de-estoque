<?php
	session_start();

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		include('conn.inc.php');

		// garante que o usuario não executará nenhum script malicioso no banco de dados
		$usuario = mysqli_real_escape_string($conn, $_POST['usuario']);
		$senha = mysqli_real_escape_string($conn, $_POST['senha']);

		// se não informou o usuario e/ou a senha
		if (empty($usuario) || empty($senha)) {
			header("Location: ../index.html?login=empty");
			exit();
		}
		else {
			$sql = "SELECT * FROM usuarios WHERE usuario='$usuario';";
			$res = mysqli_query($conn, $sql);
			$row = mysqli_num_rows($res);

			// se o usuario não está cadastrado
			if ($row < 1) {
				header("Location: ../index.html?login=erro");
				exit();
			}
			else {
				if ($row = mysqli_fetch_assoc($res)) {
					$senhaCorreta = password_verify($senha, $row['senha']); // verifica se é a senha correta (codificada)

					// senha errada
					if ($senhaCorreta == false) {
						header("Location: ../index.html?login=erro");
						exit();
					}
					elseif ($senhaCorreta == true) {
						$_SESSION['id'] = $row['id'];
						$_SESSION['nome'] = $row['nome'];
						$_SESSION['usuario'] = $row['usuario'];
						$_SESSION['tipo'] = $row['tipo'];
						
						if ($row['tipo'] == "admin") {
							header("Location: ../painel-admin.php?login=sucesso");
						}
						else {
							header("Location: ../painel.php?login=sucesso");
						}
					}
				}
			}
		}
	}
	else {
		header("Location: ../index.html?login=error");
		exit();
	}
?>
