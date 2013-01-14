<?php
class Navegacao
{
	public $uri,$host,$admin,$dir,$script;
	private $senha = '123456';
	private $mensagem = array();

	public function __construct()
	{
		session_start();
	
		$this->uri   = $_SERVER['REQUEST_URI']; 
		$this->host  = $_SERVER['SERVER_NAME'];
		$this->admin = $_SERVER['SERVER_ADMIN'];
		$this->dir   = dirname($_SERVER['SCRIPT_FILENAME']);
		$this->script = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'];

		$this->apagar();
	}


	public function autenticacao()
	{
		if(isset($_GET['sair']))
		{
			session_destroy();
			return $this->redirect($this->script);
			die();
		}

		if(isset($_POST['senha']))
		{
			if(!empty($_POST['senha']))
			{
				if($_POST['senha'] === $this->senha)
				{
					$_SESSION['logado'] = true;
				}else{
					$this->mensagem['erro'] = 'Falha na autenticação';
				}
			}
		}
		if(!isset($_SESSION['logado']))
		{
			$this->html_topo();
			$this->form_login();
			$this->html_footer();
		}else{
			$this->listar($this->diretorio());
		}	
	}

	
	public function debug($variavel)
	{
		echo "<pre>";
		print_r($variavel);
		echo "</pre><hr />";
	}

	public function redirect($url)
	{
		header('location: '.$url);
	}

	public function diretorio()
	{
		return glob($this->dir.'/*');
	}

	public function listar($array)
	{
		$this->html_topo();
		if(count($array) > 1)
		{	

			echo "<table><thead><tr><th>Arquivos / Diretório</th><th>Ação</th></tr></thead><tbody>";
			foreach($array as $a)
			{
				if(is_file($a))
				{
					if(!preg_match('/navtools.php/', $a)){
					printf('<tr>
								<td>%s</td>
								<td><a href="%s">Apagar</a></td>
							</tr>',basename($a),basename($this->script).'?file='.$a.'&amp;acao=del');
					}
				}

				if(is_dir($a))
				{
				printf('<tr>
							<td>%s</td>
							<td><a href="%s">Apagar</a></td>
						</tr>',basename($a),basename($this->script).'?dir='.$a.'&amp;acao=del');
				}

			}
			echo "</tbody></table>";
		}else{
			echo "<h2>O diretório está limpo!</h2>";
		}
		$this->html_footer();
	}

	private function html_topo()
	{
	  echo '<!DOCTYPE HTML>
			<html lang="pt-BR">
			<head>
				<meta charset="UTF-8">
				<title>Sistemas de Manipulação de arquivos</title>
				<style>
					#wrapper{width:980px;margin:0 auto;font-family:helvetica,arial,sans-serif;font-size:1.2em;}
					table{}
					th{background:#333;color:#fff;}
					th,td{padding:5px;}
					tr:nth-child(even){background:#dedede;}
					#login{width:250px;background:#fefefe;margin:200px auto; border:none;}
					#login input{display:block;padding:5px;width:100%;}
					#login button{padding:5px 10px;cursor:pointer;}

				</style>
			</head>
			<body>
			<div id="wrapper">';
			if(isset($_SESSION['logado']))
			{
			echo '<nav>
				    <ul>
					<li><a href="'.$this->script.'">Home</a></li>
					<li><a href="'.$this->script.'?sair=true">Sair</a></li>
					</ul>
				 </nav>
			';
				
			}	
	}

	private function html_footer()
	{
		echo '</div></body>
			  </html>';
	}

	private function apagar()
	{
		if(isset($_GET['acao']) and $_GET['acao'] == 'del' and isset($_GET['dir']))
		{
			if(is_dir($_GET['dir']))
			{
				rmdir($_GET['dir']);
				echo "Diretório Apagado com sucesso!";
			}
		}

		if(isset($_GET['acao']) and $_GET['acao'] == 'del' and isset($_GET['file']))
		{
			if(is_file($_GET['file']))
			{
				unlink($_GET['file']);
				echo "Arquivo apagado com sucesso!";
			}
		}

	}


	private function form_login()
	{
		$form = sprintf("<form action='%s' method='post'>",$this->script);
		$form .= "<fieldset id='login'>";
		$form .= "<label for='senha'>Senha de acesso</label>";
		$form .= "<input required type='password' id='senha' name='senha' />";
		$form .= "<button type='submit'>Acessar</button>";

		if(!empty($this->mensagem))
		{ 
			foreach($this->mensagem as $key => $m)
			{
				$form .= sprintf("<p class='%s'>%s</p>",$key,$m);
			}
		};

		$form .= "</fieldset>";
		$form .= "</form>";
		echo $form;
	}


};




$n = new Navegacao();
$n->autenticacao();


?>
