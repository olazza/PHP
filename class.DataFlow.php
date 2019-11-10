<?php
	/*
	 * @class DataFlow
	 * @author Viva Interativa - www.vivainterativa.com.br
	 * @date  17/04/2015
	 *
	 */
	class DataFlow extends mysqli
	{
		/*
		 * Builder
		 */
		public function __construct()
		{
			parent::__construct(DB_HOST, DB_USER, DB_PASS, DB_DATA);

			if ($this->connect_error) 
			{
            	$this->error("Erro ao conectar com banco de dados", "(".$this->connect_errno.") ".$this->connect_error);
				exit();
        	}
		}


		/*
		 * search()
		 * @param string
		 * @param string
		 * @param string
		 * @param string or array
		 * @param string
		 * @param integer
		 * @param integer
		 * @param string
		 * @return array
		 */
		public function search($table, $fields = '*', $join = NULL, $where = NULL, $order = NULL, $page = 1, $limit = NULL, $other = NULL)
		{
			$data = array();

			if ($page == NULL || empty($page) || $page == 0)
			{
				$page = 1;
			}

			$where = $this->_whereResult($where);

			$sql  = "SELECT {$fields} FROM {$table}";
			$sql .= (!is_null($join))  ? " {$join}" : "";
			$sql .= (!is_null($where)) ? " WHERE {$where}" : "";
			$sql .= (!is_null($other)) ? " {$other}" : "";
			$sql .= (!is_null($order)) ? " ORDER BY {$order}" : "";
			$sql .= (!is_null($limit)) ? " LIMIT ".(($page - 1) * $limit).", ".$limit : "";

			$query = $this->query($sql);

			if (!$query)
			{
				$this->error("Erro ao executar consulta sql: ".$sql, "(".$this->errno.") ".$this->error);
				exit();
			}

			$nrows = $query->num_rows;
			$total = $this->count($table, $fields, $join, $where, $order, $other);
			$pages = (!is_null($limit)) ? ceil($total/$limit) : 1;

			$data['query'] = $sql;
			$data['total'] = $total;
			$data['nrows'] = $nrows;
			$data['pages'] = $pages;
			$data['items'] = array();

			while ($item = $query->fetch_assoc()) 
			{
				array_push($data['items'], $item);
			}

			$query->free();
			return $data;
		}


		/*
		 * sql()
		 * @param string
		 * @return array
		 */
		public function sql($sql)
		{
			$data  = array();
			$query = $this->query($sql);

			if (!$query)
			{
				$this->error("Erro ao executar consulta sql: ".$sql, "(".$this->errno.") ".$this->error);
				exit();
			}

			$data['query'] = $sql;
			$data['nrows'] = $query->num_rows;
			$data['items'] = array();

			while ($item = $query->fetch_assoc()) 
			{
				array_push($data['items'], $item);
			}

			$query->free();
			return $data;
		}


		/*
		 * count()
		 * @param  string
		 * @param  string
		 * @param  string
		 * @param  string or array
		 * @param  string
		 * @param  string
		 * @return integer
		 */
		public function count($table, $fields = '*', $join = NULL, $where = NULL, $order = NULL, $other = NULL)
		{
			$where = $this->_whereResult($where);

			$sql  = "SELECT {$fields} FROM {$table}";
			$sql .= (!is_null($join)) ? " {$join}" : "";
			$sql .= (!is_null($where)) ? " WHERE {$where}" : "";
			$sql .= (!is_null($other)) ? " {$other}" : "";
			$sql .= (!is_null($order)) ? " ORDER BY {$order}" : "";

			$total = $this->query($sql)->num_rows;
			return $total;
		}


		/*
		 * insert()
		 * @param string
		 * @param array
		 * @return boolean
		 */
		public function insert($table, $params)
		{
			$param = $this->_insertFields($params);

			$sql = "INSERT INTO {$table} ({$param[name]}) VALUES ({$param[value]})";
			$res = $this->query($sql);

			if (!$res)
			{
				$this->error("Erro ao executar consulta sql: ".$sql, "(".$this->errno.") ".$this->error);
				exit();
			}

			return array("query" => $sql, "lastid" => $this->insert_id);
		}


		/*
		 * update()
		 * @param string
		 * @param array
		 * @param string
		 * @return boolean
		 */
		public function update($table, $params, $where = NULL)
		{
			$param = $this->_updateFields($params);
			$where = $this->_whereResult($where);

			$sql  = "UPDATE {$table} SET {$param}";
			$sql .= (!is_null($where)) ? " WHERE {$where}" : "";
			$res  = $this->query($sql);

			if (!$res)
			{
				$this->error("Erro ao executar consulta sql: ".$sql, "(".$this->errno.") ".$this->error);
				exit();
			}

			return array("query" => $sql);
		}


		/*
		 * delete()
		 * @param string
		 * @param string
		 * @return boolean
		 */
		public function delete($table, $where = NULL)
		{
			$where = $this->_whereResult($where);

			$sql  = "DELETE FROM {$table}";
			$sql .= (!is_null($where)) ? " WHERE {$where}" : "";
			$res  = $this->query($sql);

			if (!$res)
			{
				$this->error("Erro ao executar consulta sql: ".$sql, "(".$this->errno.") ".$this->error);
				exit();
			}

			return array("query" => $sql);
		}


		/*
		 * reset()
		 * @param string
		 * @return boolean
		 */
		public function reset($table)
		{
			$sql = "TRUNCATE TABLE {$table}";
			$res = $this->query($sql);

			if (!$res)
			{
				$this->error("Erro ao executar consulta sql: ".$sql, "(".$this->errno.") ".$this->error);
				exit();
			}

			return array("query" => $sql);
		}


		/*
		 * error()
		 * @param string
		 * @return string
		 */
		public function error($message = '', $warning = '')
		{
			if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && toLower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
			{
				echo "error";
				exit();
			} 
			else 
			{
				echo "<!DOCTYPE html>\n";
				echo "<html>\n";
				echo "	<head>\n";
				echo "		<title>Erro</title>\n";
				echo "	</head>\n";
				echo "	<body>\n";
				echo "		<div style=\"font-family: Arial, Helvetica, sans-serif; font-size:14px; color:black; background:white; margin:30px; padding:20px 30px; border:2px solid black; line-height:140%\">\n";
				echo "			<h2>Ocorreu um erro ao executar uma requisi&ccedil;&atilde;o com o banco de dados:</h2>\n";
				echo "			<strong>Origem:</strong> ".$_SERVER['HTTP_REFERER']."<br />\n";
				echo "			<strong>P&aacute;gina:</strong> ".$_SERVER['REQUEST_URI']."<br />\n";
				echo "			<strong>Arquivo:</strong> ".$_SERVER['SCRIPT_FILENAME']."<br />\n";
				echo "			<strong>Mensagem:</strong> ".$message."<br />\n";
				echo "			<strong>Erro MySQLi:</strong> ".$warning."<br />\n";
				echo "			<a href=\"mailto:atendimento@vivainterativa.com.br?subject=".PROJETO."\">Clique aqui para relatar este erro ao desenvolvedor</a>\n";
				echo "		</div>\n";
				echo "	</body>\n";
				echo "</html>";
				exit();
			}
		}


		/*
		 * _whereResult()
		 * @param  string or array
		 * @param  boolean
		 * @return string or array
		 */
		private function _whereResult($where = NULL, $array = false)
		{ 
			$list = array();

			if (is_array($where))
			{
				foreach ($where as $where)
				{
					if (!empty($where))
					{
						array_push($list, $where);
					}
				}
			}
			else if (is_string($where))
			{
				$list = explode(" AND ", $where);
			}
			else
			{
				$list = NULL;
			}

			$condition = $this->_whereIsNull($list);
			$condition = (count($condition) > 0) ? (!$array ? implode(" AND ", $condition) : $condition) : NULL;

			return $condition;
		}


		/*
		 * _whereIsNull()
		 * @param  array
		 * @return array
		 */
		private function _whereIsNull($where = NULL)
		{
			$list = array();

			if ($where != NULL && count($where) > 0)
			{
				foreach($where as $condition)
				{
					if (!empty($condition))
					{
						array_push($list, $condition);
					}
				}
			}

			return $list;
		}


		/*
		 * _insertFields()
		 * @param array
		 * @return array
		 */
		private function _insertFields($fields)
		{
			foreach ($fields as $key => $value)
			{
				$value = preg_replace("/'/", "\\'", $value);

				$field_name  .= "{$key},";
				$field_value .= "\"{$value}\",";
			}

			$field_name  = preg_replace("/,$/", "", $field_name);
			$field_value = preg_replace("/,$/", "", $field_value);

			return array('name'  => $field_name, 'value' => $field_value);
		}


		/*
		 * _updateFields()
		 * @param array
		 * @return array
		 */
		private function _updateFields($fields)
		{
			foreach ($fields as $key => $value)
			{
				$value = preg_replace("/'/", "\\'", $value);
				$data .= "{$key} = \"{$value}\",";
			}

			$result = preg_replace("/,$/", "", $data);
			return $result;
		}
	}
?>