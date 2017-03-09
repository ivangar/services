<?php
if(!isset($_SESSION)){ session_start(); }
require_once($_SERVER['DOCUMENT_ROOT'] . '/fr/inc/config/config_dxlink_env.php');
//require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/config/constants.php");

define('HOST', 'localhost');
define('DATABASE', 'dxlink_local_db');
define('USER', 'dxlink');
define('PWD', 'sRPzlNHns3x2');
define('ENCODING', 'utf8');

function DB_Connect()
{
    try {
        $con = new PDO('mysql:host=' . HOST . ';dbname=' . DATABASE . ';charset='. ENCODING, USER, PWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

    } catch (PDOException $e) {
        $con = "PDO database connection problem: " . $e->getMessage();
    } catch (Exception $e) {
        $con = "General problem: " . $e->getMessage();
    }

    return $con;
}

class Login
{
    /**
     * @var object Database connection
     */
    public $temp = null;

    /**
     * @var object Database connection
     */
    public $db_connection = null;

    /**
     * @var bool Login status of user
     */
    public $email = '';

    /**
     * @var bool Login status of user
     */
    public $user_exists = false;

    /**
     * @var object Database connection
     */
    public $doctor_id = null;

    /**
     * @var object Database connection
     */
    public $message = null;

    /**
     * @var random key
     */
    public $rand_key = 'cb4bd824f0d9c2823dc25349e058dfe858873caa52e6708bc791760dd2a3a068336aca08238c67d69b6434a4864366f5ce9a32f57bf5b710043e019fd3d1a8d268ceec2f3f556d597f339320d17eb91c9592f15a0555e15b298ab57055de8fce6df5a70bd967fc15d5bcaef6f24008e3eeb7a5a0b79c081c1bac124535096c88';


    public function __construct()
    {
        $this->db_connection = DB_Connect(); // get database connection credentials
    }

    public function setEmail($email)
    {

        //Validate empty or null fields
        if(!$this->validate($email)){
            $this->message = "Field is empty";
            return false;
        }
        
        $this->email = $email;

        return true;
    }

    public function processLogin(){
        if($this->userExists()){
            if($this->GetUserFromEmail()){

                //make functions to set the sessions
                $this->user_exists = true;
            }
        }
    }

    public function validate($value)
    {
        if(!isset($value) || empty($value)){
            return false;
        }
        
        else return true;
    }  

    //Checks field existance in the Database. If it exists it returns false, else it means it is not yet in the database and returns true
    public function userExists()
    {
        $sql = "SELECT COUNT(*) FROM (SELECT * FROM doctors WHERE email = :email) AS subquery";

        $query = $this->db_connection->prepare($sql);
        $query->bindValue(':email', $this->email);
        $query->execute();

        while($result_row = $query->fetch() ){
                if($result_row[0] == 1) return true;
                else return false;
        }

        return false;   
    }

    public function GetLoginSessionVar()
    {
        $retvar = md5($this->rand_key);
        $retvar = 'usr_'.substr($retvar,0,10);
        return $retvar;
    }

    public function GetUserFromEmail()
    {
        $sql = "SELECT * FROM doctors WHERE email = :email";

        $query = $this->db_connection->prepare($sql);
        $query->bindValue(':email', $this->email);
        $query->execute();

        while($result_row = $query->fetch(PDO::FETCH_ASSOC) ){
                $this->doctor_id = $result_row['doctor_id'];
                $this->email = $result_row['email'];
                $isMerck = $result_row['merck'];
                if($this->validate($this->doctor_id) && $isMerck) {return true;}
                else return false; 
        }

    }

    public function getuserID(){
        return $this->doctor_id;
    }

    public function Close_DB_connection(){
        $this->db_connection = null;
    }

    public function printLogout(){
        $email = $this->email;
        $this->logout = '';
        $this->logout .= "<table cellpading='0' cellspacing='0'>
                            <tr>
                                <td style='padding:0 20px 20px 10px;margin0;'>
                                    <label style='align:middle;' ><span class='textrevised'>You are currently logged in as $email </span></label>
                                </td>
                                <td style='padding:0 20px 20px 10px;margin0;'>
                                    <button id='logout' type='button' name='Submit_logout' class=' form-submit-button-cool_grey_rounded'>Logout</button>
                                </td>
                            </tr>
                          </table> ";
        return $this->logout;
    }

}

if(isset($_POST['email']) && isset($_POST['merckConnect'])){
    if($_POST['merckConnect']){
        $email = $_POST['email'];
        $login = new Login();
        if($login->setEmail($email)) { $login->processLogin(); }
        if(!$login->user_exists)
        {   
            $url = FIXED_SSL_URL.$_SERVER['HTTP_HOST'].'/AccessDeny/deny_access.html';
            header('Location: ' . $url);
        }
        $logout = $login->printLogout();
        $login->Close_DB_connection();
    }

 
}
    
elseif(isset($_GET['email']) && isset($_GET['merckConnect'])){
    if($_GET['merckConnect']){
        $email = $_GET['email'];
        $login = new Login();
        if($login->setEmail($email)) { $login->processLogin(); }
        if(!$login->user_exists)
        {
            $url = FIXED_SSL_URL.$_SERVER['HTTP_HOST'].'/AccessDeny/deny_access.html';
            header('Location: ' . $url);
        }
        $logout = $login->printLogout();
        $login->Close_DB_connection();
    }
}

/*
else{
    $url = FIXED_SSL_URL.$_SERVER['HTTP_HOST'].'/AccessDeny/deny_access.html';
    header('Location: ' . $url);
}
*/

?>