<?php 

	/*
	 *	Calcula o peso total do pedido para o cálculo de frete
	 */
	function pesoCarrinho()
	{		
		$resp       = array();
		$pesoTotal  = 0;
		$pesoGratis = 0;
		
		foreach($_SESSION[SPREFIX.'carrinho'] as $item) 
		{
			$pesoTotal  += $item['peso'] * $item['quantidade'];
			$pesoGratis += ($item['freteGratis'] == 'S') ? $item['peso'] * $item['quantidade'] : 0;
		}

		//Resp
		$resp['total']     = $pesoTotal;
		$resp['gratis']    = $pesoGratis;
		$resp['subtraido'] = ($pesoGratis > $pesoTotal) ? ($pesoGratis - $pesoTotal) : ($pesoTotal - $pesoGratis);

		return $resp;
	}

?>