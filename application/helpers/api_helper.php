<?php

function user_depo_key($user_depo=1, $api_key=1)
{
   $ci = get_instance();
   $db = $ci->db->get_where('user_depo', ['id'=>$user_depo, 'api_key'=>$api_key])->num_rows();

   if($db <= 0){
      $data = [
         'status' => FALSE,
         'code' => 404,
         'message' => 'Invalid Api Key'
      ];
      // $data = new stdClass();
      echo json_encode($data);
      exit();
   }
}
?>