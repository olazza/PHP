<?php
	/*
	 * Retorna o nome do idioma conforme o identificados
	 * ---
	 * siteLanguage()
	 * @param integer
	 * @param integer
	 * @param boolean
	 * @return string
	*/
	function siteLanguage($language = 'pt-BR', $translate = 1)
	{
		$lang = array('pt-BR' => array(1 => 'Português', 2 => 'Portuguese', 3 => 'Portugués'),
					  'en'    => array(1 => 'Inglês',    2 => 'English', 	3 => 'Inglés'),
					  'es'    => array(1 => 'Espanhol',  2 => 'Spanish',    3 => 'Español'));

		return $lang[$language][$translate];
	}


	/*
	 * Retorna primeiro e/ou último nome
	 * ---
	 * firstName()
	 * @param string
	 * @return string
	 */
	function firstName($str)
	{
		$name = explode(' ', $str);
		return $name[0];
	}


	/*
	 * Gera uma senha aleatória
	 * ---
	 * randomPassword()
	 * @param integer
	 * @return string
	 */
	function randomPassword($chars = 7, $especial = true, $data = true)
	{
		$pass1 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$pass2 = '1234567890';
		$pass3 = '!@#$%*-';
		$pass4 = date('YmdGis');

		$my_pass = '';
		
		$mykey = $pass1.$pass2.(($especial) ? $pass3 : '').(($data) ? $pass4 : '');
		$count = strlen($mykey);

		for ($n = 1; $n <= $chars; $n++)
		{
			$random  = mt_rand(1, $count);
			$my_pass .= $mykey[$random-1];
		}

		return $my_pass;
	}


	/*
	 * Retorna o número com zeros a frente
	 * ---
	 * lineNumber()
	 * @param integer ou string
	 * @param integer
	 * @param string
	 * @return string
	 */
	function lineNumber($str, $count = 3, $pad = '0')
	{
		return str_pad($str, $count, $pad, STR_PAD_LEFT);
	}

	/*
	 * Retorna o valor do ENUM
	 * ---
	 * enum()
	 * @param string
	 * @return string
	 */
	function enum($str)
	{
		switch ($str) 
		{
			case 'S': $res = 'Sim'; break;
			case 'N': $res = 'Não'; break;
			case 'A': $res = 'Ativo'; break;
			case 'I': $res = 'Inativo'; break;
			case 'C': $res = 'Cartão de crédito'; break;
			case 'D': $res = 'Débito online'; break;
			case 'B': $res = 'Boleto'; break;
			case 'T': $res = 'Transferência'; break;
		}

		return $res;
	}
?>