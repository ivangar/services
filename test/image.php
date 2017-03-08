<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/FirePHPCore/FirePHP.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/FirePHPCore/fb.php');
ob_start();
if(!isset($_SESSION)){ session_start(); }  
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/php/connect_db.php");

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

class Image
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
     * Does necessary checks for PHP version and PHP password compatibility library and runs the application
     */
    public function __construct($data)
    {
    	$this->db_connection = createDatabaseConnection(); // get database connection credentials

    }


    //Checks field existance in the Database. If it exists it returns false, else it means it is not yet in the database and returns true
    public function displayImage()
    {   
        $program_id = 'T2DM_01_FR';

        $sql = "SELECT image FROM programs WHERE program_id = :program_id";

        $query = $this->db_connection->prepare($sql);
        $query->bindValue(':program_id', $program_id);
        $query->execute();

        while($result_row = $query->fetch(PDO::FETCH_ASSOC) ){
                $this->temp = $result_row['image'];  
                
                header("Content-type: image/jpeg");
                echo $result_row['image'];
        }

    }


    public function Close_DB_connection(){
    	$this->db_connection = null;
	}

	public function getMessage(){
		return $this->message;
	}
}

$image = new Image();
$img = $image->displayImage();
echo '<img height="250" width="280" src="data:image;base64, '.$img.' " >';

?>