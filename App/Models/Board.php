<?php


namespace App\Models;


use Core\Model;

class Board extends Model
{

    public $id;
    public $name;
    public $students = [];

    /**
     * Initialize model
     *
     * @param $board
     */
    public function init($board){
        $this->id = $board->id;
        $this->name = $board->name;
        $this->students = $this->students();
    }

    /**
     * Define Board -> students relationship
     *
     * @return array
     */
    private function students(){
        $student = new Student();
        return $student->where('board_id', $this->id);
    }

}