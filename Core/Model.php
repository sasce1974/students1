<?php


namespace Core;

use App\Config;
use App\Interfaces\iModel;
use PDO;
use PDOException;

abstract class Model implements iModel
{
    /**
     * contains table name.
     *
     * Change this if table name does not follow the convention Model name -> table name + (s)
     *
     * @var string
     */
    protected $table = "";

    protected $model;

    /**
     * contains collection of model objects
     *
     * @var array
     */


    /**
     * Model constructor.
     *
     * Create table name, then Initiate object of this model if object data is provided in argument
     *
     * @param null $object
     */
    public function __construct($object = null)
    {
        if($this->table == "") {
            $this->table = lcfirst((new \ReflectionClass(get_class($this)))->getShortName()) . "s";
            //$this->table = lcfirst(get_class($this)) . 's'; // table name as Model class name + 's'
        }
        $this->model = get_class($this);

        if($object) $this->init($object);
    }


    /**
     *
     * Models use this function to initiate its instance by given object data ($model)
     *
     * @param $model
     */
    public function init($model){}



    /**
     * Get the PDO database connection
     *
     * @return mixed
     */
    protected static function getDB()
    {
        static $db = null;

        if ($db === null) {

            $db = new PDO(
                "mysql:host=" . Config::DB_HOST . ";dbname=" . Config::DB_NAME . ";charset=utf8",
                Config::DB_USER,
                Config::DB_PASSWORD
            );
            // Throw an Exception when an error occurs
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }

        return $db;
    }


    /**
     *
     * Get collection of all data from the table for particular model
     *
     * Each model object data from database is passed as argument to Model constructor
     * processed in the init method, then collected in $this->collection array
     *
     * @return array
     */
    function all(){
        try {
            $q = "SELECT * FROM $this->table";
            $con = self::getDB();
            $query = $con->prepare($q);
            $query->execute(array($this->table));

            $models = $query->fetchAll(PDO::FETCH_OBJ);
            foreach ($models as $model){

                $this->collect($model);
            }
            return Collection::getCollection();
        }catch (PDOException $e){
            print $e->getMessage();
        }
    }


    function some($limit){
        try {
            $q = "SELECT * FROM $this->table LIMIT $limit";
            $con = self::getDB();
            $query = $con->prepare($q);
            $query->execute(array($this->table));

            $models = $query->fetchAll(PDO::FETCH_CLASS, $this->model);
            //FETCH_CLASS returns class models but methods values are not initialized
            foreach ($models as $model){

                $this->collect($model);
            }
            return Collection::getCollection();
        }catch (PDOException $e){
            print $e->getMessage();
        }
    }


    /**
     * Create collection of objects
     *
     * Model is been instantiated and initialized by provided @param $model
     * then added to collection array
     *
     * @param $model
     */
    protected function collect($model):void
    {
        $class = $this->model;
        $model = new $class($model);
        if (is_object($model) && $model instanceof $class) Collection::add($model);
    }


    /**
     * Return instance of model by given ID
     *
     * @param $id
     * @return $this
     */
    function find($id){
        $con = self::getDB();
        $q = "SELECT * FROM $this->table WHERE id = ?";
        $query = $con->prepare($q);
        $query->execute(array($id));

        $model = $query->fetch(PDO::FETCH_OBJ);
        $this->init($model);
        return $this;
    }

    function where($item, $value){
        $con = self::getDB();
        $item = filter_var($item, FILTER_SANITIZE_STRING);
        $q = "SELECT * FROM $this->table WHERE $item = ?";
        $query = $con->prepare($q);
        $query->execute(array($value));

        $models = $query->fetchAll(PDO::FETCH_OBJ);
        foreach ($models as $model){

            $this->collect($model);
        }
        return Collection::getCollection();
    }

    public function toArray(){
        return (array) $this;
    }

}