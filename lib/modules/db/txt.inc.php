<?php

/* To be fully tested */

/* plain file db */
class txtDB extends dbBase {
  var $rootdir;
  var $prefix;

  function txtDB($config) {
    $this->tables = array (
      "files" => array (
        "type" => "file",
        "fields" => array (
          "id", "name", "mime", "description", "size", "remove", "user_login", "ip", "upload_date",
        )
      ),
      "users" => array (
        "type" => "file",
        "fields" => array ( 
          "id", "login", "password", "name", "group_name", "email", "lang", "reg_date", "regid", "active",
        ),
        "auto_increment" => "id",
      ),
      "groups" => array (
        "type" => "file",
        "fields" => array (
          "name", "description",
        ),
      ),
      "file_options" => array (
        "type" => "file",
        "fields" => array (
          "id", "file_id", "module", "name", "value",
        ),
        "auto_increment" => "id",
      ),
      "acl" => array (
        "type" => "file",
        "fields" => array (
          "id", "module", "action", "group_name", "access",
        ),
        "auto_increment" => "id",
      ),
      "langs" => array (
        "type" => "file",
        "fields" => array (
          "id", "name", "locale", "browser", "charset", "active",
        ),
      ),
      "plugin_acl" => array (
        "type" => "file",
        "fields" => array (
          "id", "group_name", "plugin", "access",
        ),
        "auto_increment" => "id",
      ),
      "plugin_options" => array (
        "type" => "file",
        "fields" => array (
          "id", "plugin", "group_name", "name", "value",
        ),
        "auto_increment" => "id",
      ),
      "banned" => array (
        "type" => "file",
        "fields" => array (
          "id", "ip", "access", "priority",
        ),
        "auto_increment" => "id",
      ),
      "activitylog" => array (
        "type" => "file",
        "fields" => array (
          "id", "level", "log_time", "ip", "user_login", "module", "action", "realaction", "plugin", "result", "moreinfo",
        ),
        "auto_increment" => "id",
      ),
    );
    $this->baseDir=$config['rootdir'];
    $this->prefix=$config['prefix'];

  }

  function readTxt ($file) {
    $x = file($file);
    /* first line is the structure */
    $fields = explode('|',chop($x[0]));
    $result = array();
    for ($i=1; $i<count($x); $i++) {
      $r = explode('|',chop($x[$i]));
      foreach ($fields as $k => $f) {
        $row[$f] = $r[$k];
      }
      $result[]=$row;
    }
    return $result;
  }

  function writeTxt($file,$rows,$fields) {
    $x = '';
    foreach ($fields as $f) {
      if ($x!='') $x .= '|';
      $x .= $f;
    }
    $x .= "\n";
    foreach ($rows as $row) {
      $r = '';
      foreach ($fields as $f) {
        if ($r!='') $r .= '|'; 
        $r .= $row[$f];
      }
      $x .= $r."\n";
    }
    return file_put_contents($file,$x);
  }

  function init() {
    /* initialize file names */
    if (!is_dir($this->baseDir)) {
       die(tr('ERROR: database folder not found!'));
    }
    foreach ($this->tables as $n => $d) {
      if ($d['type'] == "dir") {
        if (!is_dir($this->baseDir.'/'.$this->prefix.$n)) {
          if (!mkdir($this->baseDir.'/'.$this->prefix.$n)) {
            die(tr('ERROR: Could not create folder for %1 table!',$n));
          }
        }
      } else if ($d['type'] == "file") {
        if (!file_exists($this->baseDir.'/'.$this->prefix.$n.'.txt')) {
          $str = '';
          foreach ($d['fields'] as $f) {
            if ($str != '') $str .= '|';
            $str .= $f;
          }
          if (!file_put_contents($this->baseDir.'/'.$this->prefix.$n.'.txt',$str."\n")) {
            die(tr('ERROR: Could not create file for %1 table!',$n));
          }
        }
      }
    }
  }

  function newId($tbl,$field = 'id',$keys = array ()) {
    return 1;
  }

  function newRandomId($tbl,$field = 'id',$size = 30, $alpha = false) {
    $found = true;
    $rows = $this->readTxt($this->baseDir.'/'.$tbl.'.txt');
    while ($found) {
      $id = randomName($size,$size,$alpha);
      $found = false;
      foreach ($rows as $row) {
        /* should check for the exsistence */
        if ($row[$field]==$id) {
          $found = true;
        }
      }
    }
    return $id;
  }

  function count($tbl,$keys = array()) {
// TODO: check for keys
    if ($this->tables[$tbl]['type'] == "dir") {
      $a = scandir($this->baseDir.'/'.$this->prefix.$tbl);
      return count($a)-2; /* remove . and .. */
    } else {
      $a = file($this->baseDir.'/'.$this->prefix.$tbl.'.txt');
      return count($a)-1; /* remove the first line */
    }
  }

