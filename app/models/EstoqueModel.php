<?php

namespace app\models;

use core\Model;

class EstoqueModel extends Model
{
    public function getByProduto($produtoId)
    {
        $query = $this->db->from("estoque")
            ->where("produto_id", '=', $produtoId);

        return $query->get();
    }

    public function getByVariacao($produtoId, $variacaoId)
    {
        $query = $this->db->from("estoque")
            ->where("produto_id", '=', $produtoId)
            ->where("variacao_id", '=', $variacaoId);

        return $query->get();
    }

    public function updateQuantidade($id, $data)
    {
        return $this->db->set($data)
            ->where("id", "=", $id)
            ->update("estoque");
    }

    public function create($data)
    {
        $this->db->set($data)
            ->insert("estoque");

        return $this->db->last_id();
    }
}
