<?php 

	/*
	 *	Setar parcelas (com juros)
	 */
	function setSlicesAdd($valor, $parcelas = 12, $parcelasSemJuros = 3, $taxaJuros = 2.99)
	{
		$result = array();

		for($i=1; $i<=$parcelas; $i++)
		{
			if($i <= $parcelasSemJuros)
			{
				$semJuros = ' SEM JUROS';
				$juros    = 1;
				$parcela  = ($valor/$i);
			}
			else
			{
				$semJuros   = '';
				$juros      = $taxaJuros/100;				
				$valParcela = pow((1 + $juros), $i);			
				$valParcela = (1 / $valParcela);
				$valParcela = (1 - $valParcela);	
				$valParcela = ($juros / $valParcela);
				$parcela    = ($valor * $valParcela);
			}

        	$result[$i]['parcela'] = $i."x de ";
			$result[$i]['valor']   = $parcela;
			$result[$i]['juros']   = $semJuros;
		}

		return $result;
	}

?>