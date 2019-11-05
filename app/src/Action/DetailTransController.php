<?php

namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Query\Builder;
use Slim\Http\Request;
use Slim\Http\Response;

class DetailTransController
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
                ->join('cart', 'detail_transactions.id_cart', '=', 'cart.id')
                ->join('transactions', 'detail_transactions.id_trans', '=', 'transactions.id')
                ->select('detail_transactions.*', 'transactions.id_user as id_user', 'cart.id_product as id_product', 'cart.much as much', 'cart.total as cost', 'transactions.total as total', 'transactions.date as date', 'transactions.esd as esd')
                ->get();

        if($data)
            return $response->withJson($this->resultData($data, "Semua transaksi"), 200);
        
        return $response->withJson($this->resultData($data, "Tidak ada transaksi"), 200);
    }

    public function getByIdTrans(Request $request, Response $response, $args)
    {
        $id = $args["id"];
        
        $data = $this->table
                ->join('cart', 'detail_transactions.id_cart', '=', 'cart.id')
                ->join('transactions', 'detail_transactions.id_trans', '=', 'transactions.id')
                ->select('detail_transactions.*', 'transactions.id_user as id_user', 'cart.id_product as id_product', 'cart.much as much', 'cart.total as cost', 'transactions.total as total', 'transactions.date as date', 'transactions.esd as esd')
                ->where('id_trans', '=', $id)
                ->get();

        if($data)
            return $response->withJson($this->resultData($data, "Transaksi id : $id"), 200);
        
        return $response->withJson($this->resultData(null, "Transaksi tidak ditemukan"), 200);
    }

    public function add(Request $request, Response $response){

        $dataIn = $request->getParsedBody();
        $id_cart = $dataIn["id_cart"];
        $id_trans = $dataIn["id_trans"];
        
        if (empty($id_cart) || empty($id_trans) ) {
            return $response->withJson($this->resultData(null, "Form kosong"));
        } else {
            $data = [
                'email' => $email, 
                'name' => $name,
            ];

            $result = $this->table->insert($data);
                        
            if($result) {
                return $response->withJson($this->resultData($data, "Registrasi sukses"));
            }

            return $response->withJson($this->resultData(null, "Eksekusi error"));
        }
    }
}