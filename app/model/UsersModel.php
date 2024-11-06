<?php

require_once 'app/model/ApiModel.php';

class UserModel extends Model{

    public function obtenerUsuario($email){
        $query = $this->db->prepare("SELECT * FROM `usuarios` WHERE email = ?");
        $query->execute([$email]);
        $res = $query->fetch(PDO::FETCH_OBJ);
        return $res;
    }
}