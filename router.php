<?php
    require_once 'config.php';
    require_once 'libs/router.php';

    require_once 'app/controller/ProductsApiController.php';
    require_once 'app/controller/UsersApiController.php';

    $router = new Router();

    #                 endpoint        verbo     controller               método
    $router->addRoute('products',     'GET',    'ProductsApiController', 'get'   );
    $router->addRoute('product',     'POST',   'ProductsApiController', 'create');
    $router->addRoute('product/:ID', 'GET',    'ProductsApiController', 'get'   );
    $router->addRoute('product/:ID', 'PUT',    'ProductsApiController', 'update');
    $router->addRoute('product/:ID', 'DELETE', 'ProductsApiController', 'delete');
    $router->addRoute('user/token',   'GET',    'UserApiController',     'getToken');
    
    $router->addRoute('product/:ID/:subrecurso', 'GET',    'ProductsApiController', 'get'   );
    

    $router->route($_GET['resource'], $_SERVER['REQUEST_METHOD']);