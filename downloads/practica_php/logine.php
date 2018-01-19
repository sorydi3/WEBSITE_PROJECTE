#!/usr/bin/php-cgi
<?php
        $emmagatzemarSessions = exec("pwd") . "/tmp";
        ini_set('session.save_path', $emmagatzemarSessions);
        session_start();
        if (!empty($_POST['fuser'])) { // Arribem aquí per primera vegada
            $_SESSION['usuari'] = $_POST['fuser'];
			$_SESSION['password'] = $_POST['fpwd'];
			$usuari= $_POST['fuser'];
			$contrasenya=$_POST['fpwd'];
			setcookie('username',$usuari);
			setcookie('pasword',$contrasenya);
            // ara comprovem usuari i password intentant establir connexió amb Oracle	
            $conn = oci_connect($_SESSION['usuari'], $_SESSION['password'], 'oracleps');
            if ($conn) {
				session_regenerate_id(true);
				setcookie('message','Session inicied corectly!');
                header('Location:prueba.php');
				exit();
            }else{
				 $error = oci_error($connexio);
			     $_SESSION['message']="Error de Conexio!";
                 header('Location:practica_php.html');
				
			}
        }
?>