<?php

require "vendor/autoload.php";
use GuzzleHttp\Client;
use Sunra\PhpSimple\HtmlDomParser;
	


	$client = new Client([
	    
		'headers' =>[
			'User-Agent'=> 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.86 Safari/537.36'
		]
	]);

	$URL = 'https://www.guiamais.com.br/encontre?searchbox=true&what=&where=sao+jose+dos+campos';
	$html = $client->request("GET", $URL)->getBody();
	$dom = HtmlDomParser::str_get_html($html);

	foreach ($dom->find('meta[itemprop=url]') as $key=>$link)  {

		$urlEmpresa = $link->content;
		$html = $client ->request("GET", $urlEmpresa) -> getBody();
		$domEmpresa = HtmlDomParser::str_get_html($html);

		$basicsInfo = $domEmpresa->find ('div.basicsInfo', 0);
		$extendedInfo = $domEmpresa->find ('div.extendedInfo', 0);

		$titulo = html_entity_decode($basicsInfo->find ("h1",0)->plaintext);
		$categoria =html_entity_decode (trim($basicsInfo->find ("p.category",0)->plaintext));

		$endereco = preg_replace('/\s+/', ' ',html_entity_decode(trim($extendedInfo->find ('.advAddress',0)->plaintext)));


		$telefones=[];
		foreach ($extendedInfo -> find('li.detail') as $li) {
			$telefones[]=trim($li->plaintext);
		}

		echo $titulo.PHP_EOL.$categoria.PHP_EOL.$endereco;

		

		echo '<pre>';
		print_r($telefones);
		echo '<pre>';
		

		echo PHP_EOL.PHP_EOL.PHP_EOL;
	}

?>