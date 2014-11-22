<?php

class FilesModule extends OpenUploadModule {
var $actions = array (
      "u" => array (
          1 => "uploadForm",
          2 => "uploadOptions",
          3 => "uploadConfirm",
          4 => "uploadFileInfo",
         99 => "uploadProgress",
      ), 
      "d" => array (
          1 => "downloadForm",
          2 => "downloadRequest",
          3 => "downloadConfirm",
      ),
      "g" => array (
         1 => "serveFile",
      ),
      "r" => array (
          1 => "removeRequest",
          2 => "removeConfirm",
          3 => "removeResult",
      ),
      "l" => array (
          1 => "fileList",
          2 => "fileDetail",
      ),
    );
var $page;
var $menu;

  function FilesModule() {
    if (app()->user->info('login')!='')
      $files = tr("My Files");
    else
      $files = tr("Public Files");
    $this->page = array (
     "u" => array (
       "title" => tr("File upload"),
     ),
     "d" => array (
       "title" => tr("File download"),
     ),
     "r" => array (
       "title" => tr("File Removal"),
     ),
     "l" => array (
       "title" => $files,
     ),
    );
    $this->menu = array (
      "u" => tr("File Upload"),
      "l" => $files,
      //"d" => tr("File Download"),
      //"r" => tr("File Removal"),
    );
  }

  function init() {
    /* initialize */
  }

  /* real implementation */

  function uploadForm() {
    global $_SESSION;

    unset($_SESSION['user']['u']);
    $_SESSION['user']['identifier']=randomName(40,40);
    switch (app()->config['progress']) {
      case 'uploadprogress':
        $this->tpl->assign('identifiername','UPLOAD_IDENTIFIER');
        break;
      case 'apc':
        $this->tpl->assign('identifiername',ini_get('apc.rfc1867_name'));
        break;
      default: 
        $this->tpl->assign('identifiername','UPLOAD_IDENTIFIER');
        break;
    }
    app()->tpl->assign('identifier',$_SESSION['user']['identifier']);
    $result = app()->pluginAction('uploadForm',$finfo);
  }
  
  function uploadProgress() {
    global $_SESSION;

    if (isset($_SESSION['user']['identifier'])) {
      ob_clean();
      header("Cache-Control: no-cache, must-revalidate");
      header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
      // need this 'cause of IE problems
      header('Content-Type: text/html; Charset=utf-8');

      $progress = array('complete' => 0, 'total' => 0, 'percentage' => 0, 'files' => 0);
      switch (app()->config['progress']) {
        case 'uploadprogress':
          $res = uploadprogress_get_info($_SESSION['user']['identifier']);
          if (isset($res['bytes_uploaded'])) {
            $progress['complete'] = $res['bytes_uploaded'];
            if ($res['bytes_total']>0)
              $progress['total'] = $res['bytes_total'];
            $progress['percentage'] = floor(($res['bytes_uploaded'] / $res['bytes_total'])*100);
            $progress['files'] = $res['files_uploaded'];
          }
          break;
        case 'apc': 
          $res = apc_fetch(ini_get('apc.rfc1867_prefix').$_SESSION['user']['identifier']);
          if ($res) {
            $progress['complete'] = $res['current'];
            $progress['total'] = $res['total'];
            if ($res['total']>0)
              $progress['percentage'] = floor(($res['current'] / $res['total'])*100);
            $progress['files'] = 0;
          }
          break;
      }
      app()->tpl->assign('progress',$progress);
      app()->display('modules/files/uploadProgress');
      exit;
    }
    exit;
  }

