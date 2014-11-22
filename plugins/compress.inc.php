<?php

class compressPlugin extends OpenUploadPlugin {

  function compressPlugin() {
    $this->description = tr('Compress the uploaded files');
    $this->options = array(
      array('name' => 'command', 'description' => tr('Command to be executed. One per line.'), 'type' => 'list'),
      array('name' => 'extension', 'description' => tr('Extensions corresponding to commands.'), 'type' => 'list'),
      array('name' => 'compression', 'description' => tr('Name of compression commands'), 'type' => 'list'),
    );
  }

  function uploadOptions(&$finfo, $acl) {
    if ($acl != 'enable') return true;
    $group = $this->getGroup('command');
    if (count($this->config['command'][$group])==0 and count($this->config['command']['*'])>0) {
      $this->config['command'][$group]=$this->config['command']['*'];
      $this->config['compression'][$group]=$this->config['compression']['*'];
      $this->config['extension'][$group]=$this->config['extenion']['*'];
    }
    if (count($this->config['command'][$group])>0) {
      $this->assign('compress',$this->config['compression'][$group]);
      $this->display('uploadOptions');
    }
    return true;
  }

  function uploadConfirm(&$finfo, $acl) {
    global $_POST;
    if ($acl != 'enable') return true;
    if ($_POST['compress']!='') {
      if ($finfo[0]['compress']==1) return true; /* file already compressed */
      $group = $this->getGroup('command');
      if (count($this->config['command'][$group])==0 and count($this->config['command']['*'])>0) {
        $this->config['command'][$group]=$this->config['command']['*'];
        $this->config['compression'][$group]=$this->config['compression']['*'];
        $this->config['extension'][$group]=$this->config['extension']['*'];
      }
      if (count($this->config['command'][$group])>0) {
        // TODO: more error checking
        /* create a tmp folder where to store the files */
        $tmpdir = randomName(20,20);
        $res = mkdir(app()->config['DATA_PATH'].'/tmp/'.$tmpdir);
        /* move the uploaded files to that folder with the original name */
        foreach ($finfo as $f) {
          rename($f['tmp'],app()->config['DATA_PATH'].'/tmp/'.$tmpdir.'/'.$f['name']);
          $files .= ' "'.chop($f['name']).'"';
          $lfiles .= ' "'.app()->config['DATA_PATH'].'/tmp/'.$tmpdir.'/'.chop($f['name']).'"';
        }
        /* execute the compress command */
        $cmd=$this->config['command'][$group][$_POST['compress']];
        $params['%1']=$finfo[0]['tmp'];
        $params['%2']=$files;
        $params['%3']=$lfiles;
        $params['%4']=app()->config['DATA_PATH'].'/tmp/'.$tmpdir;
        $cmd = strtr($cmd,$params);
        $pwd = getcwd();
        chdir(app()->config['DATA_PATH'].'/tmp/'.$tmpdir);
        exec($cmd,$output);
        chdir($pwd);
        /* remove the files */
        foreach ($finfo as $f) {
          unlink(app()->config['DATA_PATH'].'/tmp/'.$tmpdir.'/'.$f['name']);
        }
        rmdir(app()->config['DATA_PATH'].'/tmp/'.$tmpdir);
        /* update the file information with the compressed one */
        $finfo[0]['mime']='binary/octet-stream';
        $finfo[0]['name']=$tmpdir.'.'.$this->config['extension'][$group][$_POST['compress']];
        $finfo[0]['size']=filesize($finfo[0]['tmp'].'.'.$this->config['extension'][$group][$_POST['compress']]);
        $finfo[0]['tmp']=$finfo[0]['tmp'].'.'.$this->config['extension'][$group][$_POST['compress']];
        for ($i=1; $i<count($finfo); $i++)
          unset($finfo[$i]);
        /* tell the program that the file is already compressed, so not to run it again */
        $finfo[0]['compress']=1;
      }
    }
    return true;
  }

}

?>