<?php

namespace app\models;

use core\Model;

class VariacaoModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getByProduto($produtoId)
    {
        $query = $this->db->from("variacoes")
            ->where("produto_id", '=', $produtoId);

        return $query->get();
    }

    public function create($data)
    {
        $this->db->set($data)
            ->insert("variacoes");

        return $this->db->last_id();
    }
}
