<?php

require_once 'app/model/ApiModel.php';

class ProductModel extends Model{

    public function getProductos() {
        $query = $this->db->prepare("SELECT * FROM productos INNER JOIN categorias ON productos.id_categoria = categorias.id_categoria");
        $query->execute();
        $productos = $query->fetchAll(PDO::FETCH_OBJ);
        return $productos;
    }

     public function getCampos(){
        $query = $this->db->prepare("SHOW FIELDS FROM productos");
        $query->execute();
        $campos = $query->fetchAll(PDO::FETCH_OBJ);
        return $campos;
    }

    public function getOrdenado($final){
        $query = $this->db->prepare("SELECT * FROM  productos $final");
        $query->execute();
        $productos = $query->fetchAll(PDO::FETCH_OBJ);
        return $productos;
    }

     public function getById($id){
        $query = $this->db->prepare("SELECT * FROM `productos` WHERE id_producto = ?");
        $query->execute([$id]);
        $producto = $query->fetch(PDO::FETCH_OBJ);
        return $producto;
    }

    public function create($id_vendedor,$id_categoria,$nombre,$descripcion,$precio,$stock,$fecha_creacion,$imagen,$disponible){
        $query = $this->db->prepare("INSERT INTO productos (id_vendedor, id_categoria, nombre, descripcion, precio, stock, fecha_creacion, imagen, disponible) VALUES (?,?,?,?,?,?,?,?,?)");
        $query->execute([$id_vendedor,$id_categoria,$nombre,$descripcion,$precio,$stock,$fecha_creacion,$imagen,$disponible]);
        return $this->db->lastInsertId();
    }

    public function delete($id_producto){
        $query = $this->db->prepare('DELETE FROM `productos` WHERE id_producto = ?');
        $query->execute([$id_producto]);
        return $query;
    }

    public function update($id_producto,$id_vendedor,$id_categoria,$nombre,$descripcion,$precio,$stock,$fecha_creacion,$imagen,$disponible){
        $query = $this->db->prepare("UPDATE `productos` SET id_vendedor=?,id_categoria=?,nombre=?,descripcion=?,precio=?,stock=?,fecha_creacion=?,imagen=?,disponible=? WHERE id_producto=?");
        $query->execute([$id_vendedor,$id_categoria,$nombre,$descripcion,$precio,$stock,$fecha_creacion,$imagen,$disponible,$id_producto]);
        return $query;
    }
}