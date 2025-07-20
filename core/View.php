<?php

namespace core;

class View
{
    protected static $sections = [];
    protected static $currentSection;

    public static function render(string $view, array $data = []): void
    {
        $file = "./app/views/{$view}.php";

        if (file_exists($file)) {
            extract($data);
            include $file;
        } else {
            echo "Erro: View '$view' não encontrada em $file";
        }
    }

    /**
     * Carrega um layout, padrão dentro do módulo atual, ou de outro módulo se informado
     */
    public static function extend(string $layout): void
    {
        $file = "./app/views/layout/{$layout}.php";


        if (file_exists($file)) {
            include $file;
        } else {
            echo "Erro: Layout '$layout' não encontrado em $file";
        }
    }

    public static function section(string $name): void
    {
        self::$currentSection = $name;
        ob_start();
    }

    public static function endSection(): void
    {
        self::$sections[self::$currentSection] = ob_get_clean();
    }

    public static function renderSection(string $name): void
    {
        echo self::$sections[$name] ?? '';
    }

    public static function include(string $path): void
    {
        $file = "./app/views/{$path}.php";


        if (file_exists($file)) {
            include $file;
        } else {
            echo "Erro: Include '$path' não encontrado em $file";
        }
    }
}
