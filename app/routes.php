<?php
    // Routes
    use Slim\Http\Request;
    use Slim\Http\Response;

    $app->get('/', App\Action\HomeAction::class)
        ->setName('homepage');

    $app->group('/api', function () use ($app) {
        // $app->group('/template', function () use ($app) {});

        // -----------------------------------------------------------------------------
        // Routes books
        // -----------------------------------------------------------------------------

        $app->group('/books', function () use ($app) {
            
            $app->get('/', App\Action\BooksController::class.':getAllBooks')->setName('all books');

            
            $app->get('/{id}', App\Action\BooksController::class.':getBookById')->setName('get book by id');

            
            $app->get('/search/', App\Action\BooksController::class.':searchBooks')->setName('search books');

            
            $app->post('/', App\Action\BooksController::class.':addBook')->setName('add book');
        
            
            $app->post('/{id}', App\Action\BooksController::class.':editBooks')->setName('edit book by id');

            
            $app->delete('/{id}', App\Action\BooksController::class.':deleteBooks')->setName('delete book by id');
        });

        // -----------------------------------------------------------------------------
        // Routes users
        // -----------------------------------------------------------------------------

        $app->group('/users', function () use ($app) {

            $app->get('/', App\Action\CobaController::class.':getAll')->setName('get all user');

            $app->get('/{id}', App\Action\CobaController::class.':getById')->setName('get user by id');

            $app->get('/search/', App\Action\CobaController::class.':search')->setName('search user');

            $app->post('/login/', App\Action\CobaController::class.':login')->setName('login');

            $app->post('/', App\Action\CobaController::class.':add')->setName('add user');

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

            $app->get('/search/', App\Action\CategoryController::class.':search')->setName('search transaction');

            $app->post('/', App\Action\CategoryController::class.':add')->setName('add category');

            $app->post('/{id}', App\Action\CategoryController::class.':edit')->setName('edit category by id');

            $app->delete('/{id}', App\Action\CategoryController::class.':delete')->setName('delete category');
        });
    });