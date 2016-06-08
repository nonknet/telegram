<!--
@Author: Dias Taufik Rahman <root>
@Date:   2016-06-08T16:19:22+07:00
@Email:  diastaufik@gmail.com
@Last modified by:   root
@Last modified time: 2016-06-09T04:49:22+07:00
-->

<?php
//TOKEN Telegram & URL API Telegram Bot
define('BOT_TOKEN', '218218826:AAHhuAaFT2OBBjHytsP1zVt5dnZpe1NSN_E');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

//TOKEN API Simi-Simi
define('SIMI_TOKEN', '0f922683-3829-4ac7-ad20-27900a96da29');

//TOKEN Muslimsalat.com & Url API Muslimsalat.com
define('MUSLIMSALAT_KEY', '4ee4ec12800e068b6f9ea3df598ef738');
define('MUSLIMSALAT_API', 'http://muslimsalat.com/jakarta.json?key='. MUSLIMSALAT_KEY);

//Membaca Pesan yang masuk dari client, chatID, chatContent, messageId
$content = file_get_contents("php://input");
$update = json_decode($content, true);
$chatID = $update["message"]["chat"]["id"];
$chatContent = $update["message"]["text"];
$messageId = $update["message"]["message_id"];

//Melakukan logging untuk memeriksa apakah konten yang diinginkan ada atau tidak
checkJSON($chatID,$update);

//Fungsi menyimpan string kedalam sebuah file dengan nama log.txt
function checkJSON($chatID,$update){

$myFile = "log.txt";
$updateArray = print_r($update,TRUE);
$fh = fopen($myFile, 'a') or die("can't open file");
fwrite($fh, $chatID ."\n\n");
fwrite($fh, $updateArray."\n\n");
fclose($fh);
}

//Perintah CURL
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

//Mengambil response dari API simi-simi
$responsesimi = json_decode(exec_curl('http://sandbox.api.simsimi.com/request.p?key=' . SIMI_TOKEN . '&lc=id&ft=1.0&text=' . urlencode($chatContent)), true);

//Cek apakah kita sudah sampai pada API yang ditentukan simi-simi
if ($responsesimi['msg'] == 'Daily Request Query Limit Exceeded.')
{
  //Kembalikan nilai jika API yang digunakan sudah di limit
  $chatsimi = 'Udahan ah capek mau bobo!';
} else
{
  //Mereplace string "simi" menjadi "Chitanda-chwan"
  $chatsimi = str_replace("simi", "Chitanda-chwan", $responsesimi['response']);
}

//Cek command yang dikirim dari client Telegram
if ($chatContent == '/tentangbot')
{
  //Kembalikan hasil ke client
  $reply = sendMessage('Bot Cerewet [BETA] dibuat dengan asal-asalan di kosan gracia');
  //Cek untuk command Jadwal Imsak
} elseif ($chatContent == '/jadwalimsak') {
  $jadwal = json_decode(exec_curl(MUSLIMSALAT_API), true);
  //Mengambil data jadwal imsak hari ini dari API MUSLIM SALAT
  foreach ($jadwal['items'] as $d) {
    $buff = $d['fajr'];
  };
  //Mengembalikan response yang didapat ke client Telegram
  $reply = sendMessage('Jadwal imsak hari ini nih mblo : '. $buff);
} else {
  //Jika tidak ada command, kembalikan hasil chat yang diambil dari simi-simi
  $reply =  sendMessage($chatsimi);
}

//Menyiapkan pesan yang ingin dikirimkan ke client
$sendto =API_URL."sendmessage?chat_id=".$chatID."&text=".$reply."&reply_to_message_id=".$messageId;
file_get_contents($sendto);

function sendMessage($isi){
//Fungsi untuk set isi pesan berdasarkan inputan parameter $isi
$message = $isi;
return $message;
}
