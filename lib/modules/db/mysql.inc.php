<?php
class mysqlDB extends dbBase {
  var $db;
  var $prefix;
  var $config;

  function mysqlDB($config) {
    $this->prefix = $config['prefix'];
    $this->config = $config;
  }

  function init() {
  /* connect to the database */
    mysql_connect($this->config['host'],$this->config['user'],$this->config['password'])
        or die('ERROR: connection to database failed!');

    $this->db = mysql_select_db($this->config['name']) or die('ERROR: database could not be opened');
  }


  function free() {
    mysql_close();
  }

  function newId($tbl,$field = 'id',$keys = array ()) {
    $sql = 'SELECT max(`'.$field.'`) as newid FROM `'.$this->prefix.$tbl.'`';
    if (count($keys)>0) {
      $where = '';
      foreach ($keys as $k => $v) {
        if ($where != '') $where .= ' AND ';
        $where .= '`'.$k.'`="'.(mysql_real_escape_string($v)).'"';
      }
      $sql .= ' WHERE '.$where;
    }
    $res = mysql_query($sql);
    $newid = mysql_fetch_assoc($res);
    mysql_free_result($res);
    return $id['newid']+1;
  }

  function newRandomId($tbl,$field = 'id',$size = 30, $alpha = false) {
    $found = true;
    while ($found) {
      $id = randomName($size,$size,$alpha);
      $sql = 'SELECT '.$field.' FROM `'.$this->prefix.$tbl.'` WHERE `'.$field.'`="'.$id.'"';
      $res = mysql_query($sql);
      $found = mysql_num_rows($res)>0;
      mysql_free_result($res);
    }
    return $id;
  }

  function count($tbl,$keys = array()) {
    $sql = 'SELECT count(*) AS num FROM `'.$this->prefix.$tbl.'`';
    if (count($keys)>0) {
      $where = '';
      foreach ($keys as $k => $v) {
        if ($where != '') $where .= ' AND ';
        $where .= '`'.$k.'`="'.(mysql_real_escape_string($v)).'"';
      }
      $sql .= ' WHERE '.$where;
    }
    $res = mysql_query($sql);
    $row = mysql_fetch_assoc($res);
    mysql_free_result($res);
    return $row['num'];
  }

  function read($tbl,$keys = array(), $sort = array(), $limit = '', $assoc = array()) {
    $sql = 'SELECT * FROM `'.$this->prefix.$tbl.'`';
    if (count($keys)>0) {
      $where = '';
      foreach ($keys as $k => $v) {
        if ($where != '') $where .= ' AND ';
        $where .= '`'.$k.'`="'.(mysql_real_escape_string($v)).'"';
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
      $sql .= ' LIMIT '.$limit;
    }
    $res = mysql_query($sql);
    if (!$res) { die('query failed: '.$sql); }
    $result = array();
    while ($row = mysql_fetch_assoc($res)) {
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
    mysql_free_result($res);
    return $result;
  }

  /* This is an extended function which extends the select criteria */
  function readex($tbl,$criteria = array(), $sort = array(),$limit = '') {
    $sql = 'SELECT * FROM `'.$this->prefix.$tbl.'`';
    if (count($criteria)>0) {
      $where = '';
      foreach ($criteria as $ands) {
        $where_save = $where;
        $where = '';
        foreach ($ands as $v) {
          if ($where != '') $where .= ' AND ';
          $where .= '`'.$v[0].'`'.$v[1].'"'.(mysql_real_escape_string($v[2])).'"';
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
      $sql .= ' LIMIT '.$limit;
    }
    $res = mysql_query($sql);
    if (!$res) { die('query failed: '.$sql); }
    $result = array();
    while ($row = mysql_fetch_assoc($res)) {
      $result[] = $row;
    }
    mysql_free_result($res);
    return $result;
  }

  function insert($tbl,$values,$fields = array()) {
    $sql = 'INSERT INTO `'.$this->prefix.$tbl.'`';
    $flist = '';
    $vlist = '';
    if (count($fields)>0) {
      foreach ($fields as $f) {
        if ($flist!='') $flist .= ',';
        if ($vlist!='') $vlist .= ',';
        $flist .= '`'.$f.'`';
        $vlist .= '"'.mysql_real_escape_string($values[$f]).'"';
      }    
    } else {
      foreach ($values as $k => $v) {
        if ($flist!='') $flist .= ',';
        if ($vlist!='') $vlist .= ',';
        $flist .= '`'.$k.'`';
        $vlist .= '"'.mysql_real_escape_string($v).'"';
      }
    }
    $sql .= ' ('.$flist.') VALUES ('.$vlist.')';
    return mysql_query($sql);
  }

  function update($tbl,$values,$keys = array(),$fields = array()) {
    $sql = 'UPDATE `'.$this->prefix.$tbl.'`';
    $set = '';
    if (count($fields)>0) {
      foreach ($fields as $f) {
        if ($set!='') $set .= ',';
        $set .= '`'.$f.'`="'.mysql_real_escape_string($values[$f]).'"';
      }    
    } else {
      foreach ($values as $k => $v) {
        if ($set!='') $set .= ',';
        $set .= '`'.$k.'`="'.mysql_real_escape_string($v).'"';
      }
    }
    $sql .= ' SET '.$set;
    if (count($keys)>0) { /* should always be */
      $where = '';
      foreach ($keys as $k => $v) {
        if ($where != '') $where .= ' AND ';
        $where .= '`'.$k.'`="'.mysql_real_escape_string($v).'"';
      }
      $sql .= ' WHERE '.$where;
    }
    return mysql_query($sql);
  }

  function delete($tbl,$keys = array()) {
    $sql = 'DELETE FROM `'.$this->prefix.$tbl.'`';
    if (count($keys)>0) {
      $where = '';
      foreach ($keys as $k => $v) {
        if ($where != '') $where .= ' AND ';
        $where .= '`'.$k.'`="'.mysql_real_escape_string($v).'"';
      }
      $sql .= ' WHERE '.$where;
    }
    return mysql_query($sql);
  }
}

?>