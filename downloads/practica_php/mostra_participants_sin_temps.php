#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
        <title>CURSA</title>
        <link rel="stylesheet" href="exemple.css" type="text/css">
    </head>
    <body>
        <?php
        include 'exPHP_cap.php';

        $emmagatzemarSessions = exec("pwd") . "/tmp";
        ini_set('session.save_path', $emmagatzemarSessions);
        session_start();
        $connexio = oci_connect($_COOKIE['username'], $_COOKIE['pasword'], 'oracleps');
        if (!$connexio) {
            header('Location: error_logging.php');
        }
        echo "<h2>PARTICIPANTS CURSA</h2>";
        $sentenciaSQL = "select codi,vehicle,personatge FROM participantsCurses p left join curses c ON p.cursa=c.codi where codi='{$_POST['codi_cursa']}' AND temps IS NULL order by vehicle"; // construim comanda SQL
//echo $sentenciaSQL."<br>\n";   
        $comanda = oci_parse($connexio, $sentenciaSQL); // traduim comanda SQL
        $exit = oci_execute($comanda); // executem la comanda SQL
        if (!$exit) {
           $error = oci_error($comanda);
		   setcookie('ErrorSentencia',$error['sqltext']);
			setcookie('ErrorCodi',$error[code]);
			 setcookie('ErrorMissatge',$error['message']);
			  setcookie('ErrorOffset',$error['offset']);
			   header('Location:error_execucio.php');
        }
		//$fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS);
		//die(oci_num_rows($comanda)."  ".$fila['VEHICLE']);
        echo "<table>\n";
// primer posem les capcaleres de les columnes...
        $columnes = oci_num_fields($comanda); // compta quantes columnes retorna la consulta
        echo "<tr>\n";
        for ($i = 1; $i <= $columnes; $i++) {
            echo "<th>" . htmlentities(oci_field_name($comanda, $i), ENT_QUOTES) . "</th>\n";
        }
		
        echo "</tr>\n";
        //die("hellloooooooooooooooooooooooooow");
        while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
			//die(oci_num_rows($comanda));
            echo "<tr>\n";
            foreach ($fila as $columna) {
                echo "  <td>" . ($columna !== null ? htmlentities($columna, ENT_QUOTES) : "&nbsp;") . "</td>\n";
            }
            echo "</tr>\n";
        }
        echo "</table>\n";
        oci_free_statement($comanda);
        ?>
		 <div>
		<h3>SELECCIONA VEHICLE PER ENTRAR TEMPS</h3>
		 <form action="./entra_temps_participants_form.php" method="post">
		 <label>Temps: </label><input  name="temps" placeholder="0000"/><br>
         <label>Vehicle:</label><select name="vehicle">
<?php 
        $cursa = "select * FROM participantsCurses p left join curses c ON p.cursa=c.codi where codi='{$_POST['codi_cursa']}' AND temps IS NULL order by vehicle";
        $tab="        ";
        $comanda = oci_parse($connexio, $cursa);
        oci_execute($comanda);
        while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
            echo $tab.$tab."<option value=\"" . $fila['VEHICLE']. "\">" .$fila['VEHICLE']."</option>\n";
        }
?>
        </select><br>
		 <br>
		 <?php
		 //guarda codi cursa
          setcookie('codi_cursa',$_POST['codi_cursa']);
		 ?>
        <input type = "submit" value="EDITA"/>
</form>
</div>
    </body>
</html>