<?php

function is_login()
{
   $ci = get_instance();
   if($ci->session->userdata('id')){
      $url = $ci->uri->segment(1);

      if($url == 'depo'){
            $url = $url . "/" .$ci->uri->segment(2);
      }

      $menu = $ci->session->userdata('menu');

      if(!in_array($url, $menu)){
            $ci->session->set_flashdata('gagal', 'Akses Ditolak');
            redirect($_SERVER['HTTP_REFERER']);
      }
   } else {
      $ci->session->set_flashdata('info', 'Session Anda Telah Berakhir. SIlahkan Login Kembali');
      redirect('auth');
   }
}

function qr_code($json)
{  
   $ci = get_instance();
   $ci->load->library('ciqrcode'); //pemanggilan library QR CODE
 
   $config['cacheable']    = true; //boolean, the default is true
   $config['cachedir']     = './assets/'; //string, the default is application/cache/
   $config['errorlog']     = './assets/'; //string, the default is application/logs/
   $config['imagedir']     = './assets/qr_code/'; //direktori penyimpanan qr code
   $config['quality']      = true; //boolean, the default is true
   $config['size']         = '1024'; //interger, the default is 1024
   $config['black']        = array(224,255,255); // array, default is array(255,255,255)
   $config['white']        = array(70,130,180); // array, default is array(0,0,0)
   $ci->ciqrcode->initialize($config);

   $image_name=$json['qr_code'].'.png'; //buat name dari qr code sesuai dengan nim

   $params['data'] = json_encode($json); //data yang akan di jadikan QR CODE
   $params['level'] = 'H'; //H=High
   $params['size'] = 10;
   $params['savename'] = FCPATH.$config['imagedir'].$image_name; //simpan image QR CODE ke folder assets/images/
   $ci->ciqrcode->generate($params); // fungsi untuk generate QR CODE
}

function send_mail($data)
{
   $ci = get_instance();

   $config = [
         'protocol' => 'smtp',
         'smtp_host' => 'ssl://smtp.googlemail.com',
         'smtp_user' => 'info.siulapp@gmail.com',
         'smtp_pass' => '7Su*35Oq)nYUf8',
         'smtp_port' => 465,
         'mailtype' => 'html',
         'charset' => 'utf-8',
         'newline' => "\r\n"
   ];

   $ci->email->initialize($config);
   $ci->load->library('email', $config);

   $ci->email->from('info.siulapp@gmail.com', 'Solusi Isi Ulang - SIUL');
   $ci->email->to($data['email']);
   $subject = $data['subjek'];
   $ci->email->subject($subject);

   $body = $ci->load->view($data['template'],$data,TRUE);
   $ci->email->message($body);
   $ci->email->send();
}

function send_wa($no_hp, $otp)
{
   $ch = curl_init();
   $data = [
      'phone' => "62$no_hp",
      'messageType' => 'text',
      'body' => "OTP anda adalah $otp\nHarap masukkan otp sebelum 5 menit\nJangan bagikan otp anda kepihak manapun."
   ];

   $headers = [
      'API-Key: 173b4217b8c6cdd10c7df08fe091f818f4abcdff0d3ed3d0c364e63779eedc92',
      'Content-Type:  application/json'
   ];

          
   curl_setopt($ch, CURLOPT_URL,"https://sendtalk-api.taptalk.io/api/v1/message/send_whatsapp");
   curl_setopt($ch, CURLOPT_POST, 1);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));           
   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
   $result     = curl_exec ($ch);
   $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
}

?>