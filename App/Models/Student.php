<?php


namespace App\Models;


use Core\Model;
use PDO, PDOException;
use Core\Collection;

class Student extends Model
{
    public $id;
    public $name;
    public $averageGrade;
    public $maxGrade;
    public $grades;

    protected $table = 'users';

    /**
     * Initiate Student with the passed argument data
     *
     * @param $student
     * @return void
     */
    public function init($student)
    {
        $this->id = $student->id;
        $this->name = $student->name;
        $this->grades = $this->grades($student->id);
        $this->averageGrade = $this->averageGrade($student->id);
        $this->maxGrade = $this->maxGrade($student->id);
    }


    /**
     *
     * returns @collection or @array of all Students by given Board ID
     *
     * Each student object data from database is passed as argument to Model constructor
     * processed in the init method, then collected in $this->collection array
     *
     * @param $board_id
     * @param bool $toArray
     * @return collection | array
     */

    public static function byBoard($board_id, $toArray = false){
        try{
            $con = self::getDB();
            $query = "SELECT * FROM users WHERE board_id = ?";
            $stmt = $con->prepare($query);
            $stmt->execute(array($board_id));

            if($toArray === false){
                $students = $stmt->fetchAll(PDO::FETCH_OBJ);
                $model = new Student();
                foreach ($students as $student){

                    $model->collect($student);
                }
                $students = Collection::getCollection();
            }else {
                $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
                for($i=0;$i<count($students);$i++){
                    $students[$i]['averageGrade'] = self::averageGrade($students[$i]['id']);
                    $students[$i]['maxGrade'] = self::maxGrade($students[$i]['id']);
                    $students[$i]['grades'] = self::grades($students[$i]['id']);
                }
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
            $student = $stmt->fetch(PDO::FETCH_OBJ);
            $student->averageGrade = self::averageGrade($student->id);
            $student->maxGrade = self::maxGrade($student->id);
            $student->grades = self::grades($student->id);
            return $student;
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

    public static function grades($id){
        return Grade::grade($id);
    }

    public static function averageGrade($id){
        return Grade::averageGrade($id);
    }

    public static function maxGrade($id){
        return Grade::maxGrade($id);
    }

    public static function delete($id){
        try {
            $con = self::getDB();
            $q = "DELETE FROM users WHERE id = ?";
            $stmt = $con->prepare($q);
            $stmt->execute(array($id));
            if ($stmt->rowCount() === 1) {
                $q = "DELETE FROM grades WHERE user_id = ?";
                $query = $con->prepare($q);
                $query->execute(array($id));

                return true;
            } else {
                return false;
            }
        }catch (PDOException $e){
            print $e->getMessage();
        }
    }

    function __destruct()
    {
        unset($students, $student, $model);
    }
}