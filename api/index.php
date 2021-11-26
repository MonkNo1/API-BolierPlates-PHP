<?php
    error_reporting(E_ALL ^ E_DEPRECATED);
    require_once("REST.api.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/api/lib/Database.class.php");

    class API extends REST {

        public $data = "";

        private $db = NULL;

        public function __construct(){
            parent::__construct();               
            $this->db = Database::getConnection();                 
        }
        
        public function processApi(){
            $func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
            if((int)method_exists($this,$func) > 0)
                $this->$func();
            else
                $this->response('',400);                
        }

        /*************API SPACE START*******************/

        private function test(){
                $data = $this->json(getallheaders());
                $this->response($data,101);
        }

        /*************API SPACE END*********************/

        /*
            Encode array into JSON
        */
        private function json($data){
            if(is_array($data)){
                return json_encode($data, JSON_PRETTY_PRINT);
            } else {
                return "{}";
            }
        }

    }

    // Initiiate Library

    $api = new API;
    $api->processApi();
?>
