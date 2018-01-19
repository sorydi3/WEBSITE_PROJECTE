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
		<h1>S.NOM CURSA ---</h1>
		 <form action="./Consulta_participants.php" method="post">
        <label>Nom cursa:</label><select name="nom_cursa">
<?php 
        $cursa = 'SELECT codi,nom FROM curses ORDER BY nom';
        $tab="        ";
        $comanda = oci_parse($connexio, $cursa);
        oci_execute($comanda);
        while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
            echo $tab.$tab."<option value=\"" . $fila['NOM']. "\">" .$fila['NOM']."</option>\n";
        }
?>
        </select><br>
		 <br>
        <input type = "submit" value="consulta"/>
</form>
</div>
      <div>
		<h1>----S.CODI CURSA</h1>
		 <form action="./mostra_participants_sin_temps.php" method="post">
        <label>Codi cursa:</label><select name="codi_cursa">
<?php 
        $cursa = 'SELECT codi,nom FROM curses ORDER BY nom';
        $tab="        ";
        $comanda = oci_parse($connexio, $cursa);
        oci_execute($comanda);
        while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
            echo $tab.$tab."<option value=\"" . $fila['CODI']. "\">" .$fila['CODI']."</option>\n";
        }
?>
        </select><br>
		 <br>
        <input type = "submit" value="consulta"/>
</form>
</div>
</body>
</html>