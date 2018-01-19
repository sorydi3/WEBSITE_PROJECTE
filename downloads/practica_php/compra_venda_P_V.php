#!/usr/bin/php-cgi

<?php 
setcookie('personatge1',$_POST['personatges']);
setcookie('vehicle1',$_POST['vehicles']);

//die($_POST['personatges']." <--personatge// vehicle--> ".$_POST['vehicles']." //usuari--> ".$_POST['usuari']);
?>
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
		<h1>HOLA</h1>
		 <form action="actualitza_taulas_C_V_P.php" method="post">
<?php   
 $tab="        ";
if($_POST['vehicles'] !=""){
	  echo $tab.'<label>Codi: </label><input  name="codi_nou"  maxlength="10" placeholder="Codi/model_cotxe"/>' . "\n";
	 echo $tab.'<label>Vehicle/s Venedor: </label><select name="vehicle">' . "\n";
        $vehicle = "SELECT codi FROM vehicles where propietari='{$_POST['usuari']}' order by codi";
        $comanda = oci_parse($connexio, $vehicle);
        oci_execute($comanda);
        while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
            echo $tab.$tab."<option value=\"" . $fila['CODI']. "\">" .$fila['CODI']."</option>\n";
        }
		//$opcio=$_POST['vehicles'];
		//opcio vehicle
		//setcookie('opcio',$_POST['vehicle']);
        unset($fila);
	    echo $tab."</select><br>\n";
} else{
	    //$_POST['personatges'] !=""
	    echo $tab.'<label>Alias: </label><input  name="alias_nou" maxlength="10" placeholder="Alias/comprador"/>' . "\n";
        echo $tab.'<label>personatge/s Venedor: </label><select name="personatge">' . "\n";
        $personatge = "SELECT alias, usuari FROM personatges where usuari='{$_POST['usuari']}' order by usuari";
        $comanda = oci_parse($connexio, $personatge);
        oci_execute($comanda);
        while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
            echo $tab.$tab."<option value=\"" . $fila['ALIAS'] . "\">" . $fila['USUARI'] . "</option>\n";
        }
		unset($fila);
        echo $tab."</select><br>\n";
}
?>
<?php
echo $tab.'<label>COMPRADOR: </label><select name="comprador_usuari">' . "\n";
     $usuari = "SELECT alias,nom FROM usuaris where alias !='{$_COOKIE['usuari_venedor']}' order by nom";
        $tab="        ";
        $comanda = oci_parse($connexio, $usuari);
        oci_execute($comanda);
        while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
            echo $tab.$tab."<option value=\"" . $fila['ALIAS']. "\">" .$fila['NOM']."</option>\n";
        }
		unset($fila);

 ?>
        </select><br>
		 <label>Data Operacio: </label><input  name="dataoperacion" placeholder="DD/MM/YYYY"/><br>
		 <?php 
           setcookie('usuari_venedor',$_POST['usuari']);
		   setcookie('personatge1',$_POST['personatges']);
           setcookie('vehicle1',$_POST['vehicles']);
		 ?>
		 <br>
		 <br>
        <input type = "submit" value="submit"/>
</form>
</div>
</body>
</html>