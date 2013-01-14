<?php
class Navegacao
{
	public $uri,$host,$admin,$dir,$script;

	public function __construct()
	{
		$this->uri   = $_SERVER['REQUEST_URI']; 
		$this->host  = $_SERVER['SERVER_NAME'];
		$this->admin = $_SERVER['SERVER_ADMIN'];
		$this->dir   = dirname($_SERVER['SCRIPT_FILENAME']);
		$this->script = $_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'];

		$this->apagar();
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
					#wrapper{width:980px;margin:0 auto;}
					table{}
					th{background:#333;color:#fff;}
					th,td{padding:5px;}
					tr:nth-child(even){background:#dedede;};
				</style>
			</head>
			<body><div id="wrapper">';
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


};




$n = new Navegacao();
#$n->debug($_SERVER);
$n->listar($n->diretorio());



?>
