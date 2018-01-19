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
	            echo 'User: '.$_COOKIE['username'].'<br/>'.$_COOKIE['message'];//mostra l'usuari
	  }else{
		  header('Location:practica_php.html');
          exit();		  
	  }
	  ?></h1>
        <ul class=" container">
		  <li><a href="http://bas.udg.edu/~u1939659/practica_php.html">holaaa</a>
		     <ul>
			    <li><a>holaaa</a></li>
				<li><a>holaaa</a></li>
				<li><a>holaaa</a></li>
			 </ul>
		  </li>
		  <li><a>holaaa</a>
		         <ul>
			    <li><a>holaaa</a></li>
				<li><a>holaaa</a></li>
				<li><a>holaaa</a></li>
			 </ul>
		  </li>
		  <li><a>holaaa</a>
		         <ul>
			    <li><a>holaaa</a></li>
				<li><a>holaaa</a></li>
				<li><a>holaaa</a></li>
			 </ul>
		  </li>
		  <li><a>holaaa</a></li>
		  <li><a>holaaa</a></li>
		  <li><a>holaaa</a></li>
		  <li><a>holaaa</a></li>
		  <li><a>holaaa</a></li>
        </ul>
	  </div>
</body>
</html>
