<?php

namespace app\models;

use core\Model;

class ProductModel extends Model
{
    public function getAll()
    {
        $products = $this->db->from('produtos')->get();

        $variacoes = $this->db->from('variacoes')->get();

        $grouped = [];
        foreach ($variacoes as $v) {
            $grouped[$v['produto_id']][] = $v;
        }

        foreach ($products as &$product) {
            $product['variacoes'] = $grouped[$product['id']] ?? [];
        }

        return $products;
    }

    public function getById($id)
    {
        $query = $this->db->from("produtos")
            ->where("id", '=', $id);

        return $query->get();
    }

    public function create($data)
    {
        $this->db->set($data)
            ->insert("produtos");
        return $this->db->last_id();
    }

    public function update($id, $data)
    {
        return $this->db->set($data)
            ->where("id", "=", $id)
            ->update("produtos");
    }

    public function saveVariacoes(int $productId, array $variacoes): void
    {

        $existing = $this->db->from('variacoes')
            ->where('produto_id', '=', $productId)
            ->get();

        $existingIds = array_column($existing, 'id');
        $receivedIds = [];

        $this->db->beginTransaction();

        try {
            foreach ($variacoes as $var) {
                $data = [
                    'produto_id' => $productId,
                    'nome' => $var['nome'],
                    'preco' => $var['preco_adicional'],
                ];

                if (!empty($var['id']) && in_array($var['id'], $existingIds)) {
                    $receivedIds[] = $var['id'];
                    $this->db->set($data)
                        ->where('id', '=', $var['id'])
                        ->update('variacoes');
                } else {
                    $this->db->set($data)->insert('variacoes');
                }
            }

            $toDelete = array_diff($existingIds, $receivedIds);
            if (!empty($toDelete)) {
                $this->db->whereIn('id', $toDelete)->delete('variacoes');
            }

            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }
}
