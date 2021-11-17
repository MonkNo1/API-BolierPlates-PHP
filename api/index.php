<?php
    error_reporting(E_ALL ^ E_DEPRECATED);
    require_once("REST.api.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/api/lib/Database.class.php");

    class API extends REST {

        public $data = "";

        private $db = NULL;

        public function __construct(){
            parent::__construct();                // Init parent contructor
            $this->db = Database::getConnection();                    // Initiate Database connection
        }

        /*
         * Public method for access api.
         * This method dynmically call the method based on the query string
         *
         */
        public function processApi(){
            $func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
            if((int)method_exists($this,$func) > 0)
                $this->$func();
            else
                $this->response('',400);                // If the method not exist with in this class, response would be "Page not found".
        }

        /*************API SPACE START*******************/

        private function about(){

            if($this->get_request_method() != "POST"){
                $error = array('status' => 'WRONG_CALL', "msg" => "The type of call cannot be accepted by our servers.");
                $error = $this->json($error);
                $this->response($error,406);
            }
            $data = array('version' => '0.1', 'desc' => 'This API is created by Blovia Technologies Pvt. Ltd., for the public usage for accessing data about vehicles.');
            $data = $this->json($data);
            $this->response($data,200);

        }

        private function test(){
                $data = $this->json(getallheaders());
                $this->response($data,101);
        }

        private function server(){
            if($this->get_request_method() != "POST"){
                $data = $this->json($_SERVER);
                $this->response($data,200);
            }
            $data = [
                    "status" => "unauthorized"
            ];
            $data = $this->json($data);
            $this->response($data,401);
        }

        public function verifipls()
        {
            if($this->get_request_method() != "GET" and isset($this->_request['user']) and isset($this->_request['pass'])){
                $user = $this->_request['user'];
                $pass = $this->_request['pass'];
                if($user === "monk" and $pass === "isthework"){
                    $data = [
                        "status" => "Login success"
                    ];
                    $data = $this->json($data);
                    $this->response($data,200);
                }
                else{
                     $data = [
                         "status" => "Login Failed"
                     ];
                     $data = $this->json($data);
                     $this->response($data,400);
             }
         }
         $data = [
             "status" => "unauthorized"
         ];
         $data = $this->json($data);
         $this->response($data,401);
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