  function read($tbl,$keys = array(), $sort = array(), $limit = '', $assoc = array()) {
    $file = $this->baseDir.'/'.$this->prefix.$tbl;
    if ($this->tables[$tbl]['type']=='dir') {
      
    } else {
      $result = array();
      $rows = $this->readTxt($file.'.txt');
      foreach ($rows as $row) {
         $add = true;
         if (count($keys)>0) {
           foreach ($keys as $n => $k) {
             if ($row[$n]!=$k) {
               $add = false;
               break;
             }
           }
         }
         if ($add) {
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
      }
/* need to add the limit and the sorting */
    }
    return $result;
  }

  /* This is an extended function which extends the select criteria */
  function readex($tbl,$criteria = array(), $sort = array(),$limit = '') {
    $file = $this->baseDir.'/'.$this->prefix.$tbl;
    if ($this->tables[$tbl]['type']=='dir') {
      
    } else {
      $result = array();
      $rows = $this->readTxt($file.'.txt');
      foreach ($rows as $row) {
         $add = false;
         if (count($criteria)>0) {
           foreach ($criteria as $ands) {
             $andres = true;
             foreach ($ands as $v) {
               switch($v[1]) {
                 case '=':
                   $sres = $row[$v[0]]==$v[2];
                   break;
                 case '<':
                   $sres = $row[$v[0]]<$v[2];
                   break;
                 case '>':
                   $sres = $row[$v[0]]>$v[2];
                   break;
                 case '<=':
                   $sres = $row[$v[0]]<=$v[2];
                   break;
                 case '>=':
                   $sres = $row[$v[0]]>=$v[2];
                   break;
                 case '!=':
                   $sres = $row[$v[0]]!=$v[2];
                   break;
                 default:
                   app()->error(tr('Unsupported query criteria %1',$v[1]));
                   return array();
               }
               $andres = ($andres and $sres)?true:false;
             }
             $add = ($add or $andres)?true:false;
           }
         } else {
           $add = true;
         }
         if ($add) {
           $result[] = $row;
         }
      }
    }
    return $result;
  }

  function insert($tbl,$values,$fields = array()) {
     $file = $this->baseDir.'/'.$tbl;
     if ($this->tables[$tbl]['type']=='dir') {
     } else {
       $rows = $this->readTxt($file.'.txt');
       $row = array();
       if ($this->tables[$tbl]['auto_increment']!='') {
         if (count($rows)>0)
           $row[$this->tables[$tbl]['auto_increment']]=$rows[count($rows)-1][$this->tables[$tbl]['auto_increment']]+1;
         else
          $row[$this->tables[$tbl]['auto_increment']]=1;
       }
       if (count($fields)>0) {
         /* init the values */
         foreach ($this->tables[$tbl]['fields'] as $f) {
           if ($this->tables[$tbl]['auto_increment']!=$f)
             $row[$f]='';
         }
         foreach ($fields as $f) {
           if ($this->tables[$tbl]['auto_increment']!=$f)
             $row[$f]=$values[$f];
         }
         $rows[]=$row;
       } else {
         foreach ($this->tables[$tbl]['fields'] as $f) {
           if ($this->tables[$tbl]['auto_increment']!=$f)
             $row[$f]=$values[$f];
         }
         $rows[]=$row;
       }
       return $this->writeTxt($file.'.txt',$rows,$this->tables[$tbl]['fields']);
     }
  }

  function update($tbl,$values,$keys = array(),$fields = array()) {
     $file = $this->baseDir.'/'.$tbl;
     if ($this->tables[$tbl]['type']=='dir') {
     } else {
       $rows = $this->readTxt($file.'.txt');
       foreach ($rows as $i => $r) {
         $update = true;
         if (count($keys)>0) {
           foreach ($keys as $k => $v) {
             if ($r[$k]!=$v) {
               $update = false;
               break;
             }
           }
         }
         if ($update) {
           $row = $r;
           if (count($fields)>0) {
             foreach ($fields as $f) {
               $row[$f]=$values[$f];
             }
           } else {
             foreach ($this->tables[$tbl]['fields'] as $f) {
               $row[$f]=$values[$f];
             }
           }
           $rows[$i]=$row;
         }
       }
       return $this->writeTxt($file.'.txt',$rows,$this->tables[$tbl]['fields']);
     }
  }

  function delete($tbl,$keys = array()) {
     $file = $this->baseDir.'/'.$tbl;
     if ($this->tables[$tbl]['type']=='dir') {
     } else {
       $rows = $this->readTxt($file.'.txt');
       foreach ($rows as $i => $r) {
         $delete = true;
         if (count($keys)>0) {
           foreach ($keys as $k => $v) {
             if ($r[$k]!=$v) {
               $delete = false;
               break;
             }
           }
         }
         if ($delete) {
           unset($rows[$i]);
         }
       }
       return $this->writeTxt($file.'.txt',$rows,$this->tables[$tbl]['fields']);
     }
  }


}
?>