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
            //get all users
            $app->get('/', App\Action\UsersController::class.':getAllUsers')->setName('all users');

            //get user by id
            $app->get('/{id}', App\Action\UsersController::class.':getUserById')->setName('get user by id');

            //search books by title, sinopsis, author
            $app->get('/search/', App\Action\UsersController::class.':searchUsers')->setName('search users');

            //register user
            $app->post('/', App\Action\UsersController::class.':addUser')->setName('add user');

            //login user
            $app->post('/login/', App\Action\UsersController::class.':loginUser')->setName('login by email');

            //edit book
            $app->post('/{id}', App\Action\UsersController::class.':editUsers')->setName('edit user by id');

            //delete book
            $app->delete('/{id}', App\Action\UsersController::class.':deleteUsers')->setName('delete user by id');
        });

        $app->group('/products', function () use ($app) {
            //get all users
            $app->get('/', App\Action\UsersController::class.':getAllProducts')->setName('all users');

            //get user by id
            $app->get('/{id}', App\Action\UsersController::class.':getProductsById')->setName('get user by id');

            //search books by title, sinopsis, author
            $app->get('/search/', App\Action\BooksController::class.':searchProducts')->setName('search books');

            //register user
            $app->post('/', App\Action\UsersController::class.':addProduct')->setName('add user');

            //edit book
            $app->post('/{id}', App\Action\UsersController::class.':editProducts')->setName('edit user by id');

            //delete book
            $app->delete('/{id}', App\Action\UsersController::class.':deleteProducts')->setName('delete user by id');
        });

        $app->group('/coba', function () use ($app) {
            $app->get('/', App\Action\CobaController::class.':getAllBooks')->setName('all users');
        });
    });