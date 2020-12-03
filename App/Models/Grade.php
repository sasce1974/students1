<?php


namespace App\Models;


use Core\Model;
use PDO, PDOException;

class Grade extends Model
{
    public static function studentGrades($student_id){
        try{
            $con = self::getDB();
            $q = "SELECT * FROM grades WHERE user_id = ?";
            $query = $con->prepare($q);
            $query->execute(array($student_id));
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e){
            print $e->getMessage();
        }
    }


    private static function grade($id){
        $grades = self::studentGrades($id);
        $g = array();
        foreach ($grades as $grade){
            $g[] = $grade['grade'];
        }
        return $g;
    }

    public static function averageGrade($student_id){
        $g = self::grade($student_id);
        if(count($g) > 0){
            return array_sum($g)/count($g);
        }
    }

    public static function maxGrade($student_id){
        $g = self::grade($student_id);
        if(count($g) > 0){
            return max($g);
        }
    }

    public static function create($user_id, $grade){
        try {
            $con = self::getDB();
            $q = "INSERT INTO grades (user_id, grade) VALUES (?, ?)";
            $query = $con->prepare($q);
            $r = $query->execute(array($user_id, $grade));
            if ($r){
                return true;
            }else{
                return false;
            }
        }catch (PDOException $e){
            print $e->getMessage();
        }
    }

    public static function delete($id, $student_id){
        try {
            $con = self::getDB();
            $q = "DELETE FROM grades WHERE id= ? AND user_id = ?";
            $query = $con->prepare($q);
            $r = $query->execute(array($id, $student_id));
            if ($query->rowCount() === 1){
                return true;
            }else{
                return false;
            }
        }catch (PDOException $e){
            print $e->getMessage();
        }
    }
}