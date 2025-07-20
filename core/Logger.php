<?php
namespace core;

class Logger {
    private static function getLogFile(): string {
        $date = date('Y-m-d');
        return __DIR__ . "/../logs/app-$date.log";
    }

    public static function log($message, $level = 'INFO') {
        $date = date('Y-m-d H:i:s');
        $logMessage = "[$date] [$level]: $message" . PHP_EOL;

        $logFile = self::getLogFile();

        if (!is_dir(dirname($logFile))) {
            mkdir(dirname($logFile), 0777, true);
        }

        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}
