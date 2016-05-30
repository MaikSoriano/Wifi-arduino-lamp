<!--
	Codigo desarrollado por Miguel Soriano.
	Email: msorianosanz@gmail.com

	Eres libre de compartir y editar el código.
-->


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Bombilla</title>

<script type="text/javascript">
function cargar()
{
	tv=document.getElementById("tv");
	var bombilla = document.getElementById("bombilla");
		
	if(estado==0)
	{
		tv.innerHTML="Bombilla apagada"
		bombilla.src="resources/bombilla_apagada.jpg";
	}
	else if(estado==1)
	{
		tv.innerHTML="Bombilla encendida";
		bombilla.src="resources/bombilla_encendida.jpg";
	}
}
</script>

<style type="text/css">
#bombilla {
	height: 10%;
	width: 10%;
}
</style></head>

<body onLoad="cargar()">
<?php
	//require("php_serial.class.php"); ESTO EN WINDOWS NO FUNCIONA
//Datos para conectar a la BD
	$host="localhost"; //nombre del host
	$puerto="3306"; //puerto de la base de datos
	$usuario="bombillaUsu"; //usuario en la base de datos
	$pass="bombillaPas"; //contraseña del usuario
	$bd="casa"; //nombre de la base de datos
	
	//Se conecta al Host
	
	$conexion = mysql_connect($host,$usuario,$pass);
	if(!$conexion)
	{
		print("hubo un problema con la conexión al host");
	}
	else
	{	//Se conecta a la base de datos
		$conexionbd=mysql_select_db($bd,$conexion);
		if(!$conexion)
		{
			print("hubo un problema con la conexión a la base de datos");
		}
		else
		{
			//al cargar la pagina buscamos el estado
			$busqueda="select estado from arduino";
			$resultado=mysql_query($busqueda,$conexion); //hacemos la petición SQL a la base de datos
			if(!$resultado)
			{
				print("Error al hacer la consulta");
			}
			else
			{
				//Analizamos los datos devueltos
				$valor=mysql_fetch_row($resultado);
				//Enviamos datos al puerto serial (EN WINDOWS)
				'mode COM3: BAUD=9600 PARITY=N data=8 stop=1 xon=off';
				$fp = fopen("COM3:","w+");
				if(!$fp){ echo "error al abrir COM3";}
				else{
					fputs($fp, $valor[0]);
					fclose($fp);
				}
				/*
				//Enviamos datos al puerto serial (EN LINUX)
 				$serial = new phpSerial();
    			$serial->deviceSet('/dev/ttyACM0');
    			$serial->confBaudRate(9600);
     			$serial->confParity('none');
     			$serial->confCharacterLength(8);
     			$serial->confStopBits(1);
     			$serial->confFlowControl('none');
     			$serial->deviceOpen();
				
				if(!$serial){ echo "Error al abrir puerto";}
				else
				{
					$serial->sendMessage($valor[0]);
					$serial->deviceClose();
				}	*/		
			}
		}
	}
?>
<script type="text/javascript">//recoge el estado de la bombilla despues de la petición
estado="<?php print($valor[0]); ?>";
</script>

<h1>ENCENDER Y APAGAR UNA LAMPARA POR INTERNET</h1>



<img src="resources/bombilla_apagada.jpg" alt="bombilla_apagada" id="bombilla">


<?php

//Si venimos de haberle dado al boton entramos para cambiar el estado
if(isset($_POST['boton']))
{				
	//Si el valor devuelto de la base de datos es 0 lo cambiamos a 1 
	if($valor[0]==0)
		$orden= "UPDATE arduino SET estado = 1";
	else //Si el valor devuelto de la base de datos es distinto de 0 lo cambiamos a 0 
		$orden= "UPDATE arduino SET estado = 0";
		
		//Ejecutamos la petición a la base de datos según la condición de arriba
	$resultado=mysql_query($orden,$conexion);
	if(!$resultado)
	{
		print("Error al hacer la insercción");
	}
	//Actualizamos la página para ver los cambios realizados
	echo '<script> document.location.href = document.location.href;</script>';
}
?>

<form action="index.php" method="POST">
<input type="submit" value="cambiar" name="boton">
<div id="tv">
</div>

</body>
</html>
