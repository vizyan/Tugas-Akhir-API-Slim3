<?php
    // Routes
    use Slim\Http\Request;
    use Slim\Http\Response;

    $app->get('/', App\Action\HomeAction::class)
        ->setName('homepage');

    $app->group('/api', function () use ($app) {
        // $app->group('/template', function () use ($app) {});

        //group books
        $app->group('/books', function () use ($app) {
            //get all books
            $app->get('/', App\Action\BooksController::class.':getAllBooks')->setName('all books');

            //get book by id
            $app->get('/{id}', App\Action\BooksController::class.':getBookById')->setName('get book by id');

            //search books by title, sinopsis, author
            $app->get('/search/', App\Action\BooksController::class.':searchBooks')->setName('search books');

            //add book
            $app->post('/', App\Action\BooksController::class.':addBook')->setName('add book');
        
            //edit book
            $app->post('/{id}', App\Action\BooksController::class.':editBooks')->setName('edit book by id');

            //delete book
            $app->delete('/{id}', App\Action\BooksController::class.':deleteBooks')->setName('delete book by id');
        });

        //group users
        $app->group('/users', function () use ($app) {

            $app->get('/', App\Action\CobaController::class.':getAll')->setName('get all user Eloquent');

            $app->get('/{id}', App\Action\CobaController::class.':getById')->setName('get user by id Eloquent');

            $app->get('/search/', App\Action\CobaController::class.':search')->setName('search user Eloquent');

            $app->post('/login/', App\Action\CobaController::class.':login')->setName('login Eloquent');

            $app->post('/', App\Action\CobaController::class.':add')->setName('add user Eloquent');

            $app->post('/{id}', App\Action\CobaController::class.':edit')->setName('edit user by id Eloquent');

            $app->delete('/{id}', App\Action\CobaController::class.':delete')->setName('delete user Eqloquent');
        });

        $app->group('/products', function () use ($app) {

            $app->get('/', App\Action\ProductsController::class.':getAll')->setName('get all product Eloquent');

            $app->get('/{id}', App\Action\ProductsController::class.':getById')->setName('get product by id Eloquent');

            $app->get('/search/', App\Action\ProductsController::class.':search')->setName('search product Eloquent');

            $app->post('/', App\Action\ProductsController::class.':add')->setName('add product Eloquent');

            $app->post('/{id}', App\Action\ProductsController::class.':edit')->setName('edit product by id Eloquent');

            $app->delete('/{id}', App\Action\ProductsController::class.':delete')->setName('delete product Eqloquent');
        });
    });