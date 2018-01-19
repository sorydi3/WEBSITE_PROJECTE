#!/usr/bin/php-cgi
<?php 
$emmagatzemarSessions = exec("pwd") . "/tmp";
        ini_set('session.save_path', $emmagatzemarSessions);
         $connexio = oci_connect($_COOKIE['username'], $_COOKIE['pasword'], 'oracleps');
        if (!$connexio) {
            header('Location:error_loggin.php');
        }
 $data=$_POST['Data_real'];
 //die($data);
	$sql_tanca_cursa="UPDATE curses set iniciReal=TO_DATE('{$data}','DD/MM/YYYY') WHERE codi='{$_POST['codi_cursa']}'";
	$comanda_update_cursa = oci_parse($connexio, $sql_tanca_cursa);
	$ok_update_tancat=oci_execute($comanda_update_cursa);
	//die($ok_update_tancat);
			if($ok_update_tancat){
				echo " CURSA TANCAT CORECTAMENT <br> <br>";
			}else{
				$error = oci_error($comanda_update_cursa);
			    setcookie('ErrorSentencia',$error['sqltext']);
			    setcookie('ErrorCodi',$error[code]);
			    setcookie('ErrorMissatge',$error['message']);
			    setcookie('ErrorOffset',$error['offset']);
			    header('Location:error_execucio.php');
			}
 ?>