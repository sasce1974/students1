<?php


namespace App\Controllers;


use Core\Controller;
use App\Models\Grade;

class Grades extends Controller
{
    public function create(){
        $grade = filter_input(INPUT_POST, 'grade',
            FILTER_SANITIZE_NUMBER_INT,
            ['min'=>5, 'max'=>10]);
        $student_id = filter_input(INPUT_POST, 'student_id',
            FILTER_SANITIZE_NUMBER_INT,
            ['min'=>1]);

        if(count(Grade::studentGrades($student_id)) > 3) throw new \Exception('There are already 4 grades for this student');

        if(!empty($grade) && !empty($student_id)){

            $r = Grade::create($student_id, $grade);
            if($r){
                $_SESSION['message'] = "Grade Inserted";
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }else{
                throw new \Exception('Grade not inserted');
            }
        }
    }

    public function destroy(){
        $grade_id = filter_input(INPUT_POST, 'grade_id',
            FILTER_SANITIZE_NUMBER_INT,
            ['min'=>1]);
        $student_id = filter_input(INPUT_POST, 'student_id',
            FILTER_SANITIZE_NUMBER_INT,
            ['min'=>1]);

        if(!empty($grade_id) && !empty($student_id)){
            //token...
            $r = Grade::delete($grade_id, $student_id);

            if($r){
                $_SESSION['message'] = "Grade Deleted";
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }else{
                throw new \Exception('Grade not deleted');
            }
        }else{
            throw new \Exception('Student ID or Grade ID not provided');
        }
    }
}