<?php
if(!isset($_SESSION)){ session_start(); }  
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/php/connect_db.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/php/PasswordHash.php");

define("CREATED", 201);     //Created HTTP Response Code
define("BAD_REQUEST", 400); //BAD REQUEST HTTP Response Code
define("OK", 200);          //OK HTTP Response Code
define("ACCEPTED", 202);     //Created HTTP Response Code
define("DELIMITER", "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------");

function createDatabaseConnection()
{
    try {
        $con = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset='. DB_ENCODING, DB_USER, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

    } catch (PDOException $e) {
        $con = "PDO database connection problem: " . $e->getMessage();
    } catch (Exception $e) {
        $con = "General problem: " . $e->getMessage();
    }

    return $con;
}

class ProcessHTTP
{
    /**
     * @var object Database connection
     */
    public $temp = null;

    /**
     * @var object Database connection
     */
    public $message = array();

    /**
     * @var object Database connection
     */
    public $db_connection = null;

    /**
     * @var bool Login status of user
     */
    public $user_is_logged_in = false;

    /**
     * @var bool Login status of user
     */
    public $agreement = false;

    /**
     * @var array containing user info sent
     */
    public $user_data = array();


    /**
     * @var bool Login status of user
     */
    public $hash = "";

    /**
     * @var bool Login status of user
     */
    public $salt = "";

    /**
     * @var object Database connection
     */
    public $status_code = null;

    /**
     * @var object Database connection
     */
    public $status_phrase = null;

    /**
     * Does necessary checks for PHP version and PHP password compatibility library and runs the application
     */
    public function __construct()
    {
    	$this->db_connection = createDatabaseConnection(); // get database connection credentials
    }


    public function parseHTTPRequest($data)
    {
        $null = NULL;

        //Validate array
        if(!is_array($data) || empty($data) || (count($data)!=12) ){
            $this->status_code = BAD_REQUEST;
            $this->status_phrase = "Bad request";
            $this->message["error"] = "malformed json array";
            return false;
        }

        //Validate empty or null fields
        if(!$this->validateCompulsoryFields($data)){
            $this->status_code = BAD_REQUEST;
            $this->status_phrase = "Bad request";
            return false;
        }

        /* VALIDATE DATA TYPE*/ 
        if(!$this->validateTypes($data)){
            $this->status_code = BAD_REQUEST;
            $this->status_phrase = "Bad request";
            return false;
        }

    	$this->user_data['first_name'] = $data['first_name'];
    	$this->user_data['last_name'] = $data['last_name'];
    	$this->user_data['email'] = $data['email'];
    	$this->user_data['password'] = $data['password'];
    	$this->user_data['country'] = (!isset($data['country']) || empty($data['country'])) ? $null : $data['country'];
    	$this->user_data['province'] = (!isset($data['province']) || empty($data['province'])) ? $null : $data['province'];
    	$this->user_data['postal_code'] = (!isset($data['postal_code']) || empty($data['postal_code'])) ? $null : $data['postal_code'];
    	$this->user_data['profession'] = (!isset($data['profession']) || empty($data['profession'])) ? $null : $data['profession'];
    	$this->user_data['specialty'] = (!isset($data['specialty']) || empty($data['specialty'])) ? $null : $data['specialty'];
    	$this->user_data['language'] = (!isset($data['language']) || empty($data['language'])) ? $null : $data['language'];
    	$this->user_data['matricule'] = (!isset($data['matricule']) || empty($data['matricule'])) ? 0 : $data['matricule'];
    	$this->agreement = $data['sta_agreement'];

    	return true;
    }

    public function validateCompulsoryFields($array)
    {
        $state = true;

        foreach ($array as $key => $value) {
            
            switch ($key) {
                case "first_name":
                case "last_name":
                case "email":
                case "password":
                    $state = $this->validate($value);
                    if(!$state) $this->message["error"] = "a compulsory field is missing";
                    break;
            }

            if(!$state) break;
        }

        return $state;
    }

    public function validate($value)
    {
        if(!isset($value) || empty($value)){
            return false;
        }
        
        else return true;
    }  

    public function validateTypes($array)
    {
        $state = true;

        foreach ($array as $key => $value) {
            
            switch ($key) {
                case "first_name":
                case "last_name":
                case "email":
                case "password":
                case "country":
                case "province":
                case "profession":
                case "specialty":
                    $state = is_string($value);
                    if(!$state) $this->message["error"] = "The data type of the variable '$key' does not correspond to String()";
                    break;
                case "sta_agreement":
                    $state = is_bool($value);
                    if(!$state) $this->message["error"] = "The type of the variable '$key' does not correspond to Bool()";
                    break;
            }

            if(!$state) break;
        }

        return $state;
    }

