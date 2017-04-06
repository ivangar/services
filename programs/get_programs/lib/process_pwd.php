<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/php/PasswordHash.php");

class Password
{
    /**
     * @var string for System messages, likes errors, notices, etc.
     */
    public $feedback = "";

    /**
     * @var password from the POST array
     */
    private $pwd = "";

    /**
     * @var password hash (actual password is Merck_2017)
     */
    private $pwd_hash = "Snkpacn/Wz4R0549rHs23HlvdigvJnVA";

        /**
     * @var password salt
     */
    private $pwd_salt = "xPEYicoyGA3anq3zmZAv3j07fVJxdlMz";


    /**
     * class constructor
     */
    public function __construct()
    {
        $this->runApplication();
    }

    public function runApplication()
    {
        $this->doStartSession();
        $this->processRequest();
    }

    /**
     * Handles the flow of the login/logout process. According to the circumstances, a logout, a login with session
     * data or a login with post data will be performed
     */
    private function processRequest()
    {
        if(isset($_POST["login_submitted"])) {
            $this->processPassword();
            echo $this->feedback;
        }
    }

    private function doStartSession()
    {
        session_start();
    }

    private function processPassword()
    {
        if ($this->checkFormDataNotEmpty()) {
            if($this->validatePassword())
            {
                return true;
            } 
            
            else{
                return false;
            }
        }

        return false;
    }

    private function checkFormDataNotEmpty()
    {
        if (!empty($_POST['pwd']) ) {
            return true;
        }

        else{
          $this->feedback = "Password field is empty.";
        }

        // default return
        return false;
    }

    /**
     * Checks if user exits, if so: check if provided password matches the one in the database
     * @return bool User login success status
     */
    private function validatePassword()
    {
        $this->pwd = htmlspecialchars($_POST['pwd']);

        if($this->ValidateHashSalt($this->pwd, $this->pwd_hash, $this->pwd_salt))
        {   
            $_SESSION['access'] = true;
            unset($_POST);
            $this->feedback = 'access';
            return true;
        } else {
            $this->feedback = "Wrong password";
        }

        return false;
    }

    private function ValidateHashSalt($pass, $hash, $salt){
        $Passhash = new PasswordHash();
        $hashArray = $Passhash->Create_Custom_Hash($hash, $salt);
        if($Passhash->validate_password($pass, $hashArray)){
            return true;
        }

        else return false;
    }
}

if(isset($_POST["login_submitted"]) && !empty($_POST["login_submitted"]) )
{
    // run the application
    $application = new Password();
}
?>