  function uploadOptions() {
    global $_SESSION;
    global $_FILES;
    global $_SERVER;

    if (isset($_FILES['upload'])) {
      if ($_FILES['upload']['error']>0) {
        switch ($_FILES['upload']['error']) { /* taken from here: http://it.php.net/manual/en/features.file-upload.errors.php */
          case 1: $msg = 'Maximum upload size for site wide configuration reached'; break;
          case 2: $msg = 'Maximum file size exceeded!'; break;
          case 3: $msg = 'Partial file transfer error!'; break;
          case 4: $msg = 'No file was uploaded!'; break;
          case 6: $msg = 'Missing temporary directory'; break;
          case 7: $msg = 'Can\'t write to temporary diretory!'; break;
          case 8: $msg = 'Upload blocked by extension!'; break;
          default:
             $msg = tr('Upload failed for Unknown error code: %1',$_FILES['upload']['error']); break;
        }
        app()->log('warning','uploadOptions','','DENY','Upload error: '.$msg);
        app()->error(tr($msg));
        $this->nextStep(1);
      } else if ($_FILES['upload']['size']>app()->user->info('max_upload_size')) {
          app()->log('warning','uploadOptions','','DENY','Maximum file size exceeded!');
          app()->error(tr('Maximum file size exceeded!'));
          break;
      } else {
        /* prepare the file */
        $tmpname  = app()->config['DATA_PATH'].'/tmp/'.randomName();
        for ($i = 0; $i<app()->config['multiupload']; $i++) {
          $u = 'upload';
          $tmpnamex = $tmpname;
          if ($i>0) {
            $u = 'upload_'.$i;
            $tmpnamex = $tmpname.'_'.$i;
          }
          if (isset($_FILES[$u]) and $_FILES[$u]['tmp_name']!='') {
            /* fail if something goes wrong */
            if (!move_uploaded_file($_FILES[$u]['tmp_name'],$tmpnamex)) {
               $this->error(tr('Failed moving the file on the server, please check the configuration!'));
            }
            $_SESSION['user']['u'][$i]['tmp']=$tmpnamex;
            /* get the file mime type, and do not rely on what the browser sends */
            $mime = get_mime_type($tmpnamex,$_FILES[$u]['type']);
            $_SESSION['user']['u'][$i]['mime']=$mime;
            $_SESSION['user']['u'][$i]['name']=$_FILES[$u]['name'];
            $_SESSION['user']['u'][$i]['size']=$_FILES[$u]['size'];
            $_SESSION['user']['u'][$i]['ip']=$_SERVER['REMOTE_ADDR'];
            $_SESSION['user']['u'][$i]['user_login']=app()->user->info('login');
          }
        }
        $result = app()->pluginAction('uploadComplete',$_SESSION['user']['u']);
        if (!$result) { /* some plugin blocked the upload */
          /* remove the file */
          foreach ($_SESSION['user']['u'] as $f) {
            @unlink($f['tmp']);
          }
          unset($_SESSION['user']['u']);
          redirect();
        }
        $this->nextStep(app()->step);
      }
    } else if (!isset($_SESSION['user']['u'][0])) {
      redirect();
    }
    $result = app()->pluginAction('uploadOptions',$_SESSION['user']['u']);
    if (!$result) { /* some plugin blocked the upload */
      /* remove the files */
      unset($_SESSION['user']['u']);
      redirect();
    }
    $this->tpl->assign('finfo',$_SESSION['user']['u'][0]);
    $this->tpl->assign('files',$_SESSION['user']['u']);
    /* ask for information on the file */
  }
    
  function uploadConfirm() {
    global $_POST;
    global $_SESSION;

    /* save the file */
    /* send an e-mail if requested */
    /* display the information on the upload */
    if (isset($_POST['description'])) {
      /* now check plugins and if ok add file otherwise redirect */
      $result = app()->pluginAction('uploadConfirm',$_SESSION['user']['u']);
      if (!$result)
        $this->prevStep();
      for ($i = 0; $i<count($_SESSION['user']['u']); $i++) {
        $finfo = $_SESSION['user']['u'][$i];
        $finfo['description'] = htmlentities($_POST['description']);
        if ($i==0) {
          $s = isset(app()->config['id_max_length'])?app()->config['id_max_length']:30;
          $a = isset(app()->config['id_use_alpha'])?app()->config['id_use_alpha']=='yes':false;
          $finfo['id']= app()->db->newRandomId('files','id',$s,$a);
          $mainid = $finfo['id'];
          $remove = app()->db->newRandomId('files','remove',$s,$a);
          $date = date('Y-m-d H:i:s');
        } else {
          $finfo['id']=$mainid.'_'.$i;
        }
        /* everything ok then add the file */
        $finfo['remove']= $remove;
        $finfo['upload_date'] = $date;
        app()->db->insert('files',$finfo,array('id','name','mime','description','size','remove','user_login','ip','upload_date'));
        if ($i==0) {
          foreach (app()->plugins as $plugin) {
            if (count($plugin->fields)>0) {
              foreach ($plugin->fields as $f) {
                if (isset($finfo[$f])) {
                  $pinfo['file_id'] = $finfo['id'];
                  $pinfo['module'] = $plugin->name;
                  $pinfo['name']=$f;
                  $pinfo['value']=$finfo[$f];
                  app()->db->insert('file_options',$pinfo,array('file_id','module','name','value'));
                }
              }
            }
          }
        } else {
          $pinfo['file_id'] = $finfo['id'];
          $pinfo['module'] = 'files';
          $pinfo['name']='group';
          $pinfo['value']=$mainid;
          app()->db->insert('file_options',$pinfo,array('file_id','module','name','value'));
        }
        /* move the file to the actual location */
        rename($_SESSION['user']['u'][$i]['tmp'],app()->config['DATA_PATH'].'/'.$finfo['id']);
        $_SESSION['user']['u'][$i]=$finfo;
      }
      app()->log('notice','uploadConfirm','','ALLOW',$mainid);
      $this->nextStep();
    }
  }

