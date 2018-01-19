#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
        <title>Exemple molt simple PHP/Oracle - alta personatges</title>
        <link rel="stylesheet" href="exemple.css" type="text/css"> 
    </head>
    <body>
        <?php
        $emmagatzemarSessions = exec("pwd") . "/tmp";
        ini_set('session.save_path', $emmagatzemarSessions);
        session_start();
        $connexio = oci_connect($_COOKIE['username'], $_COOKIE['pasword'], 'oracleps');
        if (!$connexio) {
            header('Location: error_logging.php');
        }

// rebem les dades a $_POST
$temps=null;
        $sentenciaSQL = "INSERT INTO participantsCurses
 (cursa,vehicle,personatge,temps)"." 
	VALUES (:cursa,:vehicle,:personatge,:temps)";
        $comanda = oci_parse($connexio, $sentenciaSQL);
        oci_bind_by_name($comanda, ":cursa", $_POST["cursa"]);
        oci_bind_by_name($comanda, ":vehicle", $_POST["vehicle"]);
        oci_bind_by_name($comanda, ":personatge", $_POST["personatge"]);
        oci_bind_by_name($comanda, ":temps",$temps);
        $exit = oci_execute($comanda);
        if ($exit) {
            echo "<p>Nou participant " . $_POST['alias'] . " inserit</p>\n";
        } else {
            $error = oci_error($comanda);
			setcookie('ErrorSentencia',$error['sqltext']);
			setcookie('ErrorCodi',$error[code]);
			setcookie('ErrorMissatge',$error['message']);
			setcookie('ErrorOffset',$error['offset']);
            header('Location:error_execucio.php');
        }
        ?>
    </body>
</html>
