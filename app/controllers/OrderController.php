<?php

namespace app\controllers;

use core\Controller;
use core\View;

class OrderController extends Controller
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
        View::render('orders/index', $data);
    }

    public function checkout()
    {
        View::render('orders/checkout');
    }


    public function create()
    {
        $post = $_POST;

        if (empty($post['name']) || empty($post['email']) || empty($post['cep']) || empty($post['cart'])) {
            return $this->error('Dados incompletos.');
        }

        $carrinho = json_decode($post['cart'], true);
        if (!is_array($carrinho) || count($carrinho) === 0) {
            return $this->error('Carrinho vazio.');
        }

        $subtotal = 0;
        foreach ($carrinho as $item) {
            $subtotal += $item['price'] * $item['quantidade'];
        }

        $frete = 20;
        if ($subtotal >= 52 && $subtotal <= 166.59) $frete = 15;
        if ($subtotal > 200) $frete = 0;

        $total = $subtotal + $frete;

        $model = $this->load->model('PedidoModel');
        $pedido_id = $model->create([
            'subtotal' => $subtotal,
            'frete' => $frete,
            'total' => $total,
            'nome_cliente' => $post['name'],
            'email_cliente' => $post['email'],
            'cep' => $post['cep'],
            'endereco' => $post['endereco'],
            'numero' => $post['numero'],
            'complemento' => $post['complemento'],
            'bairro' => $post['bairro'],
            'cidade' => $post['cidade'],
            'estado' => $post['estado']
        ]);

        $pedido_item_model = $this->load->model('PedidoItemModel');
        foreach ($carrinho as $item) {
            $pedido_item_model->addItem([
                'pedido_id' => $pedido_id,
                'produto_id' => $item['produto_id'],
                'variacao_id' => isset($item['variacao_id']) ? $item['variacao_id'] : null,
                'quantidade' => $item['quantidade'],
                'preco_unitario' => $item['price']
            ]);
        }
        return $this->success(['pedido_id' => $pedido_id]);
    }

    public function getOrder($params)
    {
        if(empty($params['id'])){
            View::render('orders/search_order');
            exit;
        }

        $model = $this->load->model('PedidoModel');
        $pedido = $model->get($params['id']);
        $data = [
            'pedido' => $pedido
        ];
        View::render('orders/order', $data);
    }
}
