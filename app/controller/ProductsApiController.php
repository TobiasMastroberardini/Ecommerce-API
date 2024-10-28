<?php
    require_once 'app/controller/ApiController.php';
    require_once 'app/helper/ApiAuthHelper.php';
    require_once 'app/model/ProductsModel.php';

    class ProductsApiController extends ApiController {
        private $model;
        private $authHelper;

        function __construct() {
            parent::__construct();
            $this->model = new ProductModel();
            $this->authHelper = new ApiAuthHelper();
        }
 function get($params = []) {
            if (empty($params)){
                $paramsGet=[];
                if (isset($_GET['nombre_producto'])){
                    $bodega=$this->model->getProductByNombre($_GET['nombre_producto']);
                    if($bodega){
                        $paramsGet['nombre_producto'] = $_GET['nombre_producto'];
                    }else{
                        $this->view->response("El producto ingresado no existe", 404);
                        return;
                    }
                }
                if (isset($_GET['categoria'])){
                    $cepa=$this->model->getProductsByCategoria($_GET['categoria']);
                    if($cepa){
                        $paramsGet['categoria'] = $_GET['categoria'];
                    }else{
                        $this->view->response("La categoria ingresada no existe", 404);
                        return;
                    }
                }
                if (isset ($_GET ['sort'])){
                    $columnas = $this->model->getColumnName();
                    $tituloValido=false;
                    foreach ($columnas as $columna){
                        if ($_GET ['sort'] == $columna->column_name){
                            $paramsGet['sort'] = $_GET['sort'];
                            $tituloValido=true;
                        }
                    }
                    if ($tituloValido==false){
                        $this->view->response("El título ingresado no existe", 404);
                        return;
                    }
                }
                if (isset ($_GET ['order'])){
                    if ($_GET ['order']=="asc"||$_GET ['order']=="desc"){
                        $paramsGet['order'] = $_GET['order'];
                    }else{
                        $this->view->response("Criterio de ordenamiento no válido (utilice: asc ó desc)", 404);
                        return;
                    }
                }
                if (isset ($_GET ['page'])){
                    if (is_numeric($_GET ['page']) && $_GET ['page'] >=1){
                        $paramsGet['page'] = $_GET['page'];
                    }else{
                        $this->view->response("Ingrese un número de página válido", 404);
                        return;
                    }
                }
                $productos = $this->model->getProducts($paramsGet);
                $this->view->response($productos, 200);
            } else {
                $producto = $this->model->getProductById($params[':ID']);
                if(!empty($producto) && empty($params[':subrecurso'])) {
                    $this->view->response($producto, 200);
                }else if(!empty($producto)){
                    $subrecurso = $params[':subrecurso'];
                    if (isset($vino->$subrecurso)) {
                        $this->view->response($producto->$subrecurso, 200);
                    } else {
                        $this->view->response("Subrecurso no existe", 404);
                    }
                } else {    
                    $this->view->response('El producto con el id='.$params[':ID'].' no existe.', 404);
                }
            }
        }

        function delete($params = []) {
            $id = $params[':ID'];
            $producto = $this->model->getProductById($id);
            if($producto) {
                $this->model->deleteProduct($id);
                $this->view->response('El producto con id='.$id.' ha sido borrado.', 200);
            } else {
                $this->view->response('El producto con id='.$id.' no existe.', 404);
            }
        }

        function create($params = []) {
            $user = $this->authHelper->currentUser();
            if(!$user) {
                $this->view->response('Unauthorized', 401);
                return;
            }
            $body = $this->getData();
            $Nombre = $body->Nombre;
            $Tipo = $body->Tipo;
            $Azucar = $body->Azucar;
            $id_bodega = $body->id_bodega;
            $id_cepa = $body->id_cepa;
            $bodega=$this->model->getBodegaById($id_bodega);
            if(!$bodega){
                $this->view->response('El id_bodega ' .$id_bodega. ' no existe', 404);
                return;
            }
            $cepa=$this->model->getCepaById($id_cepa);
            if(!$cepa){
                $this->view->response('El id_cepa ' .$id_cepa. ' no existe', 404);
                return;
            }
            $id = $this->model->insertVino($Nombre, $Tipo, $Azucar, $id_bodega, $id_cepa);
            $this->view->response('El vino fue insertado con el id='.$id, 201);
        }
        function update($params = []) {
            $user = $this->authHelper->currentUser();
            if(!$user) {
                $this->view->response('Unauthorized', 401);
                return;
            }
            $id = $params[':ID'];
            $vino = $this->model->getVino($id);

            if($vino) {
                $body = $this->getData();

                $Nombre = $body->Nombre;
                $Tipo = $body->Tipo;
                $Azucar = $body->Azucar;
                $id_bodega = $body->id_bodega;
                $id_cepa = $body->id_cepa;

                $bodega=$this->model->getBodegaById($id_bodega);
                if(!$bodega){
                    $this->view->response('El id_bodega ' .$id_bodega. ' no existe', 404);
                    return;
                }
                $cepa=$this->model->getCepaById($id_cepa);
                if(!$cepa){
                    $this->view->response('El id_cepa ' .$id_cepa. ' no existe', 404);
                    return;
                }

                $this->model->updateVino($Nombre, $Tipo, $Azucar, $id_bodega, $id_cepa, $id);

                $this->view->response('El vino con id='.$id.' ha sido modificado.', 200);
            } else {
                $this->view->response('El vino con id='.$id.' no existe.', 404);
            }
        }
    }