<?php
	/*
	 * Formata o valor
	 * ---
	 * moneyFormat()
	 * @param float or decimal
	 * @return float
	*/
	function moneyFormat($value)
	{
		$value = str_replace('.', '', $value);
		$value = str_replace(',', '.', $value);

		return $value;
	}

	/*
	 * Formata o valor para BR
	 * ---
	 * moneyFormatBR()
	 * @param float or decimal
	 * @return float
	*/
	function moneyFormatBR($value)
	{
		$preco = number_format($value, 2, ",", ".");
		return $preco;
	}


	/*
	 * Divide um valor em parcelas mantendo a soma das parcelas igual ao valor inicial
	 * ---
	 * @param float
	 * @param integer
	 * @return array
	*/
	function calculateParcels($value, $parcels)
	{
	    if (is_null($value) || !is_float($value) || $value <= 0)
	    {
	    	return "O valor informado é inválido.";
	    }
	    else
	    {
		    $listpc = array();
		    $parcel = round($value/$parcels, 2);
		    
		    $last = $parcel;
		    $temp = ($parcel*$parcels);

		    if ($temp != $value)
			{
		        $last += ($value - $temp);
		    }

		    for ($i = 0; $i < $parcels-1; $i++)
			{
		       array_push($listpc, $parcel);
		    }

		    $listpc[$parcels-1] = $last;

		    return $listpc;
		}
	}


	/*
	 * Calcula juros juros simples de um valor
	 * ---
	 * simpleRate()
	 * @param float
	 * @param float
	 * @param integer
	 * @return float
	 */
	function simpleRate($value, $rate, $parcels)
	{
		$rate 		 = $rate / 100;
		$rate_parcel = round($value * (1 + $rate * $parcels));
		$rate_value  = round($rate_parcel / $parcels, 2);

		return $rate_value;
	}

	/*
	 * Calcula juros composto de um valor
	 * ---
	 * compoundRate()
	 * @param float
	 * @param float
	 * @param integer
	 * @return float
	 */
	function compoundRate($value, $rate, $parcels)
	{
		$rate 		 = $rate / 100;
		$rate_parcel = round($value * pow((1 + $rate), $parcels), 2);
		$rate_value  = round($rate_parcel / $parcels, 2);

		return $rate_value;
	}
?>