<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/FirePHPCore/ChromePhp.php');
/**
 * The UploadFile class
 */
class UploadFile {
    /**
     * Main directory to upload files
     */
    public $output_dir = "../image/";

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
    public $sub_dirs = array("images/", "images.zip");

    /**
     * Current file that is being uploaded
     */  
    public $file = ''; 


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
        
        if($this->createImagesDir()){
            $this->moveImage();
            $this->compressDir();
        }

        echo $this->message;

    }

    private function startSession()
    {
        session_start();
    }


    private function createImagesDir()
    {
        foreach ($this->sub_dirs as $key => $value) {
            $dir = $this->output_dir.$value;

            if($this->checkDirExists($key, $dir)){ 
                $this->$dirPath = $dir;
                $this->recursiveRemoveDirectory();
            }
            
        }

        $folder = $this->output_dir.$this->sub_dirs[0];

        if($this->makeDir($folder)){
            $this->setMessage('The directory images was created successfully!');
            return true;
        }
        else{
            $this->setMessage('The directory images could not be created');
            return false;
        }

    }

    private function makeDir($folder)
    {
        return mkdir($folder, 0777, true);
    }

    public function setMessage($message){
        $this->message = "$message";
    }


    public function checkDirExists($key, $dir){

        if($key){
            if(file_exists($dir)){ 
                return true;
            }  
        }

        elseif(!$key){
            if(file_exists($dir) && is_dir($dir)) { 
                return true;
            }             
        }

        else return false;

    }

    //remove the folder images and all the files within it
    private function recursiveRemoveDirectory()
    {
        $directory = $this->$dirPath;

        if(strcmp($directory, "../image/images.zip") == 0){
            unlink($directory);
        }

        else{
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

    }

    private function compressDir()
    {
        $images_dir = $this->output_dir.$this->sub_dirs[0];
        $zip_file = $this->output_dir.$this->sub_dirs[1];

        if ($handle = opendir($images_dir))  
        {
            $zip = new ZipArchive();

            if ($zip->open($zip_file, ZIPARCHIVE::CREATE)!==TRUE) 
            {   
                $this->setMessage("Error. The zip folder could not be created.");
                return false;
            }

            while (false !== ($file = readdir($handle))) 
            {   
                if($file == "." || $file == ".." || $file == ".DS_Store") continue;
                $zip->addFile($images_dir.'/'.$file);
            }

            if(!$zip->status){
                $this->setMessage("access");
            }
            else{$this->setMessage("Error. The zip folder could not be created.");}

            closedir($handle);
            $zip->close();

            return true;
        }

        else return false;

    }

    private function moveImage(){
        $original_dir = $this->output_dir;
        $new_dir = $this->output_dir.$this->sub_dirs[0];

        $files = scandir($original_dir);  //scan the original directory containing the images

        //move each image over the new folder
        foreach($files as $file)
        {
            if($file == "." || $file == ".." || $file == ".DS_Store")
                continue;

            if(!exif_imagetype($this->output_dir.$file)) {
                continue;
            }

            $old_file = $original_dir.$file;
            $newfile = $new_dir.$file;
            if (!copy($old_file, $newfile)) {
                continue;
            }            

        }

    }

}

$upload = new UploadFile();

?>