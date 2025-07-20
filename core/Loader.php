<?php

namespace core;

use app\helper\SessionManager;
use app\libraries\Modules;

class Loader
{

    /**
     * Carrega um model.
     * 
     * @param string $model Nome do arquivo do model a ser carregado (sem a extensão .php)
     * @return object Instância do model carregado
     */
    public function model($model)
    {
        $modelClass = "app\\models\\$model";

        if (class_exists($modelClass)) {
            return new $modelClass();
        } else {
            echo "Erro: O model '$model' não foi encontrado!";
            return null;
        }
    }




    /**
     * Carrega uma biblioteca (como o banco de dados ou outras bibliotecas customizadas).
     * 
     * @param string $library Nome da biblioteca a ser carregada
     * @return object Instância da biblioteca carregada
     */
    public function library($library)
    {
        $libraryClass = "app\\libraries\\$library";

        if (class_exists($libraryClass)) {
            return new $libraryClass();
        } else {
            echo "Erro: A biblioteca '$libraryClass' não foi encontrada!";
            return null;
        }
    }
}
