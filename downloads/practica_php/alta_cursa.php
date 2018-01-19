#!/usr/bin/php-cgi
<!DOCTYPE html>
<html>
<head>
<style>
.error {color: #FF0000;}
body{
	margin
	 display:flex;
          flex-wrap:wrap;
          justify-content:center;
}
</style>
</head>
<body>  
<h1>Alta cursa</h1>
<p><span class="error">*camps obligatoris.</span></p>
<form method="post" action="./incert_cursa.php">  
  Codi: <input type="text" name="codi" placeholder="codi" required value="ibra">
  <br><br>
   Nom: <input type="text" name="nom" required placeholder="nom" value="diallo">
  <br><br>
    Premi: <input type="text" name="premi" placeholder="Premi" required value="2000">
  <br><br>
    Inscripcio: <input type="number" name="inscripcio" placeholder="Inscripcio 20 euros " required value="20">
  <br><br>
    Inici Previst: <input name="iniciprevist" placeholder="	12/2/2017" required  >
  <br><br>
  <input type="submit" name="submit" value="Submit">  
</form>
</body>
</html>