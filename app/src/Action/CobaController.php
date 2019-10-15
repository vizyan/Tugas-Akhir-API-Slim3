<?php
namespace App\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use Interop\Container\ContainerInterface;

final class CobaController
{
    private $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, $args){

    }

    public function resultData($data)
    {
        if($data){
            $result = array(
                "status" => true,
                "message" => "success",
                "data" => $data,
            );
        } else {
            $result = array(
                "status" => false,
                "message" => "failed",
                "data" => $data,
            );
        }

        return $result;
    }

    public function getAllBooks(Request $request, Response $response, $args)
    {
        $sql = "SELECT * FROM books";
        $stmt = $this->container->db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();

        return $response->withJson($this->resultData($data));
    }

    public function getBookById(Request $request, Response $response, $args)
    {
        $id = $args["id"];
        $sql = "SELECT * FROM books WHERE book_id=:id";
        $stmt = $this->container->db->prepare($sql);
        $stmt->execute([":id" => $id]);
        $data = $stmt->fetch();
        
        return $response->withJson($this->resultData($data));
    }

    public function searchBooks(Request $request, Response $response)
    {
        $keyword = $request->getQueryParam("keyword");
        $sql = "SELECT * FROM books WHERE title LIKE '%$keyword%' OR sinopsis LIKE '%$keyword%' OR author LIKE '%$keyword%'";
        $stmt = $this->container->db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();

        return $response->withJson($this->resultData($data));
    }

    public function addBook(Request $request, Response $response){
        $new_book = $request->getParsedBody();
        
        $sql = "INSERT INTO books (title, author, sinopsis) VALUE (:title, :author, :sinopsis)";
        $stmt = $this->container->db->prepare($sql);
    
        $data = [
            ":title" => $new_book["title"],
            ":author" => $new_book["author"],
            ":sinopsis" => $new_book["sinopsis"]
        ];

        if($stmt->execute($data)) {
            return $response->withJson($this->resultData($data));
        }
    
        $result = array(
            "status" => false,
            "message" => "error execute",
            "data" => $data,
        );
        return $response->withJson($result, 500);
    }

    public function editBooks(Request $request, Response $response, $args)
    {
        $id = $args["id"];
        $new_book = $request->getParsedBody();
        $sql = "UPDATE books SET title=:title, author=:author, sinopsis=:sinopsis WHERE book_id=:id";
        $stmt = $this->container->db->prepare($sql);
        
        $data = [
            ":id" => $id,
            ":title" => $new_book["title"],
            ":author" => $new_book["author"],
            ":sinopsis" => $new_book["sinopsis"]
        ];
    
        if($stmt->execute($data))
            return $response->withJson($this->resultData($data));
        
        $result = array(
            "status" => false,
            "message" => "error execute",
            "data" => $data,
        );
        return $response->withJson($result, 500);
    }

    public function deleteBooks(Request $request, Response $response, $args)
    {
        $id = $args["id"];
        $sql = "DELETE FROM books WHERE book_id=:id";
        $stmt = $this->container->db->prepare($sql);
            
        $data = [
            ":id" => $id
        ];
        
        if($stmt->execute($data))
            return $response->withJson($this->resultData($data));
        
        $result = array(
            "status" => false,
            "message" => "error execute",
            "data" => $data,
        );
        return $response->withJson($result, 500);
    }
}
