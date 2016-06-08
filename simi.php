<!--
@Author: Dias Taufik Rahman
@Date:   2016-06-08T13:46:37+07:00
@Last modified by:   mydisha
@Last modified time: 2016-06-08T14:01:37+07:00
-->

<?php

$telegram = 'API-KEY-DISINI'; //API Key untuk Bot Telegram
$simikey = 'API-KEY-DISINI'; //Api key untuk simi simi
$url_telegram = 'https://api.telegram.org/bot' . $telegram . '/getUpdates'; //URL Telegram

// Fungsi CURL

function exec_curl($url)
{
  $exec = curl_init();

  curl_setopt($exec, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
  curl_setopt($exec, CURLOPT_URL, $url);
  curl_setopt($exec,CURLOPT_RETURNTRANSFER,true);

  $out = curl_exec($exec);

  if (curl_errno($exec)) {
     print curl_error($exec);
  }

  curl_close($exec);

  return $out;
}

//Fungsi simpan log

function save($text) {
    fwrite(fopen('log.txt', "a+"), $text . PHP_EOL);
    fclose(fopen('log.txt', "a+"));
}
//Dapatkan response dari api telegram

$response = json_decode(exec_curl($url_telegram), true);

  //Jika response = OK
  if ($response['ok'] == 1)
  {
  foreach ($response['result'] as $d) {

    foreach ($d as $c)
    {
      // Simpan Chat, chat id, chat reply terakhir dari client kedalam variable $buff
      $buff = array('isichat' => $c['text'], 'chatid' => $c['chat']['id'], 'message_id' => $c['message_id']);
    }
  }

//Dapatkan response dari api simi simi dan memasukkan hasil chat dari client telegram
//Kedalam kueri
  $responsesimi = json_decode(exec_curl('http://sandbox.api.simsimi.com/request.p?key=' . $simikey . '&lc=id&ft=1.0&text=' . urlencode($buff['isichat'])), true);

  //Jika response sukses
  if($responsesimi['result'] == 100)
  {
    //Simpan hasil kedalam array
    $pesan = array (
    'chat_id'             => $buff['chatid'],
    'text'                => $responsesimi['response'],
    'reply_to_message_id' => $buff['message_id']
  );
    //Kirim chat balasan simi simi ke client berdasarkan id nya.
    $send = json_decode(exec_curl("https://api.telegram.org/bot" . $telegram . "/sendMessage?chat_id=". $pesan['chat_id'] . "&text=" . $pesan['text'] . "&reply_to_message_id=". $pesan['reply_to_message_id']), true);
    //Simpan ke log jika sukses
    save('sukses');
  } else {
    //Simpan log jika gagal
    save($response['description']);
  }
} else {
    //Simpan log jika gagal
    save($response['description']);
}
