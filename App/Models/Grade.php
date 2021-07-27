<?php


namespace App\Models;


use App\Controllers\Grades;
use Core\Model;
use mysql_xdevapi\Collection;
use PDO, PDOException;

class Grade extends Model
{
    public $id;
    public $student_id;
    public $grade;
    public $created_at;
    public $updated_at;

    /**
     * initialize model Grade
     *
     * @param $grade
     */
    public function init($grade)
    {
        if(is_object($grade)) {
            $this->id = $grade->id;
            $this->grade = $grade->grade;
            $this->student_id = $grade->user_id;
            $this->created_at = $grade->created_at;
            $this->updated_at = $grade->updated_at;
        }
    }

    /**
     * Get all student grades as Grade object
     *
     * @param $student_id
     * @return Collection
     */
    public static function studentGrades($student_id)
    {
        try {
            $grades = new Grade();
            return $grades->where('user_id', $student_id);
        } catch (PDOException $e) {
            print $e->getMessage();
        }
    }


    /**
     * Get student grades in array
     *
     * @param $id - student id
     * @return array
     */
    public static function grade($id) : array
    {
        $grades = self::studentGrades($id);
        $g = array();
        foreach ($grades as $grade){
            $g[] = $grade->grade;
        }

        return $g;
    }

    /**
     * Get average student grade
     *
     * @param $student_id
     * @return float|int
     */
    public static function averageGrade($student_id){
        $g = self::grade($student_id);
        if(count($g) > 0){
            return array_sum($g)/count($g);
        }
    }

    /**
     * Get student max grade
     *
     * @param $student_id
     * @return int | void
     */
    public static function maxGrade($student_id){
        $g = self::grade($student_id);
        if(count($g) > 0){
            return max($g);
        }
    }

    /**
     * Create new grade
     *
     * @param $user_id
     * @param $grade
     * @return bool
     */
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

    /**
     * Delete grade
     *
     * @param $id
     * @param $student_id
     * @return bool
     */
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


    /**
     * Get results as JSON or XML
     *
     * @param $board_id
     * @return false|mixed|string
     */
    public static function export($board_id)
    {
        $users = Student::byBoard($board_id, true);
        foreach ($users as $student){

            if($board_id == 1){
                $student['final_result'] = ($student['averageGrade'] < 7) ? 'Failed' : 'Passed';
            }else{
                $student['final_result'] = (count($student['grades']) > 2 && $student['maxGrade'] >= 8) ? 'Passed' : 'Failed';
            }
        }

        if ($board_id == 1) {
            return json_encode($users);
        } else {
            return self::arrayToXML($users);
        }
    }


    /**
     * Convert array to XML object
     *
     * @param array $data
     * @return mixed
     */
    private static function arrayToXML(array $data){

        $xml_data = new \SimpleXMLElement('<data></data>');

        function array_to_xml($data, &$xml_data ) {
            foreach( $data as $key => $value ) {
                if( is_array($value) ) { //if $value is array, make recursive function
                    if( is_numeric($key) ){
                        $key = 'item'.$key;
                    }
                    $xml_data->addChild($key);
                    array_to_xml($value,$xml_data);
                } else {
                    $xml_data->addChild($key, htmlspecialchars("$value"));
                }
            }
        }

        array_to_xml($data,$xml_data);
        return $xml_data->asXML();

        /*$xml = $xml_data;
        $dom = new \DOMDocument('1.0');
        $dom->preserveWhiteSpace = true;
        $dom->formatOutput = true;
        $dom->loadXML($xml);
        $dom->saveXML();*/

    }
}