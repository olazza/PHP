<?php
	/*
	 * Gera slug
	 * ---
	 * urlSlug()
	 * @param string
	 * @param string
	 * @param boolean
	 * @return string
	*/
	function urlSlug($str = '', $char = '-', $underline = false)
	{
		$slug = removeAccents($str, 'ISO-8859-1');
		$slug = str_replace(" ", $char, $slug);
		$slug = ($underline) ? str_replace("_", $char, $slug) : $slug;
		$slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '', $slug));
		//$slug = strtolower(str_replace(array('/--+/', '/---+/'), '-', $slug));
		$slug = preg_replace('/-{2,}/', '-', $slug);

		return $slug;
	}


	/*
	 * Adiciona um parâmetro em uma querystring
	 * ---
	 * addParamInQuery()
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	*/
	function addParamInQuery($url, $key, $value) 
	{
		$url = preg_replace('/(.*)(\?|&)'.$key.'=[^&]+?(&)(.*)/i', '$1$2$4', $url.'&');
		$url = substr($url, 0, -1);
		$pos = strpos($url, '?');
		
		if ($pos === false) 
		{
			return ($url.'?'.$key.'='.$value);
		} 
		else 
		{
			return ($url.'&'.$key.'='.$value);
		}
	}
	
	
	/*
	 * Remove um parâmetro em uma querystring
	 * ---
	 * removeParamInQuery()
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	*/	
	function removeParamInQuery($url, $key) 
	{
		$url = preg_replace('/(.*)(\?|&)'.$key.'=[^&]+?(&)(.*)/i', '$1$2$4', $url.'&');
		$url = substr($url, 0, -1);
		
		return ($url);
	}

	/*
	 * Obtem a URL atual completa
	 * ---
	 * getFullUrl()
	 * @return string
	*/	
	function getFullUrl() 
	{
		$protocol = (isset($_SERVER['HTTPS'])) ? "https://" : "http://";
		$url      = $protocol . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		
		return $url;
	}

?>