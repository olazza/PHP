<?php
	/*
	 * Converte data para o formato setado
	 * ---
	 * dateFormat()
	 * @param string
	 * @param string
	 * @return string
	 */
	function dateFormat($date, $format = 'd/m/Y H:i:s')
	{
		$time = '';

		if (strlen($date) > 10) 
		{
			$time = substr($date, 11, 10);
			$date = substr($date, 0, 10);
		}

		$bar   = strstr($date, '/');
		$point = strstr($date, '.');

		if ($bar) {
			$date = implode("-", array_reverse(explode("/", $date)));
		} elseif ($point) {
			$date = implode("-", array_reverse(explode(".", $date)));
		}

		$datetime = new DateTime($date.' '.$time);
		return $datetime->format($format);
	}


	/*
	 * Retorna uma nova data acrescentando dias, semanas, meses ou anos
	 * ---
	 * dateAdd()
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	function dateAdd($date, $time, $format = 'Y-m-d')
	{
		$datetime = new DateTime($date);
		$datetime->modify($time);

		return $datetime->format($format);
	}


	/*
	 * Retorna o número de dias entre a data inicial e a data final
	 * ---
	 * dateDiff()
	 * @param string
	 * @param string
	 * @return integer
	 */
	function dateDiff($date1, $date2)
	{
		$datetime1 = new DateTime($date1);
		$datetime2 = new DateTime($date2);
		$date_diff = $datetime1->diff($datetime2);

		return $date_diff->format('%a');
	}


	/*
	 * Retorna o nome do dia da semana, por extenso ou abreviado
	 * ---
	 * dayName()
	 * @param date/datetime
	 * @param integer
	 * @param boolean
	 * @return string
	 */
	function dayName($date, $language = 1, $abr = false)
	{
		$names = array(1 => array('Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado'),
					   2 => array('Sunday', 'Monday', 'Tuesday','Wednesday', 'Thursday', 'Friday', 'Saturday'),
					   3 => array('Domingo', 'Lunes', 'Martes','Miércoles', 'Jueves', 'Viernes', 'Sábado'));
		
		$numb = date('w', strtotime($date));
		$name  = (!$abr) ? $names[$language][$numb] : substr($names[$language][$numb], 0, 3);

		return $name;
	}


	/*
	 * Retorna o nome do mês
	 * ---
	 * monthName()
	 * @param integer
	 * @param integer
	 * @param boolean
	 * @return string
	 */
	function monthName($month, $language = 1, $abr = false)
	{
		$months = array(1 => array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'),
					    2 => array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
					    3 => array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'));

		$name = $months[$language][$month-1];
		$name = ($abr) ? substr($name, 0, 3) : $name;

		return $name;
	}


	/*
	 * Exibe mensagem de cumprimento ao usuário conforme o turno
	 * ---
	 * greetUser()
	 */
	function greetUser()
	{
		$hora = date("H");

		if ($hora >= 6 && $hora < 12) {
			return "Bom Dia";
		} elseif ($hora >= 12 && $hora < 18) {
			return "Boa Tarde";
		} else {
			return "Boa Noite";
		}
	}
?>