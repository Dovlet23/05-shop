<?php

namespace App\Logging\Telegram;

use Monolog\Logger;

class TelegramLoggerFactory
{
    public function __invoke(array $config): Logger
    {
        $logger = new logger('telegram');
        $logger->pushHandler(new TelegramLoggerHandler($config));

        return $logger;
    }
}
