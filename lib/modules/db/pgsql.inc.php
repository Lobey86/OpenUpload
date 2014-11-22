<?php


/* to be implemented soon */

class pgsqlDB extends dbBase {
var $config;
var $db;
var $prefix;


  function pgsqlDB($config = array()) {
    $this->config = $config;
    $this->prefix = $config['prefix'];
  }

  function init() {
    $str = "host=".$this->config['host'];
    $str .= " port=".($this->config['port']!=''?$this->config['port']:'5432');
    $str .= " dbname=".$this->config['name'];
    $str .= " user=".$this->config['user'];
    $str .= " password=".$this->config['password'];
    $this->db = pg_connect($str) or die('ERROR: connection to db failed!');
  }

  function free() {
    pg_close($this->db);
  }

  function newId($tbl,$field = 'id',$keys = array ()) {
    $sql = 'SELECT max("'.$field.'") as newid FROM "'.$this->prefix.$tbl.'"';
    if (count($keys)>0) {
      $where = '';
      foreach ($keys as $k => $v) {
        if ($where != '') $where .= ' AND ';
        $where .= '"'.$k.'"=\''.(pg_escape_string($v)).'\'';
      }
      $sql .= ' WHERE '.$where;
    }
    $res = pg_query($sql);
    $newid = pg_fetch_assoc($res);
    pg_free_result($res);
    return $id['newid']+1;
  }

  function newRandomId($tbl,$field = 'id',$size = 30, $alpha = false) {
    $found = true;
    while ($found) {
      $id = randomName($size,$size,$alpha);
      $sql = 'SELECT '.$field.' FROM "'.$this->prefix.$tbl.'" WHERE "'.$field.'"=\''.$id.'\'';
      $res = pg_query($sql);
      $found = pg_num_rows($res)>0;
      pg_free_result($res);
    }
    return $id;
  }

  function count($tbl,$keys = array()) {
    $sql = 'SELECT count(*) AS num FROM "'.$this->prefix.$tbl.'"';
    if (count($keys)>0) {
      $where = '';
      foreach ($keys as $k => $v) {
        if ($where != '') $where .= ' AND ';
        $where .= '"'.$k.'"=\''.(pg_escape_string($v)).'\'';
      }
      $sql .= ' WHERE '.$where;
    }
    $res = pg_query($sql);
    $row = pg_fetch_assoc($res);
    pg_free_result($res);
    return $row['num'];
  }

  function read($tbl,$keys = array(), $sort = array(), $limit = '', $assoc = array()) {
    $sql = 'SELECT * FROM "'.$this->prefix.$tbl.'"';
    if (count($keys)>0) {
      $where = '';
      foreach ($keys as $k => $v) {
        if ($where != '') $where .= ' AND ';
        $where .= '"'.$k.'"=\''.(pg_escape_string($v)).'\'';
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
    if ($limit != '') {
      $l = explode(',',$limit);
      $sql .= ' LIMIT '.$l[1].' OFFSET '.$l[0];
    }
    $res = pg_query($sql);
    if (!$res) { die('query failed: '.$sql); }
    $result = array();
    while ($row = pg_fetch_assoc($res)) {
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
    pg_free_result($res);
    return $result;
  }

  /* This is an extended function which extends the select criteria */
  function readex($tbl,$criteria = array(), $sort = array(),$limit = '') {
    $sql = 'SELECT * FROM "'.$this->prefix.$tbl.'"';

    if (count($criteria)>0) {
      $where = '';
      foreach ($criteria as $ands) {
        $where_save = $where;
        $where = '';
        foreach ($ands as $v) {
          if ($where != '') $where .= ' AND ';
          $where .= '"'.$v[0].'"'.$v[1].'\''.(pg_escape_string($v[2])).'\'';
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
    if ($limit != '') {
      $l = explode(',',$limit);
      $sql .= ' LIMIT '.$l[1].' OFFSET '.$l[0];
    }
echo $sql;
    $res = pg_query($sql);
    if (!$res) { die('query failed: '.$sql); }
    $result = array();
    while ($row = pg_fetch_assoc($res)) {
      $result[] = $row;
    }
    pg_free_result($res);
    return $result;
  }

  function insert($tbl,$values,$fields = array()) {
    $sql = 'INSERT INTO "'.$this->prefix.$tbl.'"';
    $flist = '';
    $vlist = '';
    if (count($fields)>0) {
      foreach ($fields as $f) {
        if ($flist!='') $flist .= ',';
        if ($vlist!='') $vlist .= ',';
        $flist .= '"'.$f.'"';
        $vlist .= '\''.pg_escape_string($values[$f]).'\'';
      }    
    } else {
      foreach ($values as $k => $v) {
        if ($flist!='') $flist .= ',';
        if ($vlist!='') $vlist .= ',';
        $flist .= '"'.$k.'"';
        $vlist .= '\''.pg_escape_string($v).'\'';
      }
    }
    $sql .= ' ('.$flist.') VALUES ('.$vlist.')';
    return pg_query($sql) or die("ERROR: query failed: ".$sql);
  }

  function update($tbl,$values,$keys = array(),$fields = array()) {
    $sql = 'UPDATE "'.$this->prefix.$tbl.'"';
    $set = '';
    if (count($fields)>0) {
      foreach ($fields as $f) {
        if ($set!='') $set .= ',';
        $set .= '"'.$f.'"=\''.pg_escape_string($values[$f]).'\'';
      }    
    } else {
      foreach ($values as $k => $v) {
        if ($set!='') $set .= ',';
        $set .= '"'.$k.'"=\''.pg_escape_string($v).'\'';
      }
    }
    $sql .= ' SET '.$set;
    if (count($keys)>0) { /* should always be */
      $where = '';
      foreach ($keys as $k => $v) {
        if ($where != '') $where .= ' AND ';
        $where .= '"'.$k.'"=\''.pg_escape_string($v).'\'';
      }
      $sql .= ' WHERE '.$where;
    }
    return pg_query($sql);
  }

  function delete($tbl,$keys = array()) {
    $sql = 'DELETE FROM "'.$this->prefix.$tbl.'"';
    if (count($keys)>0) {
      $where = '';
      foreach ($keys as $k => $v) {
        if ($where != '') $where .= ' AND ';
        $where .= '"'.$k.'"=\''.pg_escape_string($v).'\'';
      }
      $sql .= ' WHERE '.$where;
    }
    return pg_query($sql);
  }  
}

?>