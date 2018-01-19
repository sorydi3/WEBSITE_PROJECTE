#!/usr/bin/php-cgi
<?php
 $emmagatzemarSessions = exec("pwd") . "/tmp";
        ini_set('session.save_path', $emmagatzemarSessions);
        session_start();
        $connexio = oci_connect($_COOKIE['username'], $_COOKIE['pasword'], 'oracleps');
        if (!$connexio) {
            header('Location: practica_php.html');
        }
$nom = $_POST['nom'];
$cognom =$_POST['cognom'];
$alias=substr($nom,0,3).substr($cognom,0,3);
		
		$sql_comproba_codi="SELECT * FROM usuaris where alias='{$alias}' ORDER BY alias";
		$comanda_comproba_codi = oci_parse($connexio, $sql_comproba_codi);
		   $ok_comproba_codi=oci_execute($comanda_comproba_codi);
		   $fila_usuari = oci_fetch_array($comanda_comproba_codi, OCI_ASSOC + OCI_RETURN_NULLS);
		   $num=oci_num_rows($comanda_comproba_codi);
			if($num != 0){
				 die("USUARI EXISTEN HEU D'ELEGIR UN ALTRE ALIAS PER PODER CONTINUAR ");
			}
		
$dataalta = $_POST['dataalta'];
$emeil = $_POST['emeil'];
$telefon = $_POST['telefon'];
$ddlat = $_POST['ddlat'];
$ddlong = $_POST['ddlong'];
$saldo=5000;
//echo "$nom+ $cognom + $dataalta + $emeil + $telefon + $ddlat + $ddlong;".'<br/>';
// echo 'User: '.$_COOKIE['username'].'<br/>'.$_COOKIE['message'];
 
      $sentenciaSQL = "INSERT INTO usuaris
 (alias,nom,cognoms,dataAlta,email,telefon,DD_Lat,DD_Long,saldo)
	VALUES (:alias,:nom,:cognoms, TO_DATE(:dataalta,'DD/MM/YYYY'),:emeil,:telefon,:ddlat,:ddlong,:saldo)";
        // echo "per debug: <tt>" . $sentenciaSQL . "</tt><br>\n";
        $cursor = oci_parse($connexio, $sentenciaSQL);
		oci_bind_by_name($cursor,":alias",$alias);
		oci_bind_by_name($cursor,":nom",$nom);
		oci_bind_by_name($cursor,":cognoms",$cognom);
		oci_bind_by_name($cursor,":dataalta",$dataalta);
		oci_bind_by_name($cursor,":emeil",$emeil);
		oci_bind_by_name($cursor,":telefon",$telefon);
		oci_bind_by_name($cursor,":ddlat",$ddlat);
		oci_bind_by_name($cursor,":ddlong",$ddlong);
		oci_bind_by_name($cursor,":saldo",$saldo);
		  $ok = oci_execute($cursor);
		setcookie('error_message','');
		if($ok){
			 echo "<p>Nou personatge " . $_POST['alias'] . " inserit</p>\n";
		}else{
			$error = oci_error($cursor);
			setcookie('ErrorSentencia',$error['sqltext']);
			setcookie('ErrorCodi',$error[code]);
			setcookie('ErrorMissatge',$error['message']);
			setcookie('ErrorOffset',$error['offset']);
            header('Location:error_execucio.php');
		}
		  die($ok.'=+diallo');
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>

  </body>
</html>