<?php

/* description: This plugin sets the user group based on it's connection IP 
 *              this allows a company to overcome the login part.           
 *              It does that only for the 'unregistered' users              
 * NOTE: this is still experimantal                                         
 *                                                                          
 * Installation: 
 * 1. Put this file inside the plugins directory of Open Upload 
 * 2. change the www/config.inc.php and add this plugin (I think it's better to put it as the first one in the list) 
 * 3. Login as administrator
 * 4. Set the options for the 'unregistered' group for this plugin
 *    The options associate 1 line IP to 1 Line group so you can differentiate depending on the IP
 *    i.e. IPs:  127.0.0.1
 *               10.10.0.0/24
 *       Groups: admins
 *               registered
 * 5. Enable the plugin for the 'unregisterd' group (it won't work for other groups)
 *
 * Be ware: the files uploaded with this method are visible by all the people in the group if the file listing is enabled for it
 * I'd suggest to create a separate group for this uploads (as users can still login) and disable file listing
 *
 */

class groupOnIpPlugin extends OpenUploadPlugin {

  function groupOnIpPlugin() {
    $this->description = tr('Set the default group based on the IP.');
    $this->options = array(
      array('name' => 'ip', 'description' => tr('Client IPs'), 'type' => 'list'),
      array('name' => 'group', 'description' => tr('Associated groups'), 'type' => 'list'),
    );
  }

  function init() {
    $this->loadConfig();
    /* check if enabled, and the source IP matches then it will set the associated group */
    if (app()->user->group()!=app()->config['register']['nologingroup']) return true;
    
    /* TODO: should I check if I have already done this */
           

    $group = $this->getGroup('ip');
    if (count($this->config['ip'][$group])==0 and count($this->config['ip']['*'])>0) {
      $this->config['ip'][$group]=$this->config['ip']['*'];
      $this->config['group'][$group]=$this->config['group']['*'];
    }
    if (count($this->config['ip'][$group])>0) {
      foreach ($this->config['ip'][$group] as $k => $ip ) {
        if (app()->matchIP($_SERVER['REMOTE_ADDR'],$ip)) {
          /* set the corrisponding value and return */
          if ($this->config['group'][$group][$k]!='') {
            app()->user->setInfo('group',$this->config['group'][$group][$k]);
            return;
          }
        }
      }
    }
   
    /* TODO: save that this operation was already done ? */

  }
}
?>