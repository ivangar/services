<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/FirePHPCore/ChromePhp.php');
/**
 * The CompressDir class
 */
class CompressDir {
    /**
     * Main directory to upload files
     */
    public $output_dir = "../image/";

    /**
     * Temporary directory to upload files before submitting the event
     */
    public $zip_file = "../images.zip";

    /**
     * String to contain error messages
     */
    public $message = '';


    /**
     * Constructor initializes database
     */
    public function __construct()
    {
        $this->executeCompression();
    }

    public function executeCompression()
    {   
        $this->startSession();

        if($this->checkFileExists()){ 
            unlink($this->zip_file);
        }

        $this->compressDir();
        
        echo $this->message;

    }

    private function startSession()
    {
        session_start();
    }

    private function compressDir()
    {

        if ($handle = opendir($this->output_dir))  
        {
            $zip = new ZipArchive();

            if ($zip->open($this->zip_file, ZIPARCHIVE::CREATE)!==TRUE) 
            {   
                $this->setMessage("Error. The zip folder could not be created.");
                return false;
            }

            while (false !== ($file = readdir($handle))) 
            {   
                if($file == "." || $file == ".." || $file == ".DS_Store") continue;
                $zip->addFile($this->output_dir.'/'.$file);
            }

            if(!$zip->status){
                $this->setMessage("access");
            }
            else{$this->setMessage("Error. The ZipArchive status returned is $zip->status.");}

            closedir($handle);
            $zip->close();

            return true;
        }

        else{
            $this->setMessage("Error. The images handler could not be opened.");
            return false;
        } 

    }

    public function checkFileExists(){

        if(file_exists($this->zip_file)){ 
            return true;
        }  

        else return false;

    }

    public function setMessage($message){
        $this->message = "$message";
    }

}

$upload = new CompressDir();

?>