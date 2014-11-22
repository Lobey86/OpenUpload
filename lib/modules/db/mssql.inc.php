<?php

/*
 * Code by Leonardo F. Cardoso - leonardo.f.cardoso@gmail.com
 * 17 - August - 2009
 *
 * Known issues:
 *    - function mssql_escape_string is not with "Object Oriented" structure.
 *    - simulating "LIMIT / OFFSET" with bad performance
 */

/**
 * Simulate mssql_escape_string
 * @param $string_to_escape
 * @return unknown_type
 */
function mssql_escape_string($string_to_escape) {
	$replaced_string = str_replace("'","''",$string_to_escape);
	return $replaced_string;
}

class mssqlDB extends dbBase {
	var $config;
	var $db;
	var $prefix;


	function mssqlDB($config = array()) {
		$this->config = $config;
		$this->prefix = $config['prefix'];
	}

	function init() {
		$this->db = mssql_connect($this->config['host'], $this->config['user'], $this->config['password'])
		or die('ERROR: connection to db failed!');
		mssql_select_db($this->config['name']) or die('ERROR: database could not be opened.');
	}

	function free() {
		mssql_close($this->db);
	}

	function newId($tbl,$field = 'id',$keys = array ()) {
		$sql = 'SELECT max(['.$field.']) as newid FROM ['.$this->prefix.$tbl.']';
		if (count($keys)>0) {
			$where = '';
			foreach ($keys as $k => $v) {
				if ($where != '') $where .= ' AND ';
				$where .= '['.$k.']=\''.(mssql_escape_string($v)).'\'';
			}
			$sql .= ' WHERE '.$where;
		}
		$res = mssql_query($sql);
		$newid = mssql_fetch_assoc($res);
		mssql_free_result($res);
		return $id['newid']+1;
	}

	function newRandomId($tbl,$field = 'id',$size = 30, $alpha = false) {
		$found = true;
		while ($found) {
			$id = randomName($size,$size,$alpha);
			$sql = 'SELECT '.$field.' FROM ['.$this->prefix.$tbl.'] WHERE ['.$field.']=\''.$id.'\'';
			$res = mssql_query($sql);
			$found = mssql_num_rows($res)>0;
			mssql_free_result($res);
		}
		return $id;
	}

	function count($tbl,$keys = array()) {
		$sql = 'SELECT count(*) AS num FROM ['.$this->prefix.$tbl.']';
		if (count($keys)>0) {
			$where = '';
			foreach ($keys as $k => $v) {
				if ($where != '') $where .= ' AND ';
				$where .= '['.$k.']=\''.(mssql_escape_string($v)).'\'';
			}
			$sql .= ' WHERE '.$where;
		}
		$res = mssql_query($sql);
		$row = mssql_fetch_assoc($res);
		mssql_free_result($res);
		return $row['num'];
	}

	function read($tbl,$keys = array(), $sort = array(), $limit = '', $assoc = array()) {
		$sql = 'SELECT * FROM ['.$this->prefix.$tbl.']';
		if (count($keys)>0) {
			$where = '';
			foreach ($keys as $k => $v) {
				if ($where != '') $where .= ' AND ';
				$where .= '['.$k.']=\''.(mssql_escape_string($v)).'\'';
			}
			$sql .= ' WHERE '.$where;
		}
		if (count($sort)>0) {
			$sorting = '';
			foreach ($sort as $s) {
				if ($sorting!='') $sorting.=',';
				$sorting .= $s;
			}
			$sql .= ' ORDER BY '.$sorting;
		}

		/* SQL Server doesn't have LIMIT/OFFSET
		 * Let's select limit+offset rows and let the code get the needed ones.
		 * Bad performance this way.
		 */
		if ($limit != '') {
			 
			$l = explode(',',$limit);

			$realLimit = $l[1];
			$realOffset= $l[0];
			$topClausule = $realLimit + $realOffset;

			$sql = str_replace('SELECT * ', 'SELECT TOP ' . $topClausule . ' * ', $sql);

		}

		$res = mssql_query($sql);
		if (!$res) { die('query failed: '.$sql); }
		$result = array();

		/* Let's ignore "offset" tuples */
		for ($index = 0; $index < $realOffset; $index++) {
			$row = mssql_fetch_assoc($res);
		}

		while ($row = mssql_fetch_assoc($res)) {
			if (count($assoc)) { /* maybe there is a better way to do this? */
				$str = '$result';
				foreach ($assoc as $k) {
					$str .= '[\''.$row[$k].'\']';
				}
				$str .= '=$row;';
				eval($str);
			} else {
				$result[] = $row;
			}
		}

		mssql_free_result($res);

		return $result;
	}

	/* This is an extended function which extends the select criteria */
	function readex($tbl,$criteria = array(), $sort = array(),$limit = '') {
		$sql = 'SELECT * FROM ['.$this->prefix.$tbl.']';

		if (count($criteria)>0) {
			$where = '';
			foreach ($criteria as $ands) {
				$where_save = $where;
				$where = '';
				foreach ($ands as $v) {
					if ($where != '') $where .= ' AND ';
					$where .= '['.$v[0].']'.$v[1].'\''.(mssql_escape_string($v[2])).'\'';
				}
				if ($where_save!='') {
					$where = $where_save.' OR ('.$where.')';
				} else {
					$where = '('.$where.')';
				}
			}
			$sql .= ' WHERE '.$where;
		}
		if (count($sort)>0) {
			$sorting = '';
			foreach ($sort as $s) {
				if ($sorting!='') $sorting.=',';
				$sorting .= $s;
			}
			$sql .= ' ORDER BY '.$sorting;
		}

		/* SQL Server doesn't have LIMIT/OFFSET
		 * Let's select limit+offset rows and let the code get the needed ones.
		 * Bad performance this way.
		 */
		if ($limit != '') {
			 
			$l = explode(',',$limit);

			$realLimit = $l[1];
			$realOffset= $l[0];
			$topClausule = $realLimit + $realOffset;

			$sql = str_replace('SELECT * ', 'SELECT TOP ' . $topClausule . ' * ', $sql);

		}

		$res = mssql_query($sql);
		if (!$res) { die('query failed: '.$sql); }
		$result = array();

		/* Let's ignore "offset" tuples */
		for ($index = 0; $index < $realOffset; $index++) {
			$row = mssql_fetch_assoc($res);
		}

		while ($row = mssql_fetch_assoc($res)) {
			$result[] = $row;
		}

		mssql_free_result($res);
		return $result;
	}

	function insert($tbl,$values,$fields = array()) {
		$sql = 'INSERT INTO ['.$this->prefix.$tbl.']';
		$flist = '';
		$vlist = '';
		if (count($fields)>0) {
			foreach ($fields as $f) {
				if ($flist!='') $flist .= ',';
				if ($vlist!='') $vlist .= ',';
				$flist .= '['.$f.']';
				$vlist .= '\''.mssql_escape_string($values[$f]).'\'';
			}
		} else {
			foreach ($values as $k => $v) {
				if ($flist!='') $flist .= ',';
				if ($vlist!='') $vlist .= ',';
				$flist .= '['.$k.']';
				$vlist .= '\''.mssql_escape_string($v).'\'';
			}
		}
		$sql .= ' ('.$flist.') VALUES ('.$vlist.')';
		return mssql_query($sql) or die("ERROR: query failed: ".$sql);
	}

	function update($tbl,$values,$keys = array(),$fields = array()) {
		$sql = 'UPDATE ['.$this->prefix.$tbl.']';
		$set = '';
		if (count($fields)>0) {
			foreach ($fields as $f) {
				if ($set!='') $set .= ',';
				$set .= '['.$f.']=\''.mssql_escape_string($values[$f]).'\'';
			}
		} else {
			foreach ($values as $k => $v) {
				if ($set!='') $set .= ',';
				$set .= '['.$k.']=\''.mssql_escape_string($v).'\'';
			}
		}
		$sql .= ' SET '.$set;
		if (count($keys)>0) { /* should always be */
			$where = '';
			foreach ($keys as $k => $v) {
				if ($where != '') $where .= ' AND ';
				$where .= '['.$k.']=\''.mssql_escape_string($v).'\'';
			}
			$sql .= ' WHERE '.$where;
		}
		return mssql_query($sql);
	}

	function delete($tbl,$keys = array()) {
		$sql = 'DELETE FROM ['.$this->prefix.$tbl.']';
		if (count($keys)>0) {
			$where = '';
			foreach ($keys as $k => $v) {
				if ($where != '') $where .= ' AND ';
				$where .= '['.$k.']=\''.mssql_escape_string($v).'\'';
			}
			$sql .= ' WHERE '.$where;
		}
		return mssql_query($sql);
	}
}

?>
