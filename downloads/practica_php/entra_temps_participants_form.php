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
        <?php
        $emmagatzemarSessions = exec("pwd") . "/tmp";
        ini_set('session.save_path', $emmagatzemarSessions);
         $connexio = oci_connect($_COOKIE['username'], $_COOKIE['pasword'], 'oracleps');
        if (!$connexio) {
            header('Location:error_logging.php');
        } 
		 $sql_update_temps=" UPDATE (select * FROM participantsCurses p left join curses c ON p.cursa=c.codi where codi='{$_COOKIE['codi_cursa']}' AND temps IS NULL)SET temps='{$_POST['temps']}' WHERE codi='{$_COOKIE['codi_cursa']}' and vehicle='{$_POST['vehicle']}'";
		  $comanda = oci_parse($connexio, $sql_update_temps);
        $ok=oci_execute($comanda);
        if($ok){
			echo "TEMPS ENTRAT CORECTAMENT <br><br>";
		}else{
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