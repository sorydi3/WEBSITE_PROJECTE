#!/usr/bin/php-cgi
<!DOCTYPE html>
<html>
<head>
  <title>cursa</title>
  <meta charset="utf-8" />
 <link rel="stylesheet" type="text/css" href="menu.css">
</head>

<body>
       <?php 
        $emmagatzemarSessions = exec("pwd") . "/tmp";
        ini_set('session.save_path', $emmagatzemarSessions);
        session_start();
        $connexio = oci_connect($_COOKIE['username'], $_COOKIE['pasword'], 'oracleps');
        if (!$connexio) {
            header('Location:practica_php.html');
		}
	   ?>
      <h1><?php 
	  //session_start;
	  if(isset($_COOKIE["username"])){//comproba si  ha entrat la contrasenya y l'usuari
	            echo $_COOKIE['error_message'].'<br/>'.'User: '.$_COOKIE['username'].'<br/>'.$_COOKIE['message'];//mostra l'usuari
	  }else{
		  header('Location:practica_php.html');
          exit();		  
	  }
	  ?></h1>
        <ul class=" container">
		  <li><a href="alta_usuari.php">AltaUsuari</a></li>
		  <li><a href="alta_cursa.php">AltaCursa</a></li>
		  <li><a href="alta_participants_curses.php">AltaParticipants</a></li>
		  <li><a href="venda_usuari.php">compra/venda</a></li>
		  <li><a href="consulta_participants_select.php">Consulta Cursa</a></li>
		  <li><a href="consulta_participants_select.php">Entra temps</a></li>
		  <li><a href="consulta_participants_select.php">Tanca cursa</a></li>
        </ul>
	  </div>
</body>
</html>
