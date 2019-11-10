<?php
	/*
	 * Deixa a string com as letras maiúsculas
	 * ---
	 * toUpper()
	 * @param string
	 * @return string
	 */
	function toUpper($str)
	{
	    $text = strtr(strtoupper($str), "àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
	    return $text;
	}


	/*
	 * Deixa a string com as letras minúsculas
	 * ---
	 * toLower()
	 * @param string
	 * @return string
	 */
	function toLower($str)
	{
	   $text = strtr(strtolower($str), "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß", "àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ");
	   return $text;
	}


	/*
	 * Converte a string para tags html
	 * ---
	 * toHTML()
	 * @param string
	 * @param string
	 * @return string
	 */
	function toHTML($str, $tag = "")
	{
		$text = (!empty($tag)) ? htmlspecialchars(strip_tags($str, $tag), ENT_QUOTES) : htmlspecialchars($str, ENT_QUOTES); 
		return $text;
	}


	/*
	 * Remove acentos de um string
	 * ---
	 * removeAccents()
	 * @param string
	 * @param string
	 * @return string
	 */
	function removeAccents($str, $charset = 'UTF-8')
	{
		$accents = array(
			'A' => '/&Agrave;|&Aacute;|&Acirc;|&Atilde;|&Auml;|&Aring;/',
			'a' => '/&agrave;|&aacute;|&acirc;|&atilde;|&auml;|&aring;/',
			'C' => '/&Ccedil;/',
			'c' => '/&ccedil;/',
			'E' => '/&Egrave;|&Eacute;|&Ecirc;|&Euml;/',
			'e' => '/&egrave;|&eacute;|&ecirc;|&euml;/',
			'I' => '/&Igrave;|&Iacute;|&Icirc;|&Iuml;/',
			'i' => '/&igrave;|&iacute;|&icirc;|&iuml;/',
			'N' => '/&Ntilde;/',
			'n' => '/&ntilde;/',
			'O' => '/&Ograve;|&Oacute;|&Ocirc;|&Otilde;|&Ouml;/',
			'o' => '/&ograve;|&oacute;|&ocirc;|&otilde;|&ouml;/',
			'U' => '/&Ugrave;|&Uacute;|&Ucirc;|&Uuml;/',
			'u' => '/&ugrave;|&uacute;|&ucirc;|&uuml;/',
			'Y' => '/&Yacute;/',
			'y' => '/&yacute;|&yuml;/',
			'a.' => '/&ordf;/',
			'o.' => '/&ordm;/'
		);

		return preg_replace($accents, array_keys($accents), htmlentities($str, ENT_NOQUOTES, $charset));
	}


	/*
	 * Converte para maiúscula o primeiro caractere de cada palavra, respeitando a lista de exceções.
	 * ---
	 * capitalizeWords()
	 * @param string
	 * @param array
	 * @return string
	 */
	function capitalizeWords($str, $e = array('de', 'des', 'do', 'dos', 'da', 'das', 'em', 'com'))
	{
		return join(' ', array_map(create_function('$str', 'return (!in_array($str, '.var_export($e, true).')) ? ucfirst($str) : $str;'), explode(' ', mb_strtolower($str))));
	}


	/*
	 * Evita o uso de sintaxe sql nos forms
	 * ---
	 * antiInjection()
	 * @param string
	 * @return string
	 */
	function antiInjection($str)
	{
		$text = preg_replace("/(\"|'|from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/i","", $str);
		$text = strip_tags(trim($text));
		//$text = htmlentities($text);
		$text = (get_magic_quotes_gpc()) ? $text : addslashes($text);

		return $text;
	}


	/*
	 * Retorna uma string formatada
	 * ---
	 * inputMask()
	 * @param string
	 * @param string
	 * @param array
	 * @return string
	 */
	function inputMask($str, $mask, $replace = array('-', ' ', '.', '/', '[', ']', '(', ')'))
	{
		$numb = -1;
		$text = str_replace($replace, '', $str);

		for ($x = 0; $x < strlen($mask); $x++)
		{
			if ($mask[$x] == 'X')
			{
				$mask[$x] = $text[++$numb];
			}
		}

		return $mask;
	}


	/*
	 * Retorna um resumo do texto
	 * ---
	 * resumeText()
	 * @param string
	 * @param integer
	 * @return string
	 */
	function resumeText($str, $limit = 50)
	{
		if (strlen($str) > $limit) {
			$text = substr($str, 0, $limit).'...';
		} else {
			$text = $str;
		}

		return $text;
	}


	/*
	 * Trata a string antes de gravar no banco
	 * ---
	 * inputClean()
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	function inputClean($str, $case = NULL, $tag = NULL)
	{
		switch($case)
		{
			case "upper":
				$text = antiInjection(toUpper($str));
				break;
			case "lower":
				$text = antiInjection(toLower($str));
				break;
			case "capitalize":
				$text = antiInjection(capitalizeWords($str));
				break;
			case "html":
				$text = toHTML($str, $tag);
				break;
			case "none":
				$text = antiInjection($str);
				break;
			default:
				$text = antiInjection($str);
		}

		if (!is_array($text))
		{
			$charset = mb_detect_encoding($text, 'UTF-8, ISO-8859-1');
			$my_text = ($charset == 'UTF-8') ? utf8_decode($text) : $text;
		}

		return $my_text;
	}	
?>