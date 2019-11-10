<?php
	/*
	 * Gera combo do ano
	 * ---
	 * selectLimit()
	 * @param string
	 * @param array
	 * @return string
	*/
	function selectLimit($current = '', $numbers = NULL)
	{
		if (is_null($numbers)) {
			$list = array(50, 150, 300, 500, 'Todos');
		} else {
			$list = $numbers;
		}

		foreach ($list as $option) 
		{
			$attr  = ($option == $current) ? ' selected="selected"' : '';
			$drop .= "<option value='".$option."'".$attr.">".$option."</option>\n";
		}

		return $drop;
	}


	/*
	 * Marca o link ativo
	 * ---
	 * checkLink()
	 * @param string
	 * @return string
	*/
	function checkLink($local, $pasta = '', $pastaPai = '')
	{
		if(!empty($pastaPai)) {
			$css = ($pastaPai == $pasta) ? 'active' : '';
		} 
		else {
			$css = ($local == $pasta) ? 'active' : '';
		}
		
		return $css;
	}


	/*
	 * Marca o sublink ativo
	 * ---
	 * checkLink()
	 * @param string
	 * @return string
	*/
	function checkSublink($local, $pasta)
	{
		$css = ($local == $pasta) ? 'active' : '';
	
		return $css;
	}


	/*
	 * Define o status do registro
	 * ---
	 * checkStatus()
	 * @param string
	 * @return string
	*/
	function checkStatus($status)
	{
		$a = array('A' => 'Ativo', 'I' => 'Inativo', 'B' => 'Bloqueado', 'S' => 'Suspenso', 'C' => 'Cancelado', 'L' => 'Liberado');
		//return "<span class='status ".strtolower($a[$status])."'>".$a[$status]."</span>";
		return $a[$status];
	}

	
	/*
	 * Grava em uma sessão a querystring de retorno
	 * ---
	 * urlReturn()
	 */
	function urlReturn()
	{
		if (isset($_GET) && count($_GET) > 0)
		{
			$_SESSION[SPREFIX.'_MREF'] = rawurlencode($_SERVER['QUERY_STRING']);
		}
		else
		{
			$_SESSION[SPREFIX.'_MREF'] = "";
			unset($_SESSION[SPREFIX.'_MREF']);
		}
	}


	/*
	 * Monta aviso javascript
	 * ---
	 * viewMessage()
	 * @param string
	 * @param string
	 * @param boolean
	 * @param string
	 * @return string
	 */
	function viewMessage($message = '', $page = 'index.php', $search = true, $query = '')
	{
		$querystring = '';
		$qrytoreturn = isset($_SESSION[SPREFIX.'_MREF']) ? '?'.$_SESSION[SPREFIX.'_MREF'] : '';

		if (!$search)
		{
			if (!empty($query)) {
				$querystring = '?'.$query;
			}
		}
		else
		{
			if (!empty($query) && !empty($qrytoreturn)) {
				$querystring = rawurldecode($qrytoreturn).'&'.$query;
			} elseif (!empty($query) && empty($qrytoreturn)) {
				$querystring = '?'.$query;
			} else {
				$querystring = rawurldecode($qrytoreturn);
			}
		}

		$s  = "";
		$s .= "<script type=\"text/javascript\">\n";
		$s .= (!empty($message)) ? "alert('".$message."');\n" : "";
		$s .= "location.href = '".$page.$querystring."';\n";
		$s .= "</script>\n";

		return $s;
	}

	/*
	 *	Variáveis de entrada
	 *	---
	 *	inputVariables()
	 *	@param: string
	 *	@return string
	 */
	function inputVariables($limit = MAXREGS) {
		$resp['pagina'] = (!isset($_GET["pagina"]) || empty($_GET["pagina"])) ? 1 : $_GET["pagina"];
		$resp['limite'] = (!isset($_GET["limite"]) || empty($_GET["limite"])) ? $limit : ($_GET["limite"] == "todos" ? NULL : $_GET["limite"]);	

		return $resp;
	}
?>