<?php



function smarty_function_db_table($params, &$smarty){

  $data = $params['data'];
  isset($params['header'])?$headers = $params['header']:$headers=array();
  isset($params['fields'])?$fields = $params['fields']:$fields=array();
  
  $fields_set = count($fields)>0;
  
  $result = '<table '.$params['tbloptions'].'>';
  $result .= "<tbody>\n";
  if (count($headers)>0){
    $result .= '<tr>';
    foreach ($headers as $val){
      $result .= '<th>'.$val.'</th>';
    }
    $result .= '</tr>';
  }
  
  foreach ($data as $row){
    $result .= '<tr>';
    if ($fields_set) {
      foreach ($fields as $val){
        $result .= '<td>'.htmlentities($row[$val]).'</td>';   
      }
    } else {
      foreach ($row as $val){
        $result .= '<td>'.htmlentities($val).'</td>';   
      }
    }
    $result .= '</tr>'."\n";
  }
  
  $result .= "</tbody>\n";
  $result .= "</table>\n";
  return $result;  
}