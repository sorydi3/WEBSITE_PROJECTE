#!/usr/bin/php-cgi
        <?php
        $emmagatzemarSessions = exec("pwd") . "/tmp";
        ini_set('session.save_path', $emmagatzemarSessions);
        $connexio =oci_connect($_COOKIE['username'], $_COOKIE['pasword'], 'oracleps');
        if (!$connexio) {
            header('Location:error_logging.php');
		}
		 $sql_comproba_codi="SELECT * FROM curses where codi='{$_POST['codi']}' ORDER BY codi";
		$comanda_comproba_codi = oci_parse($connexio, $sql_comproba_codi);
		   $ok_comproba_codi=oci_execute($comanda_comproba_codi);
		   $fila_vehicle = oci_fetch_array($comanda_comproba_codi, OCI_ASSOC + OCI_RETURN_NULLS);
		   $num=oci_num_rows($comanda_comproba_codi);
			if($num != 0){
				 die("CODI VEHICLE EXISTEN HEU D'ELEGIR UN ALTRE CODI PER PODER CONTINUAR ".$num);
			}
			// rebem les dades a $_POST
        $sentenciaSQL = "INSERT INTO curses
 (codi,nom,premi,inscripcio,iniciPrevist,iniciReal,millorTemps) 
	VALUES (:codi,:nom,:premi,:inscripcio,TO_DATE(:iniciprevist,'DD/MM/YYYY'),TO_DATE(:inicireal,'DD/MM/YYYY'), :millortemps)";
	    
        $comanda = oci_parse($connexio, $sentenciaSQL);
		$millortemps=null;
		$inicireal=null;
        oci_bind_by_name($comanda, ":millortemps", $millortemps);
		oci_bind_by_name($comanda, ":inicireal", $inicireal);
		oci_bind_by_name($comanda, ":codi", $_POST["codi"]);
        oci_bind_by_name($comanda, ":nom", $_POST["nom"]);
        oci_bind_by_name($comanda, ":iniciprevist", $_POST["iniciprevist"]);
        oci_bind_by_name($comanda, ":premi", $_POST["premi"]);
        oci_bind_by_name($comanda, ":inscripcio", $_POST["inscripcio"]);
        $exit = oci_execute($comanda);
        if ($exit) {
            echo "<p>Nou cursa inserit correctament " . $_POST['nom'] . " inserit</p>\n";
        } else{
            $error = oci_error($comanda);
			setcookie('ErrorSentencia',$error['sqltext']);
			setcookie('ErrorCodi',$error[code]);
			setcookie('ErrorMissatge',$error['message']);
			setcookie('ErrorOffset',$error['offset']);
            header('Location:error_execucio.php');
        }
    ?>
