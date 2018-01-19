#!/usr/bin/php-cgi
<?php
        $emmagatzemarSessions = exec("pwd") . "/tmp";
        ini_set('session.save_path', $emmagatzemarSessions);
        $connexio = oci_connect($_COOKIE['username'], $_COOKIE['pasword'], 'oracleps');
        if (!$connexio) {
            header('Location:practica_php.html');
			exit();
		}
?>
<!DOCTYPE html>
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>  
<h2>PHP Form Validation Example</h2>
<p><span class="error">*camps obligatoris.</span></p>
<form method="post" action="./insert_usuari.php">  
  Nom: <input type="text" name="nom" placeholder="alias" required value="ibra">
  <br><br>
  Cognom: <input type="text" name="cognom" required placeholder="Cognom" value="ibra">
  <br><br>
  dataAlta: <input name="dataalta" placeholder="dataAlta" required value="2323">
  <br><br>
   Emeil: <input type="email" name="emeil" placeholder="emeil" required value="ibra@hotmail.com">
  <br><br>
   Telefon: <input type="tel" name="telefon" placeholder="telefon" required value="030390" >
  <br><br>
   DD_Lat: <input type="number" name="ddlat" placeholder="DD_Lat" required value="0499">
  <br><br>
   DD_long: <input type="number_format" name="ddlong" placeholder="DD_long" required value="0349">
  <br><br>
   Saldo: <input type="number_format" name="saldo" placeholder="Saldo Adicional"  value="20">
  <br><br>
  <input type="submit" name="submit" value="Submit">  
</form>
</body>
</html>