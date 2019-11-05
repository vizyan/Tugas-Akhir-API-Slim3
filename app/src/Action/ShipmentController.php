<?php

namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Query\Builder;
use Slim\Http\Request;
use Slim\Http\Response;

class ShipmentController
{

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

    public function __construct() {}

    public function __invoke(Request $request, Response $response, $args){}

    public function getAll(Request $request, Response $response){
        // $data = $this->table->get();

        $datab = $this->table
                ->join('category', 'products.id_category', '=', 'category.id')
                ->select('products.*', 'category.name as cat_name')
                ->get();

        if($datab)
            return $response->withJson($this->resultData($datab, "Semua produk"), 200);
        
        return $response->withJson($this->resultData($datab, "Tidak ada produk"), 200);
    }

    public function getById(Request $request, Response $response, $args)
    {
        $id = $args["id"];
        
        $data = $this->table
                ->join('category', 'products.id_category', '=', 'category.id')
                ->select('products.*', 'category.name as cat_name')
                ->where('products.id', $id)
                ->first();
        
        if($data)
            return $response->withJson($this->resultData($data, "Produk id"), 200);
        
        return $response->withJson($this->resultData(null, "Produk tidak ditemukan"), 200);
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
        $name = $dataIn["email"];
        $price = $dataIn["name"];
        $stock = $dataIn["password"];
        $stellarId = $dataIn["stellarId"];
        $secretSeed = $dataIn["secretSeed"];
        // $token = $dataIn["token"];
        // $address = $dataIn["address"];
        // $phone = $dataIn["phone"];
        $passwordEncrypt = password_hash($password, PASSWORD_DEFAULT);
        
        if (empty($email) || empty($name) || empty($stellarId) || empty($secretSeed) || empty($password) ) {
            return $response->withJson($this->resultData(null, "Form kosong"));
        
        } else {
            $data = $this->table->where('email', $email)->exists();
            if($data){
                return $response->withJson($this->resultData($data, "Email telah terdaftar"));
            
            } else {

                $datab = [
                    'email' => $email, 
                    'name' => $name,
                    'stellarId' => $stellarId,
                    'secretSeed' => $secretSeed,
                    // 'token' => $token,
                    // 'address' => $address,
                    // 'phone' => $phone,
                    'password' => $passwordEncrypt
                ];

                $newData = $this->table->insert($datab);
                        
                if($newData) {
                    return $response->withJson($this->resultData($datab, "Registrasi sukses"));
                }

                return $response->withJson($this->resultData(null, "Eksekusi error"));
            }
        }
    }

    public function edit(Request $request, Response $response, $args){

        $id = $args["id"];
        $dataIn = $request->getParsedBody();
        $email = $dataIn["email"];
        $name = $dataIn["name"];
        $password = $dataIn["password"];
        $stellarId = $dataIn["stellarId"];
        $secretSeed = $dataIn["secretSeed"];
        // $token = $dataIn["token"];
        // $address = $dataIn["address"];
        // $phone = $dataIn["phone"];
        $passwordEncrypt = password_hash($password, PASSWORD_DEFAULT);
        
        if (empty($email) || empty($name) || empty($stellarId) || empty($secretSeed) || empty($password) ) {
            return $response->withJson($this->resultData(null, "Form kosong"));
        
        } else {
            $data = $this->table->where('email', $email)->exists();
            if($data){
                return $response->withJson($this->resultData($data, "Email telah terdaftar"));
            
            } else {

                $datab = [
                    'email' => $email, 
                    'name' => $name,
                    'stellarId' => $stellarId,
                    'secretSeed' => $secretSeed,
                    // 'token' => $token,
                    // 'address' => $address,
                    // 'phone' => $phone,
                    'password' => $passwordEncrypt
                ];

                $newData = $this->table->where('id', $id)->updateOrInsert($datab);
                        
                if($newData) {
                    return $response->withJson($this->resultData($datab, "Update sukses"));
                }

                return $response->withJson($this->resultData($newData, "Eksekusi error"));
            }
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
}