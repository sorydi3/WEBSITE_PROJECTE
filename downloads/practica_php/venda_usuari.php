#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
        <title>Cursa</title>
        <link rel="stylesheet" href="manu.css" type="text/css"> 
		<style>
		
		  label{

			  color:red;
			  font-size:20px;
	          font-family:Georgia,serif;
			   display:flex;
               flex-wrap:wrap;
               justify-content:center;
			   margin:7px;
		  }
		  
		  body{
			  margin-top:70px;
			   display:flex;
               flex-wrap:wrap;
               justify-content:center;
		  }
		</style>
    </head>
    <body>
	<div>
        <?php
        $emmagatzemarSessions = exec("pwd") . "/tmp";
        ini_set('session.save_path', $emmagatzemarSessions);
         $connexio = oci_connect($_COOKIE['username'], $_COOKIE['pasword'], 'oracleps');
        if (!$connexio) {
            header('Location:error_loggin.php');
        } ?>
		<h1>USUARI VENEDOR</h1>
		 <form action="compra_venda_P_V.php" method="post">
        <label>Nom usuari:</label><select name="usuari">
<?php 
        $usuari = 'SELECT alias,nom FROM usuaris order by nom';
        $tab="        ";
        $comanda = oci_parse($connexio, $usuari);
        oci_execute($comanda);
        while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
            echo $tab.$tab."<option value=\"" . $fila['NOM']. "\">" .$fila['NOM']."</option>\n";
        }
?>       
        </select><br> Venen:
		 <input type="radio" name="vehicles" value="Voiture"> Vehicles
         <input type="radio" name="personatges" value="Personnage"> Personatges:
		 <br>
        <input type = "submit" value="Inserir"/>
</form>
</div>
</body>
</html>