    public function getResponseCode()
    {
        return $this->status_code;
    }  

    public function getStatusPhrase()
    {
        return $this->status_phrase;
    }  

    //Checks field existance in the Database. If it exists it returns false, else it means it is not yet in the database and returns true
    public function processRequest()
    {
	  	if(!$this->agreement){
            $this->status_phrase = "Accepted";
            $this->status_code = ACCEPTED;
            $this->message["error"] = "terms of agreement not checked";
	  		return false;
	  	}

	  	else{
            if($this->IsFieldUnique($this->user_data['email'])){
                if($this->saveToDatabase()) {
                    $this->status_phrase = "Created";
                    $this->status_code = CREATED;
                    return true;
                }
            }
	  		

            else{
                $this->status_phrase = "Ok";
                $this->status_code = OK;
                $this->message["notification"] = "user already exists in Database";
                return true;
            } 
	  	}
    }

        //This function does all database proces: login into DB, ensures user DB table exists, checks unique email, inserts fields
    public function saveToDatabase()
    {
   		    $this->CallHashFunction($this->user_data['password']);
            $language = $this->ParseLanguage($this->user_data['language']);
	        $pass = $this->hash;
	        $salt = $this->salt;
	        $active = 1;
	        
			$insert_query = "INSERT INTO doctors (first_name, last_name, email, country, province, password, hash_salt, postal_code, profession, specialty, language, active, registration_date, last_visit, matricule, merck) VALUES (:first_name,:last_name,:email,:country,:province,:password,:hash_salt,:postal_code,:profession,:specialty,:language,:active, NOW(), NOW(),:matricule, true)"; 
			
			$stmt = $this->db_connection->prepare($insert_query);
			$stmt->execute(array(':first_name'=>$this->user_data['first_name'],
		 	                  ':last_name'=>$this->user_data['last_name'],
							  ':email'=>$this->user_data['email'],
							  ':country'=>$this->user_data['country'],
							  ':province'=>$this->user_data['province'],
							  ':password'=>$pass,
							  ':hash_salt'=>$salt,
							  ':postal_code'=>$this->user_data['postal_code'],
							  ':profession'=>$this->user_data['profession'],
							  ':specialty'=>$this->user_data['specialty'],
							  ':language'=>$language,
							  ':active'=>$active,
							  ':matricule'=>$this->user_data['matricule']
							  ));
			$this->message["notification"] = "registration was successful";

			return true;

    }

    //Checks field existance in the Database. If it exists it returns false, else it means it is not yet in the database and returns true
    public function IsFieldUnique($email)
    {
	  	$sql = "SELECT COUNT(*) FROM (SELECT * FROM doctors WHERE email = :email) AS subquery";

	    $query = $this->db_connection->prepare($sql);
	    $query->bindValue(':email', $email);
	    $query->execute();

        while($result_row = $query->fetch() ){
	        	if($result_row[0] == 0) return true;
	        	else{
                    $updateMerck = "UPDATE doctors SET merck = true WHERE email = :email";
                    $query = $this->db_connection->prepare($updateMerck);
                    $query->bindValue(':email', $email);
                    $query->execute();
                    return false;
                }
        }

		return false;   
    }

    public function CallHashFunction($raw_password){
        $Passhash = new PasswordHash();
        $Passhash->SetPassword($raw_password);
        $this->salt = $Passhash->GetSalt();
        $this->hash = $Passhash->GetHash();
    }

    public function ValidateHashSalt($pass, $hash, $salt){
        $Passhash = new PasswordHash();
        $hashArray = $Passhash->Create_Custom_Hash($hash, $salt);
        if($Passhash->validate_password($pass, $hashArray)){
            return true;
        }

        else {
        	$this->message["notification"] = " password does not match dxLink Database record";
        	return false;
        }
    }

    public function ParseLanguage($language){
        $parsedLang = '';
        
        if(!isset($language) || empty($language)){ return $language; }
        (strcmp($language,'EN') == 0) ? $parsedLang = "English" : $parsedLang = "French";
        return $parsedLang;

    }

    public function Close_DB_connection(){
    	$this->db_connection = null;
	}

	public function getMessage(){
		return $this->message;
	}
}

?>