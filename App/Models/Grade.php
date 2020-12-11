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


    public static function grade($id){
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


    public static function export($board_id)
    {
        $users = Student::byBoard($board_id, true);
        foreach ($users as $student){

            if($board_id === 1){
                $student['final_result'] = ($student['averageGrade'] < 7) ? 'Failed' : 'Passed';
            }else{
                $student['final_result'] = (count($student['grades']) > 2 && $student['maxGrade'] >= 8) ? 'Passed' : 'Failed';
            }
        }
        //print "<pre>"; print_r($users); print "</pre>"; exit();
        /*$data = array();

        for ($i = 0; $i < count($users); $i++) {
            $grades = $user->grade($users[$i]->id);
            $average = $user->averageGrade($users[$i]->id);
            $maxGrade = $user->maxGrade($users[$i]->id);
            $data[$i]['user']['id'] = $users[$i]->id;
            $data[$i]['user']['name'] = $users[$i]->name;
            $data[$i]['grades'] = $grades;
            $data[$i]['average'] = $average;

            if ($board_id === 1) {
                $data[$i]['final result'] = ($average < 7) ?
                    'Failed' : 'Passed';
            } else {
                $data[$i]['final result'] = (count($grades) > 2 &&
                    $maxGrade >= 8) ?
                    'Passed' : 'Failed';
            }

        }*/

        if ($board_id == 1) {
            return json_encode($users);
        } else {
            return self::arrayToXML($users);
        }
    }


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



        //array_walk_recursive($data, array ($xml_data, 'addChild'));

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