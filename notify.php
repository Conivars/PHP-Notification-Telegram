<?php
class Notify
{
 private $ip; // Переменная ip
 private $country; // Переменная страны
 private $city; // Переменная города
 private $comment; // Переменная комментария
 public function __construct($com) {
$ip = $_SERVER['REMOTE_ADDR']; // Получаем ip посетителя
    $query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip.'?lang=ru')); // Собираем информацию о посетителе используя его IP
    if($query && $query['status'] == 'success') // Если успешно собрали информацию производим заполнение строк
 {
   $this->ip= $ip; // добавляем ip в переменную
   $this->country= $query['country']; // добавляем Страну в переменную
   $this->city= $query['city']; // добавляем Город в переменную
   $this->comment = $com; // добавляем Комментарий в переменную
        } 
else // Если не удалось получить данные об посетителе
{
$this->ip=$ip; // Добавляем ip в переменную
$this->country = 'None'; // добавляем текст None в переменную
$this->city = 'None'; // добавляем текст None в переменную
$this->comment = $com; // добавляем комментарий в переменную
    }
 }

function Send($token,$chat_id) // Функция отправки оповещения в телеграм
{
$info = 'IP - '.$this->ip.' | Country - '.$this->country.' | City - '.$this->city .' | '.$this->comment; // Собираем текст для отправки и заполняем его переменными 
$ch=curl_init(); // Создаем новый Curl
curl_setopt($ch, CURLOPT_URL,
       'https://api.telegram.org/bot'.$token.'/sendMessage'); // Выставляем ссылку с нашим токеном бота
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS,
       'chat_id='.$chat_id.'&text='.urlencode($info)); // Добавляем параметры для нашей ссылки
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_exec($ch); // Отправляем полученные данные
curl_close($ch); // Закрываем соединение
}
}
?>