  function setupLinks(&$finfo) {
      /* get the file info */
      $a = 'action'; $i = 'id'; $r = 'removeid';
      if (app()->config['use_short_links']=='yes') {
        $a = 'a'; $i = 'i'; $r = 'r';
      }
      $finfo[0]['downloadlink']= app()->config['WWW_SERVER'].app()->config['WWW_ROOT'].'/?'.$a.'=d&'.$i.'='.$finfo[0]['id'];
      $finfo[0]['removelink']=app()->config['WWW_SERVER'].app()->config['WWW_ROOT'].
                                 '/?'.$a.'=r&'.$i.'='.$finfo[0]['id'].'&'.$r.'='.$finfo[0]['remove'];
   }

  function uploadFileInfo() {
    if (isset($_SESSION['user']['u'][0]['id'])) {
      $finfo = $_SESSION['user']['u'];
      $this->setupLinks($finfo);
      $result = app()->pluginAction('uploadFileInfo',$finfo,false);
      $this->tpl->assign('finfo',$finfo[0]);
      $this->tpl->assign('files',$finfo);
      $this->tpl->assign('webbase',app()->config['WWW_SERVER'].app()->config['WWW_ROOT']);
    } else {
      redirect();
    }
  }
/**/
  function loadFile($id) {
    $finfo = app()->db->read('files',array('id'=>$id));
    $pinfo = app()->db->read('file_options',array('file_id' => $id));
    foreach ($pinfo as $v) {
      $finfo[0][$v['name']]=$v['value'];
    }
    $afiles = app()->db->read('file_options',array('module' => 'files', 'name' => 'group', 'value' => $id));
    if (count($afiles)>0) {
      foreach ($afiles as $k => $a) {
        $afile = app()->db->read('files',array('id'=>$a['file_id']));
        $finfo[$k+1]=$afile[0];
      }
    }
//print_r($finfo); exit();
    return $finfo;
  }

  function downloadForm() {
    global $_SESSION;
    global $_GET;

    unset($_SESSION['user']['d']);
    if (isset($_GET['id']) or isset($_GET['i'])) {
       $_SESSION['user']['d'][0]['id'] = isset($_GET['id'])?$_GET['id']:$_GET['i'];
       $this->nextStep();
    }
    $finfo = array();
    app()->pluginAction('downloadForm',$finfo,false);
  }

  function downloadRequest() {
    global $_GET;
    global $_POST;
    global $_SESSION;

    $id = '';
    if (isset($_POST['id'])) {
       $id = $_POST['id'];
    } else if (isset($_SESSION['user']['d'][0]['id'])) {
       $id = $_SESSION['user']['d'][0]['id'];
    }
    /* check if download exists, and what are the properties */
    if ($id != '') {
      $finfo = $this->loadFile($id);
      if ($finfo[0]['id']!=$id or isset($finfo[0]['group'])) {
        app()->log('warning','downloadRequest','','DENY','File does not exist: ID:'.$id);
        app()->error(tr('Requested file does not exist!'));
        $this->prevStep();
      } else {
        $_SESSION['user']['d']=$finfo;
        $_SESSION['user']['d'][0]['protected'] = true;
        $this->tpl->assign('finfo',$finfo[0]);
        $this->tpl->assign('files',$finfo);
        $result = app()->pluginAction('downloadRequest',$finfo,false);
        if ($result) {
          app()->log('info','downloadRequest','','ALLOW',$id);
          $_SESSION['user']['d'][0]['protected']=false;
          $this->nextStep();
        }
      }
    }
  }

