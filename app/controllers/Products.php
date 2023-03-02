<?php

class Products extends BaseController
{

    public function index(...$params)
    {
        $product = $this->model('Product');
        $products = $product->findall();
        $productsJson = json_encode($products);
        $this->view('products/list', ["products" => $productsJson]);
    }
    public function delete(...$params) 
    {
        $response = [];
        try {
            $data = $_POST;
            $productsIds = explode(',', $data['ids']);
            $this->model('Product')->massDelete($productsIds);
            $response = ["success" => true];
        } catch (Error $e) {
            $response = ["success" => false];
        }
        echo json_encode($response);

    }

}