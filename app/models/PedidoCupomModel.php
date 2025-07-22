<?php

namespace app\models;

use core\Model;

class PedidoCupomModel extends Model
{
    public function adicionarCupom($data)
    {
        return $this->db->set($data)
            ->insert("pedido_cupons");
    }
}