  function downloadConfirm() {

    /* here we do the actual download of the file */
    if (!isset($_SESSION['user']['d'])) {
       redirect();
    } else if ($_SESSION['user']['d'][0]['candownload']=='ok') {
      $finfo = $_SESSION['user']['d'];
      $this->tpl->assign('finfo',$finfo[0]);
      $this->tpl->assign('files',$finfo);
      /* download is allowed */
    } else {
      $finfo = $_SESSION['user']['d'];
      /* check wether the plugins are ok */
      $result = app()->pluginAction('downloadConfirm',$finfo);
      if (!$result)
        $this->prevStep();
      for ($i = 0; $i<count($finfo); $i++)
        $finfo[$i]['candownload']='ok';
      $_SESSION['user']['d']=$finfo;
      /* now the user can download it */
      $this->nextStep(app()->step);
    }
  }

  function serveFile() {
    global $_SESSION;
    global $_POST;
    global $_GET;

    $num = 0;
    if (isset($_GET['fid'])) {
      $num = $_GET['fid'];
    }
    /* here we do the actual download of the file */
    if (!isset($_SESSION['user']['d'])) {
      redirect();
    } else if ($_SESSION['user']['d'][$num]['candownload']!='ok') {
      $this->nextStep(2,'d');
    } else {
      $finfo = $_SESSION['user']['d'];
      /* check wether the plugins are ok */
      $result = app()->pluginAction('serveFile',$finfo);
      if (!$result)
        $this->nextStep(3,'d');
      //$_SESSION['user']['d'][$num]['candownload']='ok';
      /* if we got this far the download should begin serving */
      $file = app()->config['DATA_PATH'].'/'.$finfo[$num]['id'];
      $filesize = filesize($file);
      /* set to not timeout within default setting */
      if (isset(app()->config['max_download_time'])) {
        set_time_limit(app()->config['max_download_time']*60);
      } else {
        set_time_limit(7200); /* 2 hours should be enough */
      }
      app()->log('notice','serveFile','','ALLOW',$finfo[$num]['id']);
     /* disable and clean output buffer so it won't reach memory limit */
      ob_end_clean();
      header('Content-Description: File Transfer');
      header('Content-Type: '.$finfo[$num]['mime']);
      header('Content-Length: '.$filesize);
      header('Content-Disposition: attachment; filename="'.$finfo[$num]['name'].'"');
      header('Content-Transfer-Encoding: binary');
      header('Expires: 0');
      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      header('Pragma: public');

      readfile($file);
      /* file should have been sent now let's reset the download info */
      if (!$_SESSION['user']['d'][0]['protected'])
        $_SESSION['user']['d'][$num]['candownload']='ok';
      else 
        $_SESSION['user']['d'][$num]['candownload']='ko';
      exit(0);
    }
  }

  function removeRequest() {
    global $_GET;
    global $_SESSION;
   
    $id = '';
    if (isset($_GET['id']) or isset($_GET['i'])) {
       $id = isset($_GET['id'])?$_GET['id']:$_GET['i'];
       $r = isset($_GET['removeid'])?$_GET['removeid']:$_GET['r'];
    } else if (isset($_SESSION['user']['r'][0]['id'])) {
       $id = $_SESSION['user']['r'][0]['id'];
       $r = $_SESSION['user']['r'][0]['remove'];
    }
    /* check if file exists, and what are the properties */
    if ($id != '') {
      $finfo = $this->loadFile($id);
      if ($finfo[0]['id']!=$id or isset($finfo[0]['group'])) {
        app()->error(tr('Wrong file id!'));
        redirect();
      } else if ($r!=$finfo[0]['remove']) {
        app()->error(tr('Wrong file id!')); /* don't give the user much info on this */
        redirect();
      } else {
        $_SESSION['user']['r']=$finfo;
        $this->tpl->assign('files',$finfo);
        $this->tpl->assign('finfo',$finfo[0]);
        if (app()->config['allow_unprotected_removal']=='yes')
          $result = true;
        else
          $result = app()->pluginAction('removeRequest',$finfo,false);
        if ($result) {
          app()->log('info','removeRequest','','ALLOW',$id);
          $_SESSION['user']['r'][0]['canremove']='ok'; /* file has no protection */
          $this->nextStep();
        }
      }
    } else {
      app()->error(tr('Wrong file id!'));
      redirect();
    }
  }

