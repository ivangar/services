<?php
/**
 * The UploadFile class
 */
class UploadFile {
    /**
     * Main directory to upload files
     */
    public $output_dir = "../image/images/";

    /**
     * Temporary directory to upload files before submitting the event
     */
    public $temp_dirPath = "";    

    /**
     * Temporary directory to upload files before submitting the event
     */
    public $dirPath = "";    

    /**
     * String to contain error messages
     */
    public $message = '';

    /**
     * Array containing the file names to upload
     */
    public $files = array("COI", "honorarium", "signin", "evaluation");

    /**
     * Current file that is being uploaded
     */  
    public $file = '';

    /**
     * Subdirectory for each form
     */
    public $sub_dir = "";    


    /**
     * Constructor initializes database
     */
    public function __construct()
    {
        $this->executeUpload();
    }

    public function executeUpload()
    {   
        
        $this->startSession();
        $this->createImagesDir();

        echo $this->message;

        /*
        if(isset($_GET['update']) && $_GET['update']){
            if($this->makeFinalDir()){
                $this->temp_dirPath = $this->dirPath;  //in order to use the functions checkFileExists() and upload(), we need to set the temporary directory to the static dir
            }

            else{  echo json_encode($this->message); } //couldn't make the static directory
        }
        else{
            $this->makeTempDir();
        }

        if(!$this->checkFileExists()){
            $return = $this->upload();
            echo json_encode($return);
            if($_GET['update']){
                $modName = $_GET['moderatorName'];
                $date = date("F j, Y, g:i a");  
                $thisEventId = strtok($_GET['folderID'], '_');
                $thisModId = substr($_GET['folderID'], strpos($_GET['folderID'], "_") + 1);
                $body = "<h3>A new file has been uploaded to RepZone: </h3><h4>Moderator: {$modName} </h4><h4>Type: {$this->sub_dir}</h4><h4>Event ID: {$thisEventId}</h4><h4>Moderator ID: {$thisModId}</h4><h4>Path: {$this->dirPath}</h4><h4>Submitted on: $date</h4><h4>To view the event details, please login as the administrator with the following credentials:<br>site: <a href='https://dxlink.ca/programs/CCC_Symposium/rep_zone/login.html'>https://dxlink.ca/programs/CCC_Symposium/rep_zone/login.html</a><br>user: admin@sta.ca<br>password: sta_repzone</h4>";
                $transport = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail -t -i');

                $mailer = Swift_Mailer::newInstance($transport);

                $message = Swift_Message::newInstance('New File Uploaded to Repzone')
                ->setFrom(array('dxLink@sta.ca' => 'dxLink'))
                ->setTo(array('jamesk@sta.ca' => 'James', 'amandab@sta.ca' => 'Amanda'))
                ->setBody($body, 'text/html');

                $result = $mailer->send($message);
            
            } 
        }

        else{ echo json_encode($this->message); }
        */
    }

    private function startSession()
    {
        session_start();
    }


    private function createImagesDir()
    {

        if($this->checkDirExists()){ $this->recursiveRemoveDirectory(); }

        if($this->makeDir()){
            $this->setMessage('The directory images was created successfully!');
            return true;
        }
        else{
            $this->setMessage('The directory images could not be created');
            return false;
        }

    }

    /*
     * This function will create a folder structure like this: uploads/26_19/COI
    */
    private function makeDir()
    {
        return mkdir($this->output_dir, 0777, true);
    }

    public function setMessage($message){
        $this->message = "$message";
    }


    public function checkDirExists(){

        if (file_exists($this->output_dir) && is_dir($this->output_dir)) { 
            return true;
        } 

        else return false;

    }

    //remove the folder images and all the files within it
    function recursiveRemoveDirectory()
    {
        $directory = $this->output_dir;

        foreach(glob("{$directory}/*") as $file)
        {
            if(is_dir($file)) { 
                recursiveRemoveDirectory($file);
            } else {
                unlink($file);
            }
        }
        rmdir($directory);
    }

/* 

    private function makeTempDir()
    {
        //unset($_SESSION['temp_dir']);
        
        if($this->getTempDirSession()){
            $tmp_dir = $_SESSION['temp_dir'];
            $this->makeSubDir($tmp_dir);
            $this->temp_dirPath = $this->output_dir.$tmp_dir."/".$this->sub_dir."/";
        }

        else{
            $tmp_dir = substr(strrchr($this->file["tmp_name"], "/"), 1);
            $this->setTempDirSession($tmp_dir);
            $this->temp_dirPath = $this->output_dir.$tmp_dir;
            mkdir($this->temp_dirPath, 0777, true);   //This function will create a folder structure like this: uploads/phprCqHx3
            $this->makeSubDir($tmp_dir);
            $this->temp_dirPath .= "/".$this->sub_dir."/";            
        }
    }

    //This function will create a folder structure like this: uploads/phprCqHx3/COI
    private function makeSubDir($tmp_dir)
    {
        $sub_directory = $this->output_dir.$tmp_dir."/".$this->sub_dir."/";
        mkdir($sub_directory, 0777, true);
    }


    private function setTempDirSession($tmp_dir_name)
    {
        $_SESSION['temp_dir'] = $tmp_dir_name;
    }

    public function getTempDirSession()
    {
        if(isset($_SESSION['temp_dir']) && !empty($_SESSION['temp_dir'])){
            return true;
        }

        else return false;
    }

    public function upload()
    {

        if(isset($this->file))
        {
            $ret = array();
            $fileName = $this->file["name"];
            move_uploaded_file($this->file["tmp_name"],$this->temp_dirPath.$fileName);
            $ret[]= $fileName;
            return $ret;
         }

    }

    public function checkFileExists(){
        $file = $this->file["name"];
        $path = $this->temp_dirPath;
        $target_file = $path.$file;

        if (file_exists($target_file)) { 
            $this->setMessage('File already exists');
            return true; 
        } 
        else {return false;} 

    }




    */
}

$upload = new UploadFile();

?>