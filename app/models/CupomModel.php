<?php

namespace app\models;

use core\Model;

class CupomModel extends Model
{
    public function getByCodigo($codigo)
    {
        $stmt = $this->db->prepare("SELECT * FROM cupons WHERE codigo = ? AND validade >= CURDATE()");
        $stmt->execute([$codigo]);
        return $stmt->fetch();
    }

    public function getAllValidos()
    {
        $stmt = $this->db->query("SELECT * FROM cupons WHERE validade >= CURDATE()");
        return $stmt->fetchAll();
    }
}
