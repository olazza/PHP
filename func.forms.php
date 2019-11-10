<?php
	/*
	 * Gera combo do mês
	 * ---
	 * selectMonth()
	 * @param integer
	 * @return string
	*/
	function selectMonth($current = '')
	{
		for ($m = 1; $m <= 12; $m++)
		{
			$attr  = ($m == $current) ? ' selected="selected"' : '';
			$drop .= "<option value='".$m."'".$attr.">".monthName($m)."</option>\n";
		}

		return $drop;
	}


	/*
	 * Gera combo do ano
	 * ---
	 * selectYear()
	 * @param integer
	 * @param integer
	 * @param integer
	 * @return string
	*/
	function selectYear($first, $last, $current = '')
	{
		for ($a = $first; $a <= $last; $a++)
		{
			$attr  = ($a == $current) ? ' selected="selected"' : '';
			$drop .= "<option value='".$a."'".$attr.">".$a."</option>\n";
		}

		return $drop;
	}
?>