#!/usr/bin/php-cgi
<?php
        $emmagatzemarSessions = exec("pwd") . "/tmp";
        ini_set('session.save_path', $emmagatzemarSessions);
         $connexio = oci_connect($_COOKIE['username'], $_COOKIE['pasword'], 'oracleps');
        if (!$connexio) {
            header('Location:error_logging.php');
        }
		//comprova codi/alias si es exiten o no
     if(isset($_POST['vehicle'])){
	 $sql_comproba_codi="SELECT * FROM vehicles where codi='{$_POST['codi_nou']}' ORDER BY codi";
		$comanda_comproba_codi = oci_parse($connexio, $sql_comproba_codi);
		   $ok_comproba_codi=oci_execute($comanda_comproba_codi);
		   $fila_vehicle = oci_fetch_array($comanda_comproba_codi, OCI_ASSOC + OCI_RETURN_NULLS);
		   $num=oci_num_rows($comanda_comproba_codi);
			if($num != 0){
				 die("CODI VEHICLE EXISTEN HEU D'ELEGIR UN ALTRE CODI PER PODER CONTINUAR ".$num);
			}
	 }else{
		 $sql_comproba_alias=" select * from personatges where alias='{$_POST['alias_nou']}'";
		$comanda_comproba_alias = oci_parse($connexio, $sql_comproba_alias);
		   $ok_comproba_alias=oci_execute($comanda_comproba_alias);
		    $fila_personatge = oci_fetch_array($comanda_comproba_codi, OCI_ASSOC + OCI_RETURN_NULLS);
		   $num=oci_num_rows($comanda_comproba_alias);
			if($num != 0){
				 die("ALIAS PERSONATGE EXISTEN HEU D'ELEGIR UN ALTRE ALIAS PER PODER CONTINUAR");
			}
		 
		 
	 }
//option vehicle
echo "option vehicle <br>";
echo 'vehicle-->   '.$_POST['vehicle'].'<br/>';
echo 'vehicle_new--> '.$_POST['codi_nou'].'<br/>';

//option personatge
echo "option personatge <br>";
echo 'personatge-->  '.$_POST['personatge'].'<br/>';
echo 'perosnatge_new-->'.$_POST['alias_nou'].'<br/>';
//data operation
echo "data operation <br>";
echo 'dataoperacion--> '.$_POST['dataoperacion'].'<br/>';
//option
echo "option <br>";
echo 'personatge option-->  '.$_COOKIE['personatge1'].'<br/>';
echo 'vehicle option-->   '.$_COOKIE['vehicle1'].'<br/>';
//usuaris comprador/venedor
 echo 'venedor-->  '.$_COOKIE['usuari_venedor'].'<br/>';
 echo 'comprador-->  '.$_POST['comprador_usuari'].'<br/><br>';

 if(isset($_POST['vehicle'])){
	  $compra_sql_comprador = "select * from usuaris where alias='{$_POST['comprador_usuari']}' ORDER BY alias";
	    $comanda_comprador = oci_parse($connexio, $compra_sql_comprador);
		$ok=oci_execute($comanda_comprador);
		if($ok){
			//comprador
		$fila_comprador = oci_fetch_array($comanda_comprador, OCI_ASSOC + OCI_RETURN_NULLS);
					   $num_comprador=oci_num_rows($comanda_comprador);
                       // echo 'SALDO-- comprador>  '.$fila_comprador['SALDO'].'<br>';
						echo 'num filas ---->comprador   '.$num_comprador.'<br>';
						$compra_sql_venedor = "SELECT saldo FROM usuaris where alias='{$_COOKIE['usuari_venedor']}' ORDER BY alias";
						$comanda_venedor = oci_parse($connexio, $compra_sql_venedor);
						$ok1=oci_execute($comanda_venedor);
						if($ok1){
							   //venedor
							  $fila_venedor = oci_fetch_array($comanda_venedor, OCI_ASSOC + OCI_RETURN_NULLS);
					          $num_venedor=oci_num_rows($comanda_venedor);
                              //echo 'SALDO venedor-->  '.$fila_venedor['SALDO'].'<br>';
						      echo 'num_filass venedor--->  '.$num_venedor.'<br>';
						     
							  $sql_preu_vehicle = "SELECT * FROM vehicles where codi='{$_POST['vehicle']}' ORDER BY codi";
							  $comanda_preu_vehicle = oci_parse($connexio, $sql_preu_vehicle);
							  $ok2=oci_execute($comanda_preu_vehicle);
							  if($ok2){
								       //preu vehicle a vendre
								         $fila_vehicle = oci_fetch_array($comanda_preu_vehicle, OCI_ASSOC + OCI_RETURN_NULLS);
										 $num_vehicle=oci_num_rows($comanda_preu_vehicle);
                                        // echo 'PREU_VEHICLE-->  '.$fila_vehicle['PREU'].'<br>';
						                 echo 'num_filass vehicle--->  '.$num_vehicle.'<br>';
						                 
										    //ACTUALITZA TAULAS
										       if($fila_comprador['SALDO'] >= $fila_vehicle['PREU']){
												   echo "TE DINERS PER COMPRAR EL COTXE <br> <br>";
												   //nou saldo venedor
												   $nou_saldo_venedor=$fila_vehicle['PREU']+$fila_venedor['SALDO'];
												   //nou saldo comprador
												   $nou_saldo_comprador=$fila_comprador['SALDO']-$fila_vehicle['PREU'];
												   //sentencia sql actualitza saldo venedor
												   $sql_update_usuari_venedor="UPDATE usuaris set saldo='{$nou_saldo_venedor}' WHERE alias='{$_COOKIE['usuari_venedor']}'";
												   $comanda_update_venedor = oci_parse($connexio, $sql_update_usuari_venedor);
						                           $ok_update=oci_execute($comanda_update_venedor);
											              if($ok_update){
													         echo "HEU COBRAT CORRECTAMENT <br> <br>";
															      //DESABILTA COTXE
																  $zero=0;
																  $sql_desabilita_usuari_vehicle="UPDATE vehicles set habilitat='{$zero}' WHERE codi='{$_POST['vehicle']}'";
												                  $comanda_update_vehicle_habilitat = oci_parse($connexio, $sql_desabilita_usuari_vehicle);
						                                          $ok_update_habilitat=oci_execute($comanda_update_vehicle_habilitat);
																         if($ok_update_habilitat){
																			 echo "VEHICLE DESABILITAT PER L'USUARI VENEDOR <br> <br>";
																		 }else{
																			 $error = oci_error($comanda_update_vehicle_habilitat);
			                                                         setcookie('ErrorSentencia',$error['sqltext']);
			                                                         setcookie('ErrorCodi',$error[code]);
			                                                         setcookie('ErrorMissatge',$error['message']);
			                                                         setcookie('ErrorOffset',$error['offset']);
																	 header('Location:error_execucio.php');
																		 }
												           }else{
															         $error = oci_error($comanda_update_venedor);
			                                                         setcookie('ErrorSentencia',$error['sqltext']);
			                                                         setcookie('ErrorCodi',$error[code]);
			                                                         setcookie('ErrorMissatge',$error['message']);
			                                                         setcookie('ErrorOffset',$error['offset']);
																	 header('Location:error_execucio.php');
														   }
												   //sentencia sql actualitza saldo comprador
												   $sql_update_usuari_comprador="UPDATE usuaris SET saldo='{$nou_saldo_comprador}' WHERE alias='{$_POST['comprador_usuari']}'";
												           $comanda_update_comprador = oci_parse($connexio, $sql_update_usuari_comprador);
						                           $ok_update=oci_execute($comanda_update_comprador);
											              if($ok_update){
													         echo " DINERS ENVIAT CORECTAMENT EL VENEDOR <br><br>";
															      //TRASPASS VEHICLE NOU USUARI
																  $data=$_POST['dataoperacion'];
															
																      $codi=  $sql_nou_vehicle="INSERT INTO vehicles (codi,descripcio,color,consum,datacompra,preu,grupVehicle,combustible,propietari) 
																	   VALUES('{$_POST['codi_nou']}','{$fila_vehicle['DESCRIPCIO']}','{$fila_vehicle['COLOR']}','{$fila_vehicle['CONSUM']}',
																	   TO_DATE('{$data}','DD/MM/YYYY'),'{$fila_vehicle['PREU']}','{$fila_vehicle['GRUPVEHICLE']}','{$fila_vehicle['COMBUSTIBLE']}','{$_POST['comprador_usuari']}')";
												                  $comanda_insert_nou_vehicle = oci_parse($connexio, $sql_nou_vehicle);
						                                          $ok_insert=oci_execute($comanda_insert_nou_vehicle);
																  //-------------------------------------------------die bellow this linw--------------------------------------
																         //die($ok_insert);
																         if($ok_insert){
																			 echo "NOU PROPIETARI INCERTAT CORECTAMENT <br> <br>";
																		 }else{
																			 $error = oci_error($comanda_insert_nou_vehicle);
			                                                         setcookie('ErrorSentencia',$error['sqltext']);
			                                                         setcookie('ErrorCodi',$error[code]);
			                                                         setcookie('ErrorMissatge',$error['message']);
			                                                         setcookie('ErrorOffset',$error['offset']);
																	 header('Location:error_execucio.php');
																		 }
																      
												           }else{
															         $error = oci_error($comanda_update_venedor);
			                                                         setcookie('ErrorSentencia',$error['sqltext']);
			                                                         setcookie('ErrorCodi',$error[code]);
			                                                         setcookie('ErrorMissatge',$error['message']);
			                                                         setcookie('ErrorOffset',$error['offset']);
																	 header('Location:error_execucio.php');
														   }
											   }else{
												   echo " NO TE DINERS PER COMPRAR EL COTXE";
											   }
							  }else{
								      $error = oci_error($comanda_preu_vehicle);
			                          setcookie('ErrorSentencia',$error['sqltext']);
			                          setcookie('ErrorCodi',$error[code]);
			                          setcookie('ErrorMissatge',$error['message']);
			                          setcookie('ErrorOffset',$error['offset']);
									  header('Location:error_execucio.php');
							  }
						}else{
							
							$error = oci_error($comanda_venedor);
			                setcookie('ErrorSentencia',$error['sqltext']);
			                setcookie('ErrorCodi',$error[code]);
			                setcookie('ErrorMissatge',$error['message']);
			                setcookie('ErrorOffset',$error['offset']);
							header('Location:error_execucio.php');
						}
		}else{
			 $error = oci_error($comanda_comprador);
			setcookie('ErrorSentencia',$error['sqltext']);
			setcookie('ErrorCodi',$error[code]);
			setcookie('ErrorMissatge',$error['message']);
			setcookie('ErrorOffset',$error['offset']);
			header('Location:error_execucio.php');
		}
 }
else{
	  print "ELEGIERON UN PERSONAGE";
	  $compra_sql_comprador = "select * from usuaris where alias='{$_POST['comprador_usuari']}' ORDER BY alias";
	    $comanda_comprador = oci_parse($connexio, $compra_sql_comprador);
		$ok=oci_execute($comanda_comprador);
		if($ok){
			//comprador
		$fila_comprador = oci_fetch_array($comanda_comprador, OCI_ASSOC + OCI_RETURN_NULLS);
					   $num_comprador=oci_num_rows($comanda_comprador);
                        echo 'SALDO-- comprador>  '.$fila_comprador['SALDO'].'<br>';
						echo 'num filas ---->comprador   '.$num_comprador.'<br>';
						
						$compra_sql_venedor = "SELECT saldo FROM usuaris where alias='{$_COOKIE['usuari_venedor']}' ORDER BY alias";
						$comanda_venedor = oci_parse($connexio, $compra_sql_venedor);
						$ok1=oci_execute($comanda_venedor);
						if($ok1){
							   //venedor
							  $fila_venedor = oci_fetch_array($comanda_venedor, OCI_ASSOC + OCI_RETURN_NULLS);
					          $num_venedor=oci_num_rows($comanda_venedor);
                              echo 'SALDO venedor-->  '.$fila_venedor['SALDO'].'<br>';
						      echo 'num_filass venedor--->  '.$num_venedor.'<br>';
					
							  $sql_preu_vehicle = "SELECT * FROM personatges where alias='{$_POST['personatge']}' ORDER BY alias";
							  $comanda_preu_personatge = oci_parse($connexio, $sql_preu_vehicle);
							  $ok2=oci_execute($comanda_preu_personatge);
							  if($ok2){
								       //preu vehicle a vendre
								         $fila_personatge = oci_fetch_array($comanda_preu_personatge, OCI_ASSOC + OCI_RETURN_NULLS);
										 $num_vehicle=oci_num_rows($comanda_preu_personatge);
                                        // echo 'PREU_VEHICLE-->  '.$fila_vehicle['PREU'].'<br>';
						                 echo 'num_filass vehicle--->  '.$num_vehicle.'<br>';
						      
										    //ACTUALITZA TAULAS
										       if($fila_comprador['SALDO'] >= $fila_personatge['DESPESAMENSUAL']){
												   echo "TE DINERS PER COMPRAR EL COTXE <br> <br>";
												   //nou saldo venedor
												   $nou_saldo_venedor=$fila_personatge['DESPESAMENSUAL']+$fila_venedor['SALDO'];
												   //nou saldo comprador
												   $nou_saldo_comprador=$fila_comprador['SALDO']-$fila_personatge['DESPESAMENSUAL'];
												   //sentencia sql actualitza saldo venedor
												   $sql_update_usuari_venedor="UPDATE usuaris set saldo='{$nou_saldo_venedor}' WHERE alias='{$_COOKIE['usuari_venedor']}'";
												   $comanda_update_venedor = oci_parse($connexio, $sql_update_usuari_venedor);
						                           $ok_update=oci_execute($comanda_update_venedor);
											              if($ok_update){
													         echo "HEU COBRAT CORRECTAMENT <br> <br>";
															      //DESABILTA PERSONATGE
																  $zero=0;
																  $sql_desabilita_usuari_personatge="UPDATE personatges set habilitat='{$zero}' WHERE alias='{$_POST['personatge']}'";
												                  $comanda_update_personatge_habilitat = oci_parse($connexio, $sql_desabilita_usuari_personatge);
						                                          $ok_update_habilitat=oci_execute($comanda_update_personatge_habilitat);
																         if($ok_update_habilitat){
																			 echo "VEHICLE DESABILITAT PER L'USUARI VENEDOR <br> <br>";
																		 }else{
																			 $error = oci_error($comanda_update_personatge_habilitat);
			                                                         setcookie('ErrorSentencia',$error['sqltext']);
			                                                         setcookie('ErrorCodi',$error[code]);
			                                                         setcookie('ErrorMissatge',$error['message']);
			                                                         setcookie('ErrorOffset',$error['offset']);
																	 header('Location:error_execucio.php');
																		 }
												           }else{
															         $error = oci_error($comanda_update_venedor);
			                                                         setcookie('ErrorSentencia',$error['sqltext']);
			                                                         setcookie('ErrorCodi',$error[code]);
			                                                         setcookie('ErrorMissatge',$error['message']);
			                                                         setcookie('ErrorOffset',$error['offset']);
																	 header('Location:error_execucio.php');
														   }
												   //sentencia sql actualitza saldo comprador
												   $sql_update_usuari_comprador="UPDATE usuaris SET saldo='{$nou_saldo_comprador}' WHERE alias='{$_POST['comprador_usuari']}'";
												           $comanda_update_comprador = oci_parse($connexio, $sql_update_usuari_comprador);
						                           $ok_update=oci_execute($comanda_update_comprador);
											              if($ok_update){
													         echo " DINERS ENVIAT CORECTAMENT EL VENEDOR <br><br>";
															      //TRASPASS VEHICLE NOU USUARI
																  $data=$_POST['dataoperacion'];
															
																      $codi=  $sql_nou_personatges="INSERT INTO personatges (alias,despesaMensual,dataCreacio,usuari,tipusPersonatge) 
																	   VALUES('{$_POST['alias_nou']}','{$fila_personatge['DESPESAMENSUAL']}',TO_DATE('{$data}','DD/MM/YYYY'),'{$_POST['comprador_usuari']}','{$fila_personatge['TIPUSPERSONATGE']}')";
												                  $comanda_insert_nou_personatge = oci_parse($connexio, $sql_nou_personatges);
						                                          $ok_insert=oci_execute($comanda_insert_nou_personatge);
												
																        
																         if($ok_insert){
																			 echo "NOU PROPIETARI PERSONATGE INCERTAT CORRECTAMENT <br> <br>";
																		 }else{
																			 $error = oci_error($comanda_insert_nou_personatge);
			                                                         setcookie('ErrorSentencia',$error['sqltext']);
			                                                         setcookie('ErrorCodi',$error[code]);
			                                                         setcookie('ErrorMissatge',$error['message']);
			                                                         setcookie('ErrorOffset',$error['offset']);
																	 header('Location:error_execucio.php');
																		 }
																      
												           }else{
															         $error = oci_error($comanda_update_venedor);
			                                                         setcookie('ErrorSentencia',$error['sqltext']);
			                                                         setcookie('ErrorCodi',$error[code]);
			                                                         setcookie('ErrorMissatge',$error['message']);
			                                                         setcookie('ErrorOffset',$error['offset']);
																	 header('Location:error_execucio.php');
														   }
											   }else{
												   echo " NO TE DINERS PER COMPRAR EL COTXE";
											   }
							  }else{
								      $error = oci_error($comanda_preu_personatge);
			                          setcookie('ErrorSentencia',$error['sqltext']);
			                          setcookie('ErrorCodi',$error[code]);
			                          setcookie('ErrorMissatge',$error['message']);
			                          setcookie('ErrorOffset',$error['offset']);
									  header('Location:error_execucio.php');
							  }
						}else{
							
							$error = oci_error($comanda_venedor);
			                setcookie('ErrorSentencia',$error['sqltext']);
			                setcookie('ErrorCodi',$error[code]);
			                setcookie('ErrorMissatge',$error['message']);
			                setcookie('ErrorOffset',$error['offset']);
							header('Location:error_execucio.php');
						}
		}else{
			 $error = oci_error($comanda_comprador);
			setcookie('ErrorSentencia',$error['sqltext']);
			setcookie('ErrorCodi',$error[code]);
			setcookie('ErrorMissatge',$error['message']);
			setcookie('ErrorOffset',$error['offset']);
			header('Location:error_execucio.php');
		}
}

?>