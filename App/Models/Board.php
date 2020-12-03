<?php


namespace App\Models;


use Core\Model;
use PDO, PDOException;

class Board extends Model
{
    public static function board($id){
        try{
            $con = self::getDB();
            $query = "SELECT * FROM boards WHERE id = ?";
            $stmt = $con->prepare($query);
            $stmt->execute(array($id));
            return $stmt->fetch(PDO::FETCH_OBJ);
        }catch (PDOException $e){
            print $e->getMessage();
        }
    }
}