<?php
$emmagatzemarSessions = exec("pwd") . "/tmp";
ini_set('session.save_path', $emmagatzemarSessions);
session_start();
?>
<p class="capcalera">usuari actiu: <b> <?php echo $_SESSION['usuari']; ?> </b></p>
