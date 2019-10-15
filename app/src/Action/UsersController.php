<?php
namespace App\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use Interop\Container\ContainerInterface;

final class UsersController
{
    private $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, $args){

    }

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

    public function getAllUsers(Request $request, Response $response, $args)
    {
        $sql = "SELECT * FROM users";
        $stmt = $this->container->db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();

        if($data)
            return $response->withJson($this->resultData($data, "Semua akun"));

        return $response->withJson($this->resultData(null, "Akun tidak ada"));
    }

    public function getUserById(Request $request, Response $response, $args)
    {
        $id = $args["id"];
        $sql = "SELECT * FROM users WHERE id=:id";
        $stmt = $this->container->db->prepare($sql);
        $stmt->execute([":id" => $id]);
        $data = $stmt->fetch();
        
        if($data)
            return $response->withJson($this->resultData($data, "Akun id : $id"));
        
        return $response->withJson($this->resultData(null, "Akun tidak ditemukan"));
    }

    public function searchUsers(Request $request, Response $response)
    {
        $keyword = $request->getQueryParam("keyword");
        
        $sql = "SELECT * FROM users WHERE email LIKE '%$keyword%' OR name LIKE '%$keyword%' OR stellarId LIKE '%$keyword%'";
        $stmt = $this->container->db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();

        if($data)
            return $response->withJson($this->resultData($data, "Cari user keyword : $keyword"));
        
        return $response->withJson($this->resultData(null, "Akun tidak ditemukan"));
    }

    public function addUser(Request $request, Response $response){

        $dataIn = $request->getParsedBody();
        $email = $dataIn["email"];
        $name = $dataIn["name"];
        $stellarId = $dataIn["stellarId"];
        $secretSeed = $dataIn["secretSeed"];
        // $token = $dataIn["token"];
        $password = $dataIn["password"];
        $passwordEncrypt = password_hash($password, PASSWORD_DEFAULT);
        
        if (empty($email) || empty($name) || empty($stellarId) || empty($secretSeed) || empty($password) ) {
            return $response->withJson($this->resultData(null, "Form kosong"));

        } else {
            $sql1 = "SELECT * FROM users WHERE email=:email";
            $stmt = $this->container->db->prepare($sql1);
            $stmt->execute([":email" => $email]);
            $data = $stmt->fetch();

            if($data){
                return $response->withJson($this->resultData($data, "Email telah terdaftar"));

            } else {
                $sql = "INSERT INTO users (email, name, stellarId, secretSeed, password) VALUE (:email, :name, :stellarId, :secretSeed, :pass)";
                $stmt = $this->container->db->prepare($sql);

                $datab = [
                    ":email" => $email,
                    ":name" => $name,
                    ":stellarId" => $stellarId,
                    ":secretSeed" => $secretSeed,
                    ":pass" => $passwordEncrypt
                ];
        
                if($stmt->execute($datab)) {
                    return $response->withJson($this->resultData($datab, "Registrasi sukses"));
                }

                return $response->withJson($this->resultData(null, "Eksekusi error"));
            }
        }
    }

    public function loginUser(Request $request, Response $response, $args)
    {
        $dataIn = $request->getParsedBody();
        $email = $dataIn["email"];
        $password = $dataIn["password"];

        $sql = "SELECT * FROM users WHERE email=:email";
        $stmt = $this->container->db->prepare($sql);
        $stmt->execute([":email" => $email]);
        $data = $stmt->fetch();

        if($data){
            $passwordHash = $data['password'];
            if(password_verify($password, $passwordHash)){
                return $response->withJson($this->resultData($data, "Login sukses"));
            } else {
                return $response->withJson($this->resultData(null, "Password salah"));
            }
        } else {
            return $response->withJson($this->resultData(null, "Email belum terdaftar"));
        }
    }

    public function editUsers(Request $request, Response $response, $args)
    {
        $id = $args["id"];
        $new_book = $request->getParsedBody();
        $email = $new_book["email"];
        $name = $new_book["name"];
        $token = $new_book["token"];
        $photo = $new_book["photo"];
        $password = $new_book["password"];
        $passwordEncrypt = password_hash($password, PASSWORD_DEFAULT);

        $sql = "SELECT * FROM users WHERE email=:email";
        $stmt = $this->container->db->prepare($sql);
        $stmt->execute([":email" => $email]);

        if($stmt->fetch()){
            return $response->withJson($this->resultData(null, "Email telah terpakai"));
        } else {
            $sql1 = "UPDATE users SET email=:email, name=:name, token=:token, password=:password, photo=:photo WHERE id=:id";
            $stmt = $this->container->db->prepare($sql1);

            $data = [
                ":id" => $id,
                ":email" => $email,
                ":name" => $name,
                ":token" => $token,
                ":password" => $passwordEncrypt,
                ":photo" => $photo
            ];
    
            if($stmt->execute($data))
                return $response->withJson($this->resultData($data, "Berhasil mengedit akun"));
        
            return $response->withJson($this->resultData(null, "Eksekusi error"));
        }
    }

    public function deleteUsers(Request $request, Response $response, $args)
    {
        $id = $args["id"];
        $sql = "DELETE FROM users WHERE id=:id";
        $stmt = $this->container->db->prepare($sql);
            
        $data = [
            ":id" => $id
        ];
        
        if($stmt->execute($data))
            return $response->withJson($this->resultData($data, "Berhasil menghapus user"));
        
        return $response->withJson($this->resultData(null, "Eksekusi error"));
    }
}
