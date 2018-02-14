<?php 
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=<?= Yii::$app->charset ?>" />
<title>Activación de cuenta</title>
</head>
<body>
	<header> </header>
	<section>
		<h1>Ingresar a cuenta</h1>

		<p>Bienvenido <?=$user?></p>
        <p>Usuario: <?= $email ?></p>
        <p>Password: <?= $password ?></p>
		<p>
			Para ingresar a tu cuenta hazlo desde este <a
				href='<?=$url?>'>link</a>
		</p>
		<p>-Equipo 2 Geeks one Monkey</p>
	</section>
	<footer> </footer>


</body>
</html>
