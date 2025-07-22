<?php

namespace app\models;

use core\Model;

class PedidoItemModel extends Model
{
    public function addItem($data)
    {
        return $this->db->set($data)
            ->insert("pedido_itens");
    }
}
