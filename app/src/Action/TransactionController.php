<?php

namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Query\Builder;
use Slim\Http\Request;
use Slim\Http\Response;

class TransactionController
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
        $data = $this->table->get();

        if($data)
            return $response->withJson($this->resultData($data, "Semua transaksi"), 200);
        
        return $response->withJson($this->resultData($data, "Tidak ada transaksi"), 200);
    }

    public function getById(Request $request, Response $response, $args)
    {
        $id = $args["id"];
        
        $data = $this->table->find($id);
        
        if($data)
            return $response->withJson($this->resultData($data, "Transaksi id"), 200);
        
        return $response->withJson($this->resultData(null, "Transaksi tidak ditemukan"), 200);
    }

    public function getByUser(Request $request, Response $response, $args)
    {
        $id_user = $args["id"];
        
        $data = $this->table
                ->where('id_user', $id_user)
                ->get();
        
        if($data)
            return $response->withJson($this->resultData($data, "Transaksi user id : $id_user"), 200);
        
        return $response->withJson($this->resultData(null, "Transaksi tidak ditemukan"), 200);
    }

    public function search(Request $request, Response $response)
    {
        $keyword = $request->getQueryParam("keyword");
        
        $data = $this->table
                ->where('date', 'like', $keyword)
                ->get();

        if($data)
            return $response->withJson($this->resultData($data, "Daftar akun"), 200);
        
        return $response->withJson($this->resultData($data, "Akun tidak ditemukan"), 200);
    }

    public function add(Request $request, Response $response){

        $dataIn = $request->getParsedBody();
        $id_user = $dataIn["id_user"];
        $total = $dataIn["total"];
        $trans_hash = $dataIn["trans_hash"];
        $esd = $dataIn["esd"];

        if (empty($id_user) || empty($total) || empty($esd) || empty($trans_hash) ) {
            return $response->withJson($this->resultData(null, "Form kosong"));
        } else {
            $data = [
                'id_user' => $id_user, 
                'total' => $total,
                'trans_hash' => $trans_hash,
                'esd' => $esd
            ];

            $result = $this->table->insert($data);
                        
            if($result) {
                return $response->withJson($this->resultData(1, "Transaksi sukses"));
            }

            return $response->withJson($this->resultData(null, "Eksekusi error"));
        }
    }
}