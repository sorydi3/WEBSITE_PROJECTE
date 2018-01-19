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
		<h1>ALTA PARTICIPANTS</h1>
		 <form action="incert_participants_c.php" method="post">
        <label>vehicle:</label><select name="vehicle">
<?php 
        $vehicle = 'SELECT codi FROM vehicles order by codi';
        $tab="        ";
        $comanda = oci_parse($connexio, $vehicle);
        oci_execute($comanda);
        while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
            echo $tab.$tab."<option value=\"" . $fila['CODI']. "\">" .$fila['CODI']."</option>\n";
        }
        unset($fila);
        echo $tab."</select><br>\n";
        echo $tab.'<label>personatge: </label><select name="personatge">' . "\n";
        $personatge = 'SELECT alias, usuari FROM personatges order by usuari';
        $comanda = oci_parse($connexio, $personatge);
        oci_execute($comanda);
        while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
            echo $tab.$tab."<option value=\"" . $fila['ALIAS'] . "\">" . $fila['USUARI'] . "</option>\n";
        }
		
		unset($fila);
        echo $tab."</select><br>\n";
        echo $tab.'<label>Cursa: </label><select name="cursa">' . "\n";
        $curses = 'SELECT codi, nom FROM curses order by nom';
        $comanda = oci_parse($connexio, $curses);
        oci_execute($comanda);
        while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
            echo $tab.$tab."<option value=\"" . $fila['CODI'] . "\">" . $fila['NOM'] . "</option>\n";
        }
?>
        </select><br>
        <input type = "submit" value="Inserir"/>
</form>
</div>
</body>
</html>
