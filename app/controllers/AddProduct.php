<?php

class AddProduct extends BaseController
{

    public function index(...$params) // List all products

    {
        $productLastId = $this->model('Product')->lastId();
        $this->view('products/add', ["lastId" => $productLastId]);
    }
    public function save(...$params)
    {
        try {
            $data = $_POST;
            $product = $this->model('Product');
            $product->name = $data['name'];
            $product->price = $data['price'];
            $product->sku = $data['sku'];
            $product->productType = $data['product_type'];
            $productAttributes = [];
            foreach (array_keys($data) as $key) {
                if (strpos($key, $product->productType)!== FALSE) {
                    $productAttributes[str_replace($product->productType . '_', '', $key)] = $data[$key];
                }
            }
            $product->productAttributes = $productAttributes;
            $success = $product->add();
            if ($success) {
                $response = [
                    "success" => true,
                ];
            } else {
                $response = [
                    "success" => false,
                    "message" => "SKU is already present, Please write a different sku."
                ];
            }

            echo json_encode($response);
        } catch (ErrorException $e) {
            $response = ["success" => false];
            echo json_encode($response);
        }
        // print_r($_POST) ;
    }

}