<?php
/**
 * Debug: Логирование входящих запросов от Happ App
 * Сохраняет все HTTP-заголовки в requests.log
 */

// Путь к файлу логов
define('LOG_OUTPUT', __DIR__ . '/requests.log');

// Формируем данные для записи
$data = sprintf(
    "[%s]\n%s %s %s\n\nHTTP HEADERS:\n",
    date('c'),
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI'],
    $_SERVER['SERVER_PROTOCOL']
);

// Собираем все HTTP-заголовки
foreach ($_SERVER as $name => $value) {
    if (preg_match('/^HTTP_/',$name)) {
        // конвертируем HTTP_HEADER_NAME в Header-Name
        $name = strtr(substr($name,5),'_',' ');
        $name = ucwords(strtolower($name));
        $name = strtr($name,' ','-');
        
        // добавляем в список
        $data .= $name . ': ' . $value . "\n";
    }
}

$data .= "\nREQUEST BODY:\n" . file_get_contents('php://input') . "\n";

// Записываем в файл (с блокировкой)
file_put_contents(LOG_OUTPUT, $data, FILE_APPEND|LOCK_EX);

echo("OK!\n");
