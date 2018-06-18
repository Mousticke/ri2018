<?php
if($_POST){
    $data = [
    'email'     => $_POST['email'],
    'name'    => $_POST['name'],
    'infos'     => $_POST['infos'],
    'pack'      => $_POST['pack'],
    'company'      => $_POST['company'],
    'telephone'      => $_POST['telephone']
  ];

   $to      = 'akim.benchiha@gmail.com,ursula.salembere@ig2i.centralelille.com';
   $subject = 'New rencontres personne';
   $message = 'Une nouvelle personne s\'est inscrite' . $data['email'] . ' son nom:' . $data['name'] . ' phone: ' . $data['telephone'] . ' sa compagnie:' .
   $data['company'] . '.</br> Cette personne est interesse par un pack: ' . $data['pack'] . '.</br> Information
   complemantaire laissee par la personne : ' . $data['infos'] . '</br> On GERE SA MERE!';

   $headers = 'akim.benchiha@gmail.com' . "\r\n" .
   'Reply-To: akim.benchiha@gmail.com' . "\r\n" .
   'X-Mailer: PHP/' . phpversion();



  $mailchimp_response = syncMailchimp($data);
  $mail_response = mail($to, $subject, $message, $headers);
  echo $mailchimp_response;
  return true;
  // return json_encode(array(
  //   'mailchimp_response' => $mailchimp_response,
  //   'mail_sent' => $mail_response,
  // ));
}
  function syncMailchimp($data) {
    $apiKey = '02045807ee7bd2c7c7bff45063cce5a4-us18';
    $listId = '8ac0a73c20';

    $memberId = md5(strtolower($data['email']));
    $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
    $url = 'https://us18.api.mailchimp.com/3.0/lists/' . $listId . '/members';

    $json = json_encode([
        'email_address' => $data['email'],
        'status'        => "subscribed",
        'merge_fields'  => [
            'INFOS'     => $data['infos'],
            'LNAME'      => $data['name'],
            'PACK'      => $data['pack'],
        ]
    ]);

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $result;
  }

?>
