<?php


namespace App\Controllers;


use App\Models\Board;
use App\Models\Grade;
use App\Models\Student;
use Core\Controller;
use Core\View;

class Students extends Controller
{
    public function indexAction(){
        $this->showAction();
    }

    public function showAction(){
        $id = filter_var($this->route_params['id'], FILTER_SANITIZE_NUMBER_INT, ['min'=>1]);
        $student = Student::show($id);
        //$board_id = filter_var($this->route_params['id'], FILTER_SANITIZE_NUMBER_INT, ['min'=>1]);
        $board = Board::board($student->board_id);
        $grades = Grade::studentGrades($student->id);
        $averageGrade = Grade::averageGrade($student->id);
        $maxGrade = Grade::maxGrade($student->id);

        //var_dump($maxGrade); exit();

        View::render('Students/index.php', [
            'board'=>$board,
            'student'=>$student,
            'grades'=>$grades,
            'averageGrade'=>$averageGrade,
            'maxGrade'=>$maxGrade
        ]);

    }


    public function create(){
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $board_id = filter_input(INPUT_POST, 'board_id', FILTER_SANITIZE_NUMBER_INT, ['min'=>1]);

        if(!empty($name)){
            /*if($data['token'] !== $_SESSION['token']){
                $_SESSION['error'] = "Token mismatch (session token is: " . $_SESSION['token'] . ")";
                return false;
            }*/
            //$user = new Student();
            $r = Student::create(['board_id'=>$board_id, 'name'=>$name]);
            if($r){
                $_SESSION['message'] = "Student Created";
                header('Location:' . $_SERVER['HTTP_REFERER']);
                return true;
            }else{
                //$_SESSION['error'] = "Error In Student Creation Process";
                throw new \Exception('Error In Student Creation Process');
                return false;
            }
        }else{
            throw new \Exception('Please Insert Student Name');
            $_SESSION['error'] = "Please Insert Student Name";
            return false;
        }
    }

    public function destroy(){
        //check token
        $id = filter_var($this->route_params['id'], FILTER_SANITIZE_NUMBER_INT, ['min'=>1]);
        $r = Student::delete($id);
        if($r){
            $_SESSION['message'] = "Student Deleted";
            http_response_code(200);
            header('Location:' . $_SERVER['HTTP_REFERER']);
            return true;
        }else{
            throw new \Exception('Error In Student Deletion Process');
        }
    }
}