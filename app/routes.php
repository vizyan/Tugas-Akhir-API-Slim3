<?php
    // Routes
    use Slim\Http\Request;
    use Slim\Http\Response;

    require_once __DIR__ . '/../vendor/autoload.php';

    function resultData($data, $message)
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

            $app->get('/category/{id}', App\Action\ProductsController::class.':getByCat')->setName('get product by category');

            $app->get('/search/', App\Action\ProductsController::class.':search')->setName('search product');

            $app->post('/', App\Action\ProductsController::class.':add')->setName('add product');

            $app->post('/{id}', App\Action\ProductsController::class.':edit')->setName('edit product by id');

            $app->delete('/{id}', App\Action\ProductsController::class.':delete')->setName('delete product');
        });

        // -----------------------------------------------------------------------------
        // Routes transactions
        // -----------------------------------------------------------------------------

        $app->group('/trans', function () use ($app) {

            $app->get('/', App\Action\TransactionController::class.':getAll')->setName('get all transaction');

            $app->get('/{id}', App\Action\TransactionController::class.':getById')->setName('get transaction by id');

            $app->get('/user/{id}', App\Action\TransactionController::class.':getByUser')->setName('get transaction by user');

            $app->get('/search/', App\Action\TransactionController::class.':search')->setName('search transaction');

            $app->post('/', App\Action\TransactionController::class.':add')->setName('add transaction');

            $app->get('/detail/', App\Action\DetailTransController::class.':getAll')->setName('get all detail transaction');

            $app->get('/detail/{id}', App\Action\DetailTransController::class.':getByIdTrans')->setName('get detail by id transaction');

            $app->post('/detail/', App\Action\DetailTransController::class.':add')->setName('add transaction');
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

            $app->get('/', App\Action\CartController::class.':getAll')->setName('get all cart');

            $app->get('/{id}', App\Action\CartController::class.':getByUser')->setName('get cart by User');

            $app->get('/search/', App\Action\CartController::class.':search')->setName('search cart by user');

            $app->post('/', App\Action\CartController::class.':add')->setName('add cart');

            $app->post('/{id}', App\Action\CartController::class.':edit')->setName('edit status cart');

            $app->delete('/{id}', App\Action\CartController::class.':delete')->setName('delete cart');
        });

        // -----------------------------------------------------------------------------
        // Routes RajaOngkir
        // -----------------------------------------------------------------------------

        $app->group('/ongkir', function () use ($app) {

            $app->get('/city/', function ($request, $response, $args) {
                $data = RajaOngkir\RajaOngkir::Kota()->all();
                $result = resultData($data, "Semua kota");
                return $this->response->withJson($result);
            });

            $app->get('/{id}', function ($request, $response, $args) {
                $id = $args["id"];
                $data = RajaOngkir\RajaOngkir::Kota()->find($id);
                $result = resultData($data, "Koda berdasar id : $id");
                return $this->response->withJson($result);
            });

            $app->get('/', function ($request, $response, $args) {
                $name = $request->getQueryParam("city");
                $data = RajaOngkir\RajaOngkir::Kota()->search('city_name', $name)->get();
                $result = resultData($data, "Kota berdasarkan nama : $name");
                return $this->response->withJson($result);
            });

            $app->post('/', function ($request, $response, $args) {
                $dataIn = $request->getParsedBody();
                $origin = $dataIn["origin"];
                $destination = $dataIn["destination"];
                $weight = $dataIn["weight"];
                $courier = $dataIn["courier"];

                if (empty($origin) || empty($destination) || empty($weight) || empty($courier) ) {
                    return $this->$response->withJson(resultData(null, "Form kosong"));
                } else {
                    $data = RajaOngkir\RajaOngkir::Cost([
                        'origin' 		=> $origin,
                        'destination' 	=> $destination,
                        'weight' 		=> $weight,
                        'courier' 		=> $courier,
                    ])->get();
                    
                    $result = resultData($data, "Ongkos kirim");
                    return $this->response->withJson($result);
                }
            });

        });

    });