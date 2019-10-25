<?php

namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Query\Builder;
use Slim\Http\Request;
use Slim\Http\Response;

class CartController
{
    private $view;
    private $logger;
    protected $table;

    public function resultData($data, $message)
    {
        if($data){
            $result = array(
                "status" => true,
                "message" => $message,
                "data" => $data,
            );
        } else {
            $result = array(
                "status" => false,
                "message" => $message,
                "data" => null,
            );
        }
        return $result;
    }

    public function __construct(
        Twig $view,
        LoggerInterface $logger,
        Builder $table
    ) {
        $this->view = $view;
        $this->logger = $logger;
        $this->table = $table;
    }

    public function __invoke(Request $request, Response $response, $args){}

    public function getAll(Request $request, Response $response){

        $data = $this->table
                ->join('products', 'cart.id_product', '=', 'products.id')
                ->select('cart.*', 'products.name as name', 'products.price as price', 'products.photo as photo')
                ->get();

        if($data)
            return $response->withJson($this->resultData($data, "Semua produk"), 200);
        
        return $response->withJson($this->resultData($data, "Tidak ada produk"), 200);
    }

    public function getByUser(Request $request, Response $response, $args){
        $id = $args["id"];

        $data = $this->table
                ->join('products', 'cart.id_product', '=', 'products.id')
                ->select('cart.*', 'products.name as name', 'products.price as price', 'products.photo as photo')
                ->where([
                    ['cart.id_user', '=', $id],
                    ['cart.status', '=', '1']
                ])
                ->orderBy('cart.id', 'asc')
                ->get();
        
        if($data)
            return $response->withJson($this->resultData($data, "Isi cart"), 200);
            
        return $response->withJson($this->resultData(null, "Tidak ada cart"), 200);
    }

    public function search(Request $request, Response $response)
    {
        $keyword = $request->getQueryParam("keyword");
        
        $data = $this->table
                ->join('category as c', 'products.id_category', '=', 'c.id')
                ->select('products.*', 'c.name as cat_name')
                ->where('products.name', 'like', $keyword)
                ->get();

        if($data)
            return $response->withJson($this->resultData($data, "Daftar akun"), 200);
        
        return $response->withJson($this->resultData($data, "Akun tidak ditemukan"), 200);
    }

    public function add(Request $request, Response $response){

        $dataIn = $request->getParsedBody();
        $id_user = $dataIn["id_user"];
        $id_product = $dataIn["id_product"];
        $much = $dataIn["much"];
        $total = $dataIn["total"];
        
        if (empty($id_user) || empty($id_product) || empty($much)) {
            return $response->withJson($this->resultData(null, "Form kosong"));
        
        } else {
            $data = [
                'id_user' => $id_user, 
                'id_product' => $id_product,
                'much' => $much,
                'total' => $total,
                'status' => 1
            ];

            $result = $this->table->insert($data);
                        
            if($result) {
                return $response->withJson($this->resultData(1, "Tambah cart sukses"));
            }

            return $response->withJson($this->resultData(null, "Eksekusi error"));
        }
    }

    public function delete(Request $request, Response $response, $args)
    {
        $id = $args["id"];
        
        $data = $this->table->where('id', $id)->delete();
        
        if($data)
            return $response->withJson($this->resultData($data, "Berhasil menghapus produk"));
        
        return $response->withJson($this->resultData(null, "Produk tidak ditemukan"));
    }

    public function edit(Request $request, Response $response, $args)
    {
        $id = $args["id"];
        
        $data = $this->table
                ->where('id', $id)
                ->update(['status' => 0]);
        
        if($data)
            return $response->withJson($this->resultData($data, "Berhasil membeli produk"));
        
        return $response->withJson($this->resultData(null, "Maaf ... "));
    }
}