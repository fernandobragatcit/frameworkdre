<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="BR-PT" xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Language" content="pt_BR" />
		<title>{$SUBJECT}</title>
		<style type="text/css">
           {literal}
		   <!--
            *{
                font-family: Arial;
                color: #333;
                margin: 0px;
                padding: 0px;
				font-size: 13px;
            }
			#containerPrincipal{
				width: 680px;
				height:220px;
				left: 50%;
				top: 50%;
				margin-top: -110px;
				position: absolute;
				border: 1px solid #CCC;
			}
			h1{
				font-size: 14px;
				color: #333;
				text-align: center;
				margin: 15px 0px;
			}
			p{
				margin: 10px;
			}

			p a{
				font-size: 12px;
				color:#00F;
			}
            -->
			{/literal}
        </style>
    </head>
	<body>
    	<div id="containerPrincipal">
        	<h1>{$NOME_PORTAL}</h1>
        	<p>
            	{$NOME_USUARIO},<br /><br/>
            	Sua senha no {$NOME_PORTAL} foi alterada. <br />
                Sua nova senha é: {$SENHA_USUARIO}<br /> <br />

                Obrigado,<br />
                Equipe do {$NOME_PORTAL}.
        	</p>
        </div>
	</body>
</html>