<?php

require_once 'app/model/ApiModel.php';

class UserModel extends Model{

    function getUsers(){
        $query = $this->db->prepare('SELECT * FROM usuarios');
        $query->execute([]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    function getUSer($email){
        $query = $this->db->prepare('SELECT * FROM usuarios WHERE email = ?');
        $query->execute([$email]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function getRolUser($id_usuario) {
        $query = $this->db->prepare('SELECT rol FROM usuarios WHERE id_usuario = ?');
        $query->execute([$id_usuario]);
    
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result ? (int)$result->rol : null; // Convierte a int y maneja el caso nulo
    }

     function createUser($nombre, $email, $contraseña, $fecha_registro, $imagen){
        $query = $this->db->prepare('INSERT INTO usuarios (nombre, email, contraseña, fecha_registro, rol, imagen) VALUES (?,?,?,?,?,?)');
        $query->execute([$nombre, $email, $contraseña, $fecha_registro, 0, $imagen]);
        return $this->db->lastInsertId();
    }

    function editUser($usuario_id, $email, $contraseña, $imagen){
        $query = $this->db->prepare('UPDATE usuarios SET email = ?, contraseña = ?, imagen = ? WHERE usuario_id = ?');
        $query->execute([$usuario_id, $email, $contraseña, $imagen]);
    }

    function deleteUser($usuario_id){
        $query = $this->db->prepare('DELETE * FROM usuarios WHERE usuario_id=?');
        $query->execute([$usuario_id]);
    }
}