<?php

namespace app\controllers;

use core\Controller;
use core\View;

class ProductController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('America/Sao_Paulo');
    }

    public function index()
    {
        $model = $this->load->model('ProductModel');
        $products = $model->getAll();
        $data = [
            'products' => $products
        ];
        View::render('home/index', $data);
    }

    public function create()
    {
        $model = $this->load->model('ProductModel');

        $post = $_POST;
        if (empty($post['nome']) || empty($post['descricao']) || empty($post['preco'])) {
            echo "Todos os campos são obrigatórios.";
            return;
        }

        $params = [
            'nome' => $post['nome'],
            'descricao' => $post['descricao'],
            'preco' => (float) str_replace(',', '.', $post['preco'])
        ];

        $variacoes = $post['variacoes'] ?? [];

        foreach ($variacoes as &$var) {
            $var['nome'] = trim($var['nome']);
            $var['preco_adicional'] = isset($var['preco_adicional']) ? (float) str_replace(',', '.', $var['preco_adicional']) : 0.0;
            $var['estoque'] = isset($var['estoque']) ? (int) $var['estoque'] : 0;
        }
        try {
            $id = $model->create($params);
            $model->saveVariacoes($id, $variacoes);

            return $this->success([
                'id' => $id
            ]);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }
    public function update($params)
    {
        $id = $params['id'];
        $model = $this->load->model('ProductModel');

        $post = $_POST;

        if (empty($post['nome']) || empty($post['descricao']) || empty($post['preco']) || empty($id)) {
            echo "Todos os campos são obrigatórios.";
            return;
        }

        $paramsToUpdate = [
            'nome' => $post['nome'],
            'descricao' => $post['descricao'],
            'preco' => (float) str_replace(',', '.', $post['preco']),
        ];

        $variacoes = $post['variacoes'] ?? [];

        foreach ($variacoes as &$var) {
            $var['nome'] = trim($var['nome']);
            $var['preco_adicional'] = isset($var['preco_adicional']) ? (float) str_replace(',', '.', $var['preco_adicional']) : 0.0;
            $var['estoque'] = isset($var['estoque']) ? (int) $var['estoque'] : 0;
        }

        try {
            $result = $model->update($id, $paramsToUpdate);
            $model->saveVariacoes($id, $variacoes);

            return $this->success();
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }
}