  function removeConfirm() {
    $finfo = $_SESSION['user']['r'];

    /* here we do the actual remove of the file */
    if (!isset($_SESSION['user']['r'])) {
       redirect();
    } else if ($_SESSION['user']['r'][0]['canremove']=='ok') {
      $finfo = $_SESSION['user']['r'];
      $this->tpl->assign('finfo',$finfo[0]);
      $this->tpl->assign('files',$finfo);
      /* removal is allowed */
    } else {
      $finfo = $_SESSION['user']['r'];
      /* check wether the plugins are ok */
      if (app()->config['allow_unprotected_removal']=='yes')
        $result = true;
      else
        $result = app()->pluginAction('removeConfirm',$finfo);
      if (!$result)
        $this->prevStep();
      /* now we can remove the file */
      $_SESSION['user']['r'][0]['canremove']='ok';
      $this->tpl->assign('finfo',$finfo[0]);
      $this->tpl->assign('files',$finfo);
    }
  }

  function removeResult() {
    global $_POST;

    if (!isset($_SESSION['user']['r'])) {
       redirect();
    } else  if (isset($_POST['confirmremove']) and ($_SESSION['user']['r'][0]['canremove']=='ok')) {
      $finfo = $_SESSION['user']['r'];
      $result = app()->pluginAction('removeResult',$finfo,false);
      if (!$result)
        $this->prevStep();
      foreach ($finfo as $f) {
         app()->db->delete('files',array('id' => $f['id']));
         app()->db->delete('file_options',array('file_id' => $f['id']));
         $file = app()->config['DATA_PATH'].'/'.$f['id'];
         @unlink($file);
      }
      app()->log('notice','removeResult','','ALLOW',$finfo[0]['id']);
      unset($_SESSION['user']['r']); /* remove any file reference */
      $this->tpl->assign('files',$finfo);
      $this->tpl->assign('finfo',$finfo[0]);
    } else {
      $this->prevStep();
    }
  }

  function fileList() {
    global $_GET;
    /* TODO: need paging ... */
    $rows = 20;
    $page = isset($_GET['page'])?$_GET['page']:1;
    $offset = ($page -1)*$rows;
    $count = app()->db->count('files',array('user_login'=>app()->user->info('login')));
    $pages = ceil($count / $rows)+1;
    $files = app()->db->read('files',array('user_login'=>app()->user->info('login')),array('upload_date desc'),$offset.','.$rows);
    $result = app()->pluginAction('fileList',$files,false);
    if (!$result) 
         redirect();
    $this->tpl->assign('pagen',$page);
    $this->tpl->assign('pages',$pages);
    $this->tpl->assign('files',$files);
  }

  function fileDetail() {
    global $_GET;

    if (!isset($_GET['id'])) {
      $this->nextStep(1);
    }
    $finfo = $this->loadFile($_GET['id']);
    if (count($finfo)==0) {
      app()->error(tr('Wrong file id!'));
      $this->nextStep(1);
    } else if ($finfo[0]['user_login']!=app()->user->info('login')) {
      /* the user has no right to access this file !!! */
      app()->error(tr('Wrong file id!'));
      $this->nextStep(1);
    } else if (isset($finfo[0]['group'])) {
      /* it's a group file */
      app()->error(tr('Wrong file id!'));
      $this->nextStep(1);
    }
    $this->setupLinks($finfo);
    if (app()->user->info('login')=='') {
      unset($finfo[0]['removelink']);
    }
    $result = app()->pluginAction('fileDetail',$finfo,false);
    if (!$result) {
      $this->nextStep(1);
    }
    $this->tpl->assign('finfo',$finfo[0]);
    $this->tpl->assign('files',$finfo);
  }
}

?>
