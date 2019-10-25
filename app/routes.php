<?php
    // Routes
    use Slim\Http\Request;
    use Slim\Http\Response;

    require_once __DIR__ . '/../vendor/autoload.php';

    function resultData($data)
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
                "message" => "data kosong",
                "data" => null,
            );
        }
        return $result;
    };

    $app->get('/', App\Action\HomeAction::class)
        ->setName('homepage');

    $app->group('/api', function () use ($app) {

        // -----------------------------------------------------------------------------
        // Routes users
        // -----------------------------------------------------------------------------

        $app->group('/users', function () use ($app) {

            $app->get('/', App\Action\CobaController::class.':getAll')->setName('get all user');

            $app->get('/{id}', App\Action\CobaController::class.':getById')->setName('get user by id');

            $app->get('/search/', App\Action\CobaController::class.':search')->setName('search user');

            $app->post('/login/', App\Action\CobaController::class.':login')->setName('login');

            $app->post('/', App\Action\CobaController::class.':add')->setName('register user');

            $app->post('/{id}', App\Action\CobaController::class.':edit')->setName('edit user by id');

            $app->delete('/{id}', App\Action\CobaController::class.':delete')->setName('delete user');
        });

        // -----------------------------------------------------------------------------
        // Routes products
        // -----------------------------------------------------------------------------

        $app->group('/products', function () use ($app) {

            $app->get('/', App\Action\ProductsController::class.':getAll')->setName('get all product');

            $app->get('/{id}', App\Action\ProductsController::class.':getById')->setName('get product by id');

            $app->get('/search/', App\Action\ProductsController::class.':search')->setName('search product');

            $app->post('/', App\Action\ProductsController::class.':add')->setName('add product');

            $app->post('/{id}', App\Action\ProductsController::class.':edit')->setName('edit product by id');

            $app->delete('/{id}', App\Action\ProductsController::class.':delete')->setName('delete product');
        });

        // -----------------------------------------------------------------------------
        // Routes transactions
        // -----------------------------------------------------------------------------

        $app->group('/transactions', function () use ($app) {

            $app->get('/', App\Action\TransactionsController::class.':getAll')->setName('get all transaction');

            $app->get('/{id}', App\Action\TransactionsController::class.':getById')->setName('get transaction by id');

            $app->get('/search/', App\Action\TransactionsController::class.':search')->setName('search transaction');

            $app->post('/', App\Action\TransactionsController::class.':add')->setName('add transaction');

            $app->post('/{id}', App\Action\TransactionsController::class.':edit')->setName('edit transaction by id');

            $app->delete('/{id}', App\Action\TransactionsController::class.':delete')->setName('delete transaction');
        });

        // -----------------------------------------------------------------------------
        // Routes category
        // -----------------------------------------------------------------------------

        $app->group('/category', function () use ($app) {

            $app->get('/', App\Action\CategoryController::class.':getAll')->setName('get all category');

            $app->get('/{id}', App\Action\CategoryController::class.':getById')->setName('get category by id');

            $app->get('/search/', App\Action\CategoryController::class.':search')->setName('search cart');

            $app->post('/', App\Action\CategoryController::class.':add')->setName('add cart');

            $app->post('/{id}', App\Action\CategoryController::class.':edit')->setName('edit category by id');

            $app->delete('/{id}', App\Action\CategoryController::class.':delete')->setName('delete cart');
        });

        // -----------------------------------------------------------------------------
        // Routes cart
        // -----------------------------------------------------------------------------

        $app->group('/cart', function () use ($app) {

            $app->get('/', App\Action\CartController::class.':getAll')->setName('get all transaction');

            $app->get('/{id}', App\Action\CartController::class.':getByUser')->setName('get transaction by User');

            $app->get('/search/', App\Action\CartController::class.':search')->setName('search transaction by user');

            $app->post('/', App\Action\CartController::class.':add')->setName('add transaction');

            $app->post('/{id}', App\Action\CartController::class.':edit')->setName('edit status cart');

            $app->delete('/{id}', App\Action\CartController::class.':delete')->setName('delete transaction');
        });

        // -----------------------------------------------------------------------------
        // Routes RajaOngkir
        // -----------------------------------------------------------------------------

        $app->group('/ongkir', function () use ($app) {

            $app->get('/city/', function ($request, $response, $args) {
                $data = RajaOngkir\RajaOngkir::Kota()->all();
                $result = resultData($data);
                return $this->response->withJson($result);
            });

            $app->get('/{id}', function ($request, $response, $args) {
                $id = $args["id"];
                $data = RajaOngkir\RajaOngkir::Kota()->find($id);
                $result = resultData($data);
                return $this->response->withJson($result);
            });

            $app->get('/', function ($request, $response, $args) {
                $name = $request->getQueryParam("city");
                $data = RajaOngkir\RajaOngkir::Kota()->search('city_name', $name)->get();
                $result = resultData($data);
                return $this->response->withJson($result);
            });

            $app->post('/', function ($request, $response, $args) {
                $dataIn = $request->getParsedBody();
                $origin = $dataIn["origin"];
                $destination = $dataIn["destination"];
                $weight = $dataIn["weight"];
                $courier = $dataIn["courier"];

                $data = RajaOngkir\RajaOngkir::Cost([
                    'origin' 		=> $origin,
                    'destination' 	=> $destination,
                    'weight' 		=> $weight,
                    'courier' 		=> $courier,
                ])->get();
                
                return $this->response->withJson($data);
            });

        });

    });