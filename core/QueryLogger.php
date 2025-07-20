<?php
namespace core;

class QueryLogger {
    private static function getLogFile(): string {
        $date = date('Y-m-d'); // Obtém a data atual no formato YYYY-MM-DD
        return __DIR__ . "/../logs/queries-$date.log";
    }

    public static function logQuery($query, $params = [], $executionTime = null, $context = [], $level = 'DEBUG') {
        $date = date('Y-m-d H:i:s');
        $logMessage = "[$date] [$level] QUERY: $query";

        // Se houver parâmetros, adicionamos ao log
        if (!empty($params)) {
            $logMessage .= " | PARAMS: " . json_encode($params);
        }

        // Adiciona o tempo de execução se disponível
        if ($executionTime !== null) {
            $logMessage .= " | EXEC TIME: " . $executionTime;
        }

        $logMessage .= " | CONTEXT: " . json_encode($context);

        $logMessage .= PHP_EOL;

        $logFile = self::getLogFile();

        // Garante que o diretório de logs existe
        if (!is_dir(dirname($logFile))) {
            mkdir(dirname($logFile), 0777, true);
        }

        // Escreve a query no arquivo de log
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}
