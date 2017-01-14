<?php

//
// config
// --------------------------------------------------
//

  // mailchimp api key - http://admin.mailchimp.com/account/api/
  $apikey = '1234512345-us10';

  // mailchimp list id - http://admin.mailchimp.com/lists/
  $id     = 'abcde12345';

//
// script
// --------------------------------------------------
//

  $double_optin      = true;
  $send_welcome      = false;
  $update_existing   = false;
  $replace_interests = true;
  $email_type        = 'html';

  $email = $_POST['email'];
  $fname = isset( $_POST['fname'] ) ? $_POST['fname'] : '';
  $lname = isset( $_POST['lname'] ) ? $_POST['lname'] : '';

  if( isset( $email ) AND $email != '' ) {
    $merge_vars = array();

    if( $fname != '' ) { $merge_vars['FNAME'] = $fname; }

    if( $lname != '' ) { $merge_vars['LNAME'] = $lname; }

    $data = array(
      'email_address'     => $email,
      'apikey'            => $apikey,
      'id'                => $id,
      'double_optin'      => $double_optin,
      'send_welcome'      => $send_welcome,
      'email_type'        => $email_type,
      'replace_interests' => $replace_interests,
      'update_existing'   => $update_existing,
      'merge_vars'        => $merge_vars
    );

    $payload = json_encode($data);

    $datacenter = explode( '-', $apikey );
    $submit_url = "http://" . $datacenter[1] . ".api.mailchimp.com/1.3/?method=listSubscribe";

    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $submit_url );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_POST, true );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, urlencode( $payload ) );

    $result = curl_exec( $ch );
    curl_close ( $ch );
    $data = json_decode( $result );

    if ( $data->error ) {
      if ( $data->code == 104 ) {
        $msg = 'Invalid MailChimp API key.';
      } else {
        $msg = $data->code .' : '.$data->error;
      }

      die(json_encode(array('type' => 'error', 'msg' => $msg)));
    } else {
      die(json_encode(array('type' => 'success', 'msg' => 'Confirmation email has been successfully sent to your email address')));
    }
  }

?>
