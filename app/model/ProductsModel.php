<?php

require_once 'app/model/ApiModel.php';

class ProductModel extends Model{

    function getProducts() {
        // Consulta con JOIN para traer el nombre de la categoría
        $query = $this->db->prepare('
        SELECT productos.*, categorias.nombre AS categoria_nombre
        FROM productos
        JOIN categorias ON productos.id_categoria = categorias.id_categoria
        WHERE productos.disponible = 1
        ');
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    function getProductById($producto_id){
        $query = $this->db->prepare('SELECT * FROM productos WHERE id_producto  = ?');
        $query->execute([$producto_id]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    function getProductByNombre($nombre){
        $query = $this->db->prepare('SELECT * FROM productos WHERE nombre = ?');
        $query->execute([$nombre]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    function getProductsByCategoria($categoria) {
        // Consulta con JOIN para traer el nombre de la categoría
        $query = $this->db->prepare('
        SELECT productos.*, categorias.nombre AS categoria_nombre
        FROM productos
        JOIN categorias ON productos.id_categoria = categorias.id_categoria
        WHERE productos.id_categoria = ?
        ');
        $query->execute([$categoria]);
        return $query->fetchAll(PDO::FETCH_OBJ); // Retorna el resultado como un array de objetos
    }

    function getSellerId($id_producto) {
        $query = $this->db->prepare('SELECT id_vendedor FROM productos WHERE id_producto = ?');
        $query->execute([$id_producto]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    function getPrecioProducto($id_producto){
        $query = $this->db->prepare('SELECT precio FROM productos WHERE id_producto = ?');
        $query->execute([$id_producto]);
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result->precio;
    }

    function createProduct($id_vendedor, $categoria, $nombre, $descripcio, $precio, $imagen, $stock, $fecha_creacion) {
        $query = $this->db->prepare('INSERT INTO productos (id_vendedor, id_categoria, nombre, descripcion, precio, imagen, stock, fecha_creacion, disponible) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $query->execute([$id_vendedor, $categoria, $nombre, $descripcio, $precio, $imagen, $stock, $fecha_creacion, 1]);
   }

    function editProduct($id_producto, $categoria, $nombre, $descripcion, $precio, $imagen, $stock) {
        $query = $this->db->prepare('UPDATE productos SET id_categoria = ?, nombre = ?, descripcion = ?, precio = ?, imagen = ?, stock = ? WHERE id_producto = ?');
        $query->execute([$categoria, $nombre, $descripcion, $precio, $imagen, $stock, $id_producto]);
    }

    function deleteProduct($producto_id){
        $query = $this->db->prepare('DELETE FROM productos WHERE id_producto=?');
        $query->execute([$producto_id]);
    }

    public function getColumnName() {
        $query= $this->db->prepare('SELECT column_name from information_schema.columns where TABLE_SCHEMA = ?');
        $query->execute(["ecommerce"]);
        $columnas= $query->fetchAll(PDO::FETCH_OBJ);

        return $columnas;
    }
}