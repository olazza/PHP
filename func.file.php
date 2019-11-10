<?php
	/*
	 * Força o download de um determinado arquivo
	 * ---
	 * forceDownload()
	 * @param string
	 * @param string
	 * @return file or boolean
	 */
	function forceDownload($directory = './', $file)
	{
	    if (file_exists($directory.$file))
		{
			header("Content-Description: File Transfer"); 
			header("Content-Type: application/octet-stream"); 
			header("Content-Disposition: attachment; filename={$file}");
			readfile($directory.$file);
	    }
		else
		{
			return false;
		}
	}


	/*
	 * Cria um diretorio
	 * ---
	 * makeDirectory()
	 * @param string
	 * @param integer
	 * @return string or boolean
	 */
	function makeDirectory($directory, $cmod = 0777)
	{
		if (!file_exists($directory))
		{
			if (mkdir($directory, $cmod))
			{
				return $directory;
			}
			else
			{
				return false;
			}
		}
	}


	/*
	 * Altera a permissão de um diretório
	 * ---
	 * changeDirectoryPermission()
	 * @param string
	 * @param integer
	 * @return boolean
	 */
	function changeDirectoryPermission($directory, $chmod = 0777)
	{
		if (function_exists('chmod'))
		{
			chmod($directory, $chmod);
			return true;
		}
		else
		{
			return false;
		}
	}


	/*
	 * Abre um diretório e faz a leitura dos arquivos
	 * ---
	 * openDirectory()
	 * @param string
	 * @return array
	 */
	function openDirectory($directory)
	{
		if (is_dir($directory))
		{
			if ($handle = opendir($directory))
			{
				$files = array();

				while (false !== ($file = readdir($handle)))
				{
					if ($file == '.' || $file == '..')
					{
						continue;
					}

					array_push($files, $file);
				}

				closedir($handle);
				sort($files);

				return $files;
			}
		}
	}


	/*
	 * Verifica se o arquivo existe e deleta do servidor
	 * ---
	 * deleteFile()
	 * @param string
	 * @param string
	 * @return boolean
	 */
	function deleteFile($filename, $directory = './')
	{
		if (!empty($filename))
		{
			if (file_exists($directory.'/'.$filename))
			{
				unlink($directory.'/'.$filename);
			}
		}
	}


	/*
	 * Retorna a extensão do arquivo
	 * ---
	 * fileExtension()
	 * @param string
	 * @return string
	 */
	function fileExtension($filename)
	{
		return preg_replace('/^.*\./', '', $filename);
		//return end(explode(".", $filename));
	}


	/*
	 * Retorna a extensão do arquivo
	 * ---
	 * checkFile()
	 * @param string
	 * @param string
	 * @param string or array
	 * @param integer
	 * @param integer
	 * @param integer
	 * @return array
	 */
	function checkFile($input, $filename, $type = 'image', $limit = 2, $maxw = NULL, $maxh = NULL)
	{
		//Objeto
		$temp = $_FILES[$input]['tmp_name'];
		$name = $_FILES[$input]['name'];
		$mime = $_FILES[$input]['type'];
		$size = $_FILES[$input]['size'];
		$warn = $_FILES[$input]['error'];

		//Erros de upload
	    $err[1] = 'O arquivo é maior que o permitido pelo servidor.';
	    $err[2] = 'O arquivo é maior que o permitido pelo formulário.';
	    $err[3] = 'O upload do arquivo foi feito parcialmente.';
	    $err[4] = 'Não foi feito o upload do arquivo.';

	    //Extensões do arquivo
	    if ($type == 'image') 
	    {
	    	$exts = array('jpg', 'jpeg', 'png', 'gif');
	    	$chxy = true;
	    } 
	    elseif ($type == 'text') 
	    {
	    	$exts = array('doc', 'docx', 'txt', 'pdf', 'rtf');
	    	$chxy = false;
	    } 
	    else 
	    {
	    	$exts = $type;
	    	$chxy = false;
	    }

	    //Variáveis
		$fext = fileExtension($name);
		$fmax = $limit*1024*1024;

		//Verificações
		if ($warn > 0)
		{
			$error = 'Erro no upload do arquivo: '.$err[$erro];
		}
		elseif (!in_array($fext, $exts))
		{
			$error = "A extensão do arquivo {$name} é inválida.";
		}
		elseif ($size > $fmax)
		{
			$error = "O arquivo {$name} ultrapassou o limite de {$limit}MB.";
		}
		elseif ($chxy && !is_null($maxw) && !is_null($maxh))
		{
			$xy = imageDimension($temp);
			
			if ($xy['w'] > $maxw)
			{
				$error = "A largura da imagem ultrapassou o limite de {$maxw} pixels.";
			}
			elseif ($xy['h'] > $maxh)
			{
				$error = "A largura da imagem ultrapassou o limite de {$maxh} pixels.";
			}
		}

		return array('error' => $error, 'temp' => $temp, 'name' => $filename.'.'.$fext);
	}	


	/*
	 * Executa o upload do arquivo
	 * ---
	 * fileUpload()
	 * @param string
	 * @param string
	 * @param string
	 * @param string or array
	 * @param integer
	 * @return string
	 */
	function fileUpload($input, $filename, $directory = './', $type = 'text', $limit = 5, $maxw = NULL, $maxh = NULL)
	{
		$check  = checkFile($input, $filename, $type, $limit, $maxw, $maxh);
		$chmod  = changeDirectoryPermission($directory);

		$myfile = '';
		$notice = '';

		if (!empty($check['error'])) 
		{
			$notice = $check['error'];
		}
		else 
		{
			if ($chmod)
			{
				$myfile = $directory.'/'.$check['name'];
				$mytemp = $check['temp'];
				$upload = move_uploaded_file($mytemp, $myfile);

				if (!$upload)
				{
					$notice = 'Não foi feito o upload do arquivo.';
				}
			}
			else
			{
				$notice = 'Não foi possível alterar a permissão do diretório.';
			}
		}

		return array('filename' => $check['name'], 'notice' => $notice);
	}


	/*
	 * Retorna as dimensões da imagem na original
	 * ---
	 * imageDimension()
	 * @param string
	 * @return array
	 */
	function imageDimension($image)
	{
		list($width, $height) = getimagesize($image);
		return array('w' => $width, 'h' => $height);
	}

	/*
	 *	Faz o upload da imagem principal
	 */
	function uploadMainImg($foto, $slug, $limite = 250, $dir = '../../upload/produtos', $sizeThumb = 0)
	{
		$resp       = array();
		$sizeLimite = $limite*1024;
		
		//Limita o tamanho
		if($foto['size'] >= $sizeLimite)
		{
			echo viewMessage("A imagem não pode ter mais do que ".$limite."KB!", 'index.php', false);
			exit();
		}

		$nome  = $slug.date('Gis');
		$temp  = $foto['tmp_name'];
		$ext   = preg_replace('/^.*\./', '', $foto['name']);
		$img   = $nome.'.'.$ext;
		$thumb = "";

		$objImage = new SimpleImage();

		try {
			
			//Salva a thumb
			if($sizeThumb > 0) 
			{
				$thumb = $nome.'-thumb.'.$ext;
				$objImage->load($temp)->fit_to_width($sizeThumb)->save($dir.'/'.$thumb, 80);
			}

			//Salva a imagem
			$objImage->load($temp)->save($dir.'/'.$img);
		} 
		catch(Exception $e) {
			//Erro
			echo viewMessage($e->getMessage(), 'criar.php', false);
			exit();
		}

		$resp['img']   = $img;
		$resp['thumb'] = $thumb;

		return $resp;
	}

	/*
	 *	Faz o upload das imagens da galeria
	 */
	function uploadImgGallery($totImg, $slug, $idProduto, $objReg, $dir = '../../upload/produtos')
	{
		for ($i=1; $i<=$totImg; $i++) 
		{ 
			$imagem = 'foto'.$i;

			if ($_FILES[$imagem]['size'] > 0)
			{
				$nome = $slug.$i.date('Gis');
				$temp = $_FILES[$imagem]['tmp_name'];
				$ext  = preg_replace('/^.*\./', '', $_FILES[$imagem]['name']);
				$img  = $nome.'.'.$ext;

				$objImage = new SimpleImage();

				try {
					$objImage->load($temp)->save($dir.'/'.$img);
				} 					
				catch(Exception $e) {
					//Erro
					echo viewMessage($e->getMessage(), 'criar.php', false);
					exit();
				}

				//Grava os dados
				$dados = array('id_produto' => $idProduto, 'foto' => $img);
				$grava = $objReg->insert('produtos_fotos', $dados);
			}
		}
	}	

	/**
	 * Apaga tudo dentro de uma pasta
	 * 
	 * Remove todos os ficheiros, sub-diretorias e seus ficheiros
	 * de dentro do caminho fornecido.
	 * 
	 * @param string $dir Caminho completo para diretoria a esvaziar.
	 */
	function deleteAllFiles($dir) {
	    if (is_dir($dir)) 
	    {
	        $iterator = new \FilesystemIterator($dir);

	        if ($iterator->valid()) 
	        {
	            $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
	            $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

	            foreach ( $ri as $file ) {
	                $file->isDir() ?  rmdir($file) : unlink($file);
	            }
	        }
	    }
	}
?>