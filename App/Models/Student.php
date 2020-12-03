<?php


namespace App\Models;


use Core\Model;
use PDO, PDOException;

class Student extends Model
{
    public $id;
    public $name;
    public $averageGrade;
    public $maxGrade;

    public static function all(){
        try{
            $con = self::getDB();
            $query = "SELECT * FROM users";
            $smpt = $con->query($query);
            return $smpt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e){
            print $e->getMessage();
        }
    }

    public static function byBoard($board_id){
        try{
            $con = self::getDB();
            $query = "SELECT * FROM users WHERE board_id = ?";
            $stmt = $con->prepare($query);
            $stmt->execute(array($board_id));
//            return $stmt->fetchAll(PDO::FETCH_OBJ)
            $students = $stmt->fetchAll(PDO::FETCH_OBJ);

            foreach ($students as $student){
                $student->averageGrade = self::averageGrade($student->id);
                $student->maxGrade = self::maxGrade($student->id);
            }
            return $students;

        }catch (PDOException $e){
            print $e->getMessage();
        }
    }

    public static function show($id){
        try{
            $con = self::getDB();
            $query = "SELECT * FROM users WHERE id = ?";
            $stmt = $con->prepare($query);
            $stmt->execute(array($id));
            return $stmt->fetch(PDO::FETCH_OBJ);
        }catch (PDOException $e){
            print $e->getMessage();
        }
    }

    public static function create($data){
        try {
            $con = self::getDB();
            $q = "INSERT INTO users (board_id, name) VALUES (?, ?)";
            $query = $con->prepare($q);
            return $query->execute(array($data['board_id'], $data['name']));
        }catch (PDOException $e){
            print $e->getMessage();
        }
    }

    public static function averageGrade($id){
        return Grade::averageGrade($id);
    }

    public static function maxGrade($id){
        return Grade::maxGrade($id);
    }

    public static function delete($id){
        $con = self::getDB();
        $q = "DELETE FROM users WHERE id = ?";
        $stmt = $con->prepare($q);
        $stmt->execute(array($id));
        if($stmt->rowCount() === 1){
            $q = "DELETE FROM grades WHERE user_id = ?";
            $query = $con->prepare($q);
            $query->execute(array($id));

            return true;
        }else{
            return false;
        }
    }
}