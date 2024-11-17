<?php
    require_once 'app/controller/ApiController.php';
    require_once 'app/helper/ApiAuthHelper.php';
    require_once 'app/model/UsersModel.php';

    class UsersApiController extends ApiController {
        private $model;
    private $helper;

    function __construct(){
        parent::__construct();
        $this->model = new UserModel();
        $this->helper = new ApiAuthHelper();
    }
    
    function obtenerToken($params = []) {
        $basic = $this->helper->obtenerAuthHeaders();

        if(empty($basic)) {
            $this->view->response('No envió encabezados de autenticación.', 401);
            return;
        }

        $basic = explode(" ", $basic);

        if($basic[0]!="Basic") {
            $this->view->response('Los encabezados de autenticación son incorrectos.', 401);
            return;
        }

        $userpass = base64_decode($basic[1]);
        $userpass = explode(":", $userpass);

        $email = $userpass[0];
        $password = $userpass[1];

        
        $usuario = $this->model->obtenerUsuario($email);

        if($usuario&&password_verify($password,$usuario->contraseña)) {
            $usuario = [ "nombre" => $usuario->email, "id" => $usuario->id_usuario];
            $token = $this->helper->crearToken($usuario);
            $this->view->response($token,200);
            return;
        } else {
            $this->view->response('El usuario o contraseña son incorrectos.', 401);
            return;
        }
    }
}