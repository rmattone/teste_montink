<?php

namespace app\models;

use core\Model;

class PedidoModel extends Model
{
    public function create($data)
    {
        $this->db->set($data)
            ->insert("pedidos");

        return $this->db->last_id();
    }

    public function updateStatus($id, $status)
    {
        return $this->db->set("status", '=', $status)
            ->where("id", "=", $id)
            ->update("pedidos");
    }

    public function delete($id)
    {
        return $this->db->set("deleted_at", '=', date('Y-m-d H:i:s'))
            ->where("id", "=", $id)
            ->update("pedidos");
    }

    public function get($id)
    {
        $pedido = $this->db->from('pedidos')
            ->where("id", '=', $id)
            ->get()[0] ?? false;

        if (!$pedido) {
            return false;
        }

        $itens = $this->db->from('pedido_itens pi')
            ->join('produtos p', 'p.id = pi.produto_id')
            ->where("pi.pedido_id", '=', $pedido['id'])
            ->get();

        $pedido['itens'] = $itens;

        return $pedido;
    }
}
