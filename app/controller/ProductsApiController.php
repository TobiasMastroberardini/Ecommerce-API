<?php
    require_once 'app/controller/ApiController.php';
    require_once 'app/helper/ApiAuthHelper.php';
    require_once 'app/model/ProductsModel.php';

    class ProductsApiController extends ApiController {
        private $model;
        private $authHelper;
        private $camposProductos;

        function __construct() {
            parent::__construct();
            $this->model = new ProductModel();
            $this->authHelper = new ApiAuthHelper();
            $this->camposProductos = [];

            // CORS para consumir desde un front
            header("Access-Control-Allow-Origin: http://localhost:3000");
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
            header("Access-Control-Allow-Headers: Content-Type, Authorization");
        }

        function generarCampos(){
            $aux = $this->model->getCampos();
            for($i = 0; $i<count($aux); $i++){
                array_push($this->camposProductos,$aux[$i]->Field);
            }
            return $this->camposProductos;
        }

        function get($params = []){
            $this->generarCampos();
            $ordenes = ['ASC', 'DESC'];

            if(empty($params)){
                $consultaFinal = "";
                $parcialCampo = "";
                $ordenPorParcial = "";
                $paginadoParcial = "";

                if(isset($_GET['campo']) && isset($_GET['valor'])){
                    if(in_array($_GET['campo'],$this->camposProductos)){
                        $campo = $_GET['campo'];
                        $valor = $_GET['valor'];

                        $parcialCampo = "WHERE $campo = '$valor'";
                    }else{
                        $this->view->response("Campo incorrecto. Seleccione un valor del dominio.", 400);
                        return;
                    }
                }

                if(isset($_GET['ordenPor']) && $_GET['orden']){
                    if(in_array(($_GET['ordenPor']), $this->camposProductos)){
                         $orderPor = $_GET['ordenPor'];

                        if(in_array($_GET['orden'],$ordenes)){
                            $orden = $_GET['orden'];
                        }else{
                            $this->view->response("Debe seleccionar un orden adecuado", 400);
                            return;
                        }

                        $ordenPorParcial = "ORDER BY $orderPor $orden";
                    }else{
                        $this->view->response("ordenPor invalido. Por favor seleccione uno adecuado", 400);
                        return;
                    }
                }

                if(isset($_GET['pagina'])){
                    if(is_numeric($_GET['pagina'])){
                        if($_GET['pagina'] <=0){
                            $pagina = 1;
                        }else{
                            $pagina = $_GET['pagina'];
                        }

                        if(isset($_GET['limite'])){
                            $limite = $_GET['limite'];
                        }else{
                            $limite = 3;
                        }

                        $inicio = ((int)$pagina - 1) * ((int)$limite);
                        $paginadoParcial = "LIMIT $inicio,$limite";
                }else{
                    $this->view->response("Pagina invalida. Por favor seleccione un valor numerico", 400);
                    return;
                }
            }

            $consultaFinal = $parcialCampo. " " .$ordenPorParcial. " " .$paginadoParcial;

            if($consultaFinal != ""){
                $productos = $this->model->getOrdenado($consultaFinal);
            }else{
                $productos = $this->model->getProductos();
            }

            if($productos){
                $this->view->response($productos,200);
                return;
            }else{
                $this->view->response("No se han encontrado productos", 404);
                return;
            }

            }else{
                if($params[':ID']){
                    $id = $params[':ID'];
                    $producto = $this->model->getById($id);
                    if($producto){
                        $this->view->response($producto, 200);
                        return;
                    }else {
                        $this->view->response("El producto con id = $id no existe", 404);
                        return;
                    }
                }else {
                    $this->view->response("Sintaxis de enpoint invalida", 400);
                    return;
                }
            }
        }

        function create(){
            if($this->authHelper->verificarCliente()){
                $body = $this->getData();

                $id_vendedor = $body->id_vendedor;
                $id_categoria = $body->id_categoria;
                $nombre = $body->nombre;
                $descripcion = $body->descripcion;
                $precio = $body->precio;
                $imagen = $body->imagen;
                $stock = $body->stock;
                $fecha_creacion = $body->fecha_creacion;
                $disponible = 1;

                if(empty($body->imagen)){
                    $imagen = null;
                }

                if(empty($id_vendedor)|| empty($id_categoria)|| empty($nombre)||empty($descripcion)||empty($precio)||empty($imagen)||empty($stock) ||empty($fecha_creacion)){
                    $this->view->response('Por favor complete todo los campos',400);
                    return;
                }else{
                    $productoCreado = $this->model->create($id_vendedor,$id_categoria,$nombre,$descripcion,$precio,$imagen,$stock,$fecha_creacion,$disponible);

                    $this->view->response("El producto se creo correctamente con el id $productoCreado", 201);
                    return;
                }
            }else{
                $this->view->response("Debe entregar un token de Autorizacion",401);
                return;
            }
        }

        function update($params = []){
            if($this->authHelper->verificarCliente()){
                $id = $params[':ID'];
                $producto = $this->model->getById($id);

                if($producto){
                    $body = $this->getData();
                    
                    if(!empty($body->id_vendedor) || !empty($body->id_categoria) || !empty($body->nombre) || !empty($body->descripcion) || !empty($body->precio)||!empty($body->imagen) || !empty($body->stock) || !empty($body->fecha_creacion) || !empty($body->disponible)){
                        $id_vendedor = $body->id_vendedor;
                        $id_categoria = $body->id_categoria;
                        $nombre = $body->nombre;
                        $descripcion = $body->descripcion;
                        $precio = $body->precio;
                        $imagen = $body->imagen;
                        $stock = $body->stock;
                        $fecha_creacion = $body->fecha_creacion;
                        $disponible = $body->disponible;

                        if(empty($body->imagen)){
                            $imagen = null;
                        }

                        $prod = $this->model->update($id,$id_vendedor,$id_categoria,$nombre,$descripcion,$precio,$imagen,$stock,$fecha_creacion,$disponible);
                        $this->view->response("El producto con id = $id ha sido modificado", 200);
                        return;
                    }else{
                        $this->view->response("Error. Faltan completar campos",400);
                        return;
                    }
                }else{
                    $this->view->response("No existe un producto con el id = $id", 404);
                }
            }else{
                $this->view->response("Debe ingresar el token de Autorizacion", 401);
                return;
            }
        }

        function delete($params = []){
            if($this->authHelper->verificarCliente()){
                $id = $params[':ID'];
                $producto = $this->model->getById($id);

                if($producto){
                    $this->model->delete($id);
                    $this->view->response("Producto con id = $id eliminado correctamente", 200);
                    return;
                }else{
                    $this->view->response("El producto con id = $id no existe",404);
                    return;
                }
            }else{
                $this->view->response("Debe ingresar el token de Autorizacion", 401);
                return;
            }
        }
    }
