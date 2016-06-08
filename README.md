<!--
@Author: Dias Taufik Rahman <root>
@Date:   2016-06-08T13:53:06+07:00
@Email:  diastaufik@gmail.com
@Last modified by:   root
@Last modified time: 2016-06-09T04:57:42+07:00
-->



# php-simi-telegrambot
php-simi-telegrambot adalah aplikasi berbasis php yang digunakan sebagai bot untuk aplikasi chatting Telegram.
Respon dari bot ini diambil dari aplikasi simi-simi.

# Penggunaan

1. Dapatkan api key untuk simi-simi lewat url ini `http://developer.simsimi.com/api`
2. Buat bot telegram dan dapatkan apinya dengan cara search `@BotFather` pada telegram lalu ikuti instruksinya
3. Setup Webhook dengan perintah linux
`curl -F "url=https://domainkamu.com/script-bot.php" -F "certificate=@/lokasi/file/cert/cert_chain.crt" https://api.telegram.org/bot[TOKEN DISINI]/setWebhook`

# Fitur

1. Response chat dari simi-simi
2. Jadwal Imsak yang diambil dari `http://muslimsalat.com`
