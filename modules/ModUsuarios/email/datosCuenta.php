<?php 
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=<?= Yii::$app->charset ?>" />
<title>Datos de la cuenta</title>
</head>
<body>
	<header> </header>
	<section>
		<h1>Datos de la cuenta</h1>

		<p>Hola <?=$user?></p>
        <p>Estos son tus datos para acceder al sitio:</p>
        <p>
            <b>Usuario: </b> <?=$email?>
            <b>Contraseña: </b> <?=$password?>
        </p>
		<p>
			O pueden ingresar desde el siguiente link: <a
				href='<?=$url?>'>link</a>
		</p>
		<p>-Equipo 2 Geeks one Monkey</p>
	</section>
	<footer> </footer>


</body>
</html>
