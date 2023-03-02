<?php

class Product extends BaseModel
{
    public string $name;
    public float $price;
    public string $productType;
    public array $productAttributes;
    public string $sku;
    public function findall()
    {
        $sql = "SELECT * FROM products";
        $products = $this->db->query($sql)->fetchAll();
        for ($i = 0; $i < count($products); $i++) {
            $products[$i]['product_attributes'] = json_decode($products[$i]['product_attributes']);
        }
        return $products;
    }
    public function add()
    {
        try {
            $skuChecker = $this->checkSku($this->sku);
            if ($skuChecker == false)
                return false;
            $newEntry = [
                "name" => $this->name ? $this->name : null,
                "price" => $this->price ? $this->price : 0,
                "product_type" => $this->productType ? $this->productType : null,
                "product_attributes" => $this->productAttributes ? json_encode($this->productAttributes) : null,
                "sku" => $this->sku ? $this->sku : null
            ];
            $columnsStr = implode(',', array_keys($newEntry));
            $namedParams = implode(',', array_map(function ($val) {
                return ":$val"; }, array_keys($newEntry)));
            $sql = "INSERT INTO products ($columnsStr) VALUES ($namedParams)";
            $this->db->prepare($sql)->execute($newEntry);
            return true;
        } catch (ErrorException $e) {
            return false;
        }
    }
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM products WHERE id=:id";
            $this->db->prepare($sql)->execute(['id' => $id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    public function massDelete(array $ids)
    {
        try {
            foreach ($ids as $id) {
                $this->delete($id);
            }
        } catch (PDOException $e) {
            return false;
        }
    }
    public function lastId()
    {
        try {
            $sql = "SELECT id FROM products ORDER BY id DESC LIMIT 1";
            $lastId = $this->db->query($sql)->fetch()['id'];
            return $lastId ? $lastId : 0;
        } catch (PDOException $e) {
            return 0;
        }
    }
    public function checkSku($sku)
    {
        try {
            $sql = "SELECT * FROM products WHERE sku = '$sku'";
            $result = $this->db->query($sql)->fetchAll();
            if (count($result) == 0)
                return true;
            else
                return false;
        } catch (PDOException $e) {
            throw new ErrorException($e->getMessage());
        }
    }
}