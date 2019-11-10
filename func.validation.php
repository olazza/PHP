<?php
    /*
     * Valida CPF (número único de identificação de pessoa física)
     * ---
     * checkCPF()
     * @param string
     * @return boolean
    */
    function checkCPF($cpf)
    {
        $cpf = preg_replace('[^0-9]', '', $cpf);

        if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '99999999999')
    	{
            return false;
        }

        for ($t = 8; $t < 10;)
    	{
            for ($d = 0, $p = 2, $c = $t; $c >= 0; $c--, $p++)
    		{
                $d += $cpf[$c] * $p;
            }

            $d = ((10 * $d) % 11) % 10;

            if ($cpf[++$t] != $d)
    		{
                return false;
            }
        }

        return true;
    }


    /*
     * Valida PIS/PASEP (documento do Programa de Integração Social)
     * ---
     * checkPIS()
     * @param string
     * @return boolean
    */
    function checkPIS($pis)
    {
        $pis = preg_replace('[^0-9]', '', $pis);

        if (strlen($pis) != 11 || intval($pis) == 0)
    	{
            return false;
        }

        for ($d = 0, $p = 2, $c = 9; $c >= 0; $c--, ($p < 9) ? $p++ : $p = 2)
    	{
            $d += $pis[$c] * $p;
        }

        return ($pis[10] == (((10 * $d) % 11) % 10));
    }


    /*
     * Valida título eleitoral
     * ---
     * checkTE()
     * @param string
     * @return boolean
    */
    function checkTE($te)
    {
        $te = sprintf('%012s', ereg_replace('[^0-9]', '', $te));
        $uf = intval(substr($te, 8, 2));

        if ($uf < 1 || $uf > 28)
    	{
            return false;
        }

        foreach (array(7, 8 => 10) as $s => $t)
    	{
            for ($d = 0, $p = 2, $c = $t; $c >= $s; $c--, $p++)
    		{
                $d += $te[$c] * $p;
            }

            $d %= 11;
            $d  = ($d < 2) ? (($uf < 3) ? 1 - $d : 0) : 11 - $d;

            if ($te[($s) ? 11 : 10] != $d)
    		{
                return false;
            }
        }

        return true;
    }


    /*
     * Valida CNPJ
     * ---
     * checkCNPJ()
     * @param string
     * @return boolean
    */
    function checkCNPJ($cnpj)
    {
        $cnpj = preg_replace('[^0-9]', '', $cnpj);

        if (strlen($cnpj) != 14)
    	{
            return false;
        }

        for ($t = 11; $t < 13;)
    	{
            for ($d = 0, $p = 2, $c = $t; $c >= 0; $c--, ($p < 9) ? $p++ : $p = 2)
    		{
                $d += $cnpj[$c] * $p;
            }

            $d = ((10 * $d) % 11) % 10;

            if ($cnpj[++$t] != $d)
    		{
                return false;
            }
        }

        return true;
    }


    /*
     * Valida email
     * ---
     * checkEmail()
     * @param string
     * @return boolean
    */
    function checkEmail($email)
    {
    	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    		return true;
    	} else {
    		return false;
    	}
    }
?>