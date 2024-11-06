<?php
    require_once 'config.php';
    require_once 'libs/router.php';

    require_once 'app/controller/ProductsApiController.php';
    require_once 'app/controller/UsersApiController.php';

   $router = new Router();

    #                     endpoint      verbo         controller            método
    $router->addRoute('productos'     , 'GET'   , 'ProductsApiController' , 'get');
   
    $router->addRoute('productos/:ID' , 'GET'   , 'ProductsApiController' , 'get');
   
    $router->addRoute('productos'     , 'POST'  , 'ProductsApiController' , 'create');
   
    $router->addRoute('productos/:ID' , 'PUT'   , 'ProductsApiController' , 'update');
       
    $router->addRoute('productos/:ID' , 'DELETE', 'ProductsApiController' , 'delete');
  
    $router->addRoute('auth/token'    , 'GET'   , 'UsersApiController'      , 'obtenerToken'   );
 
    $router->addRoute('categorias'    , 'GET'   , 'CategoriesApiController', 'obtener');

    $router->route($_GET['resource'], $_SERVER['REQUEST_METHOD']);