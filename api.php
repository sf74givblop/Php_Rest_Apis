<?php
    
	/* 
		This is an example class script proceeding secured API
		To use this class you should keep same as query string and function name
		Ex: If the query string value rquest=delete_user Access modifiers doesn't matter but function should be
		     function delete_user(){
				 You code goes here
			 }
		Class will execute the function dynamically;
		
		usage :
		
		    $object->response(output_data, status_code);
			$object->_request	- to get santinized input 	
			
			Was: output_data : JSON (I am using)
                        Is: output_date : text/html
         
			status_code : Send status message for headers
			
		Add This extension for localhost checking :
			Chrome Extension : Advanced REST client Application
			URL : https://chrome.google.com/webstore/detail/hgmloofddffdnphfgcellkdfbfbjeloo
		
		I used the below table for demo purpose.
		
		CREATE TABLE IF NOT EXISTS `users` (
		  `user_id` int(11) NOT NULL AUTO_INCREMENT,
		  `user_fullname` varchar(25) NOT NULL,
		  `user_email` varchar(50) NOT NULL,
		  `user_password` varchar(50) NOT NULL,
		  `user_status` tinyint(1) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`user_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
 	*/

        echo "<!DOCTYPE html>
              <html>
                <head>     
                    <meta charset=\"UTF-8\">        
                    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0\"/>
                    <meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\">
                    <meta content=\"IE=edge,chrome=1\" http-equiv=\"X-UA-Compatible\">
                    <meta content=\"no-cache,no-store,must-revalidate,max-age=-1\" http-equiv=\"Cache-Control\">
                    <meta content=\"no-cache,no-store\" http-equiv=\"Pragma\">
                    <meta content=\"-1\" http-equiv=\"Expires\">
                    <meta content=\"Serge M Frezier\" name=\"author\">
                    <meta content=\"INDEX,FOLLOW\" name=\"robots\">
                    <meta content=\"\" name=\"keywords\">
                    <meta content=\"\" name=\"description\">
                    <!--<meta name=\"mobile-web-app-capable\" content=\"yes\">-->
                    <style>
                        table#taMain {border: 5px solid grey;border-collapse: collapse;}
                        table#taMain > thead {color:white; font-weight:bold}
                        table#taMain > thead > tr {background-color:orange;}
                        table#taMain > thead > tr > td {padding:4px; width:100px!important; max-width:100px!important; min-width:100px!important}
                        table#taMain > tbody {color:green;}
                        table#taMain > tbody > tr > td {border: 0.5px solid orange;}
                        table#taMain > tfoot {color:yellow;}
                        input.button{
                            border: 1px solid #00ff00;
                            -webkit-box-shadow: 0px 0px 3px #00ff00;
                            -moz-box-shadow: 0px 0px 3px #00ff00;
                            box-shadow: 0px 0px 3px #00ff00;
                        }
                    </style>
                </head>
                <body>
                    <h1>PHP REST API with PDO connection to MYSQL</h1>
                    <h2>To test LOGIN load the following route: http://localhost:81/Php_Rest_Apis/login/</h2>
                    Use the Login button to do POST . Using the Enter Key give a GET and 406
                    <br />
                    <div id=\"login\">
                        <h3>Login</h3>
                        <form method=\"post\" action=\"\" name=\"login\">
                            <label>Email</label><br />
                            <input type=\"text\" name=\"email\" value=\"\" autocomplete=\"off\" /><br />
                            <label>Password</label><br />
                            <input type=\"password\" name=\"password\" value=\"\" autocomplete=\"off\"/><br />
                            <div class=\"errorMsg\"><?php echo Error number one; ?></div>
                            <input type=\"submit\" class=\"button\" name=\"loginSubmit\" value=\"Login\">
                        </form>
                    </div>
                    <br />
                    <br />";

	
	require_once("Rest.inc.php");
	
	class API extends REST {
	
		public $data = "";
		//That should be in another file
		const DB_SERVER = "localhost";
                const DB_PORT = "3306";
		const DB_USER = "root";
		const DB_PASSWORD = "";
		const DB = "dbserge";
                const TB = "users";
		
		private $db = NULL;
	
		public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnect();					// Initiate Database connection
		}
                
                //$this here refers to this scope ex $this->disconn
                //Start - a couple of setters and getters I will need for my PDO connection
                protected $curDbName;
                protected $curHandle;
                protected $curState;
                protected $new_conn;
                protected $connected;

                 public function _setConn($new_conn){
                     $this->conn=$new_conn;
                 }
                 public function getConn(){
                     return $this->conn;    
                 }

                 public function _setConnState($connected){  /* 1  or 0 */
                     $this->areWeConnected=$connected;
                 }
                 public function getConnState(){   /* 1  or 0 */
                     return $this->areWeConnected;    
                 }                
		//End - a couple of setters and getters I will need for my PDO connection
		
		/*
		 *  Database connection 
		*/
		private function dbConnect(){
                    /*
			$this->db = mysql_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD);
			if($this->db)
				mysql_select_db(self::DB,$this->db);
                     */
                            /* Connect to a MySQL database using driver invocation */
                    try{
                        $curHandle = new PDO('mysql:host='.self::DB_SERVER.';port='.self::DB_PORT.';dbname='.self::DB.';charset=utf8',self::DB_USER,self::DB_PASSWORD,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                        //or $curHandle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $attributes = array("AUTOCOMMIT", "ERRMODE", "CASE", "CLIENT_VERSION", "CONNECTION_STATUS",  "SERVER_INFO", "SERVER_VERSION");
                        foreach ($attributes as $val) {
                            echo "PDO::ATTR_$val: ".$curHandle->getAttribute(constant("PDO::ATTR_$val")) . "<br />";
                        }
                        echo '<div id="conn_MessageGreen" style="color:green;font-weight:bold;">Success: A proper connection to MySQL was made!</div>';
                        $this->_setConnState(1);
                        $curState=1;
                        $this->_setConn($curHandle);
                        ///Verifying that we pull something
                        //$stmt = $curHandle->query('SELECT * FROM cds');
                        //$row_count = $stmt->rowCount();
                        //echo $row_count.' rows selected';   //returns 8 rows      
                    }catch(PDOException $pe){
                        //echo $pe->getMessage().'<br /><br />';
                        //echo $pe->getCode().'<br /><br />';
                        //echo $pe->getLine().'<br /><br />';
                        //echo $pe->getFile().'<br /><br />';
                        $errMsg='';
                        $errMsg='Message='.$pe->getMessage().'. Code='.$pe->getCode().'. At Line='.$pe->getLine();
                        echo '<div id="conn_MessageRed" style="color:red;font-weight:bold;">ERROR 001. '.$errMsg.'. We are NOT connected. Connect failed.</div>';
                        $this->_setConnState(0);
                        $curState=0;
                        echo 'After CONNECTION ERROR, $curConn->getConnState() is: '.$this->getConnState().'<br>';
                        die();
                    } 
                    echo 'After we tried to connect, $this->getConnState() is: '.$this->getConnState().'<br>';
                    echo 'After we tried to connect, $curState is: '.$curState.'<br>';
		}
                
                /*
                 * Database deconnection is public as called outside class
                 */
                public function dbDisconnect(){
                    echo '<pre>'.'1 - function disconnect starts '.'</pre>';
                    echo '<pre>'.'2 - Current connState(should be one): '.$this->getConnState().'</pre>';
                    //We reset everything
                    $this->_setConnState(0);
                    $this->_setConn(NULL);  //that should suffice in fact
                    $curState=0;
                    echo '<div id="deconn_MessageGreen" style="color:green;font-weight:bold;">Now we are correctly disconnected</div>'; 
                    echo 'After we disconnected, $this->getConnState() is: '.$this->getConnState().'<br>';
                    echo 'After we disconnected, $curState is: '.$curState.'<br>';
                }
		
		/*
		 * Public method for access api.
		 * This method dynmically call the method based on the query string
		 *
		 */
		public function processApi(){
                        //rquest (without e)is not an error. Look at the .htacces file
			$func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
			if((int)method_exists($this,$func) > 0)
				$this->$func();
			else
				$this->response('',404);				// If the method not exist with in this class, response would be "Page not found".
		}
		
		/* 
		 *	Simple login API
		 *  Login must be POST method
		 *  email : <USER EMAIL>
		 *  pwd : <USER PASSWORD>
		 */
		
		private function login(){
			// Cross validation if the request method is POST else it will return "Not Acceptable" status
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			
                        //The Email and Password are the 2 credentials . We do not use the full name.
			$email = $this->_request['email'];
                        echo "<br />Debug. Email is: ".$email."<br />";
			$password = $this->_request['password'];
			echo "Debug. Password is: ".$password."<br />";
                        
			// Input validations
			if(!empty($email) and !empty($password)){
				if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                                    try{
                                        $ccc=$this->getConn();  /* using the handle */
                                        $qInput="SELECT user_id, user_fullname, user_email FROM users WHERE user_email = '".$email."' AND user_password = '".$password."' LIMIT 1";
                                        echo "Debug - ".$qInput."<br />";
                                        $sqlInput = $ccc->prepare($qInput); 
                                        $sqlInput->execute();
                                        $row_count = $sqlInput->rowCount();
                                        echo "Debug - Row(s) count: ".$row_count."<br />";
                                        if(($row_count!=NULL) && ($row_count > 0) ){
                                            //Single quotes here. it is easier with HTML markup
                                            echo '<div id="SqlInput_MessageGreen" style="color:green;font-weight:bold;">We found: '.$row_count.' row(s). Find the JSON output below:</div>'; 
                                            //$result=$sqlInput->fetch(PDO::FETCH_BOTH);
                                            $resultInput=$sqlInput->fetch(PDO::FETCH_ASSOC);
                                            $this->response($this->json($resultInput), 200);                                            
                                        }else{
                                            //Single quotes here. it is easier with HTML markup
                                            echo '<div id="SqlInput_MessageRed" style="color:red;font-weight:bold;">No row(s) found. Please verify the credentials you entered.</div>';
                                            $this->response('', 204);	// If no records "No Content" status
                                        }
                                        

                                    } catch (Exception $ex) {
                                        $this->response('', 400);	// If no records "No Content" status
                                    }
				}
			}
			
			// If invalid inputs "Bad Request" status message and reason
			$error = array('status' => "Failed", "msg" => "Invalid Email address or Password");
			$this->response($this->json($error), 400);
		}
		
		private function users(){	
			// Cross validation if the request method is GET else it will return "Not Acceptable" status
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$sql = mysql_query("SELECT user_id, user_fullname, user_email FROM users WHERE user_status = 1", $this->db);
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
					$result[] = $rlt;
				}
				// If success everythig is good send header as "OK" and return list of users in JSON format
				$this->response($this->json($result), 200);
			}
			$this->response('',204);	// If no records "No Content" status
		}
		
		private function deleteUser(){
			// Cross validation if the request method is DELETE else it will return "Not Acceptable" status
			if($this->get_request_method() != "DELETE"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			if($id > 0){				
				mysql_query("DELETE FROM users WHERE user_id = $id");
				$success = array('status' => "Success", "msg" => "Successfully one record deleted.");
				$this->response($this->json($success),200);
			}else
				$this->response('',204);	// If no records "No Content" status
		}
		
		/*
		 *	Encode array into JSON
		*/
		private function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
	}
	
	// Initiiate Library
	
	$api = new API;
	$api->processApi();
        
        //we do not forget to disconnect
        //$api->dbDisconnect();
        
        echo "</body></html>";