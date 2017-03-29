<?php 
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

    $con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $con;
}


class Programs{

    /**
     * @var object Database connection
     */
    private $db_connection = null;

    /**
     * @var user currently connected
     */
    public $user = 0;

    /**
     * @var all table rows that need to be displayed
     */
    public $programs;


    /**
     * @var one compact object containing the events
     */
	public $program_thread;
    

    /**
     * Does necessary checks for PHP version and PHP password compatibility library and runs the application
     */
    public function __construct()
    {   
        $this->runApplication();
    }

    /**
     * This is basically the controller that handles the entire flow of the application.
     */
    public function runApplication()
    {
        // start the session, always needed!
        $this->doStartSession();
        $this->db_connection = createDatabaseConnection(); // get database connection credentials
    }

    /**
     * Simply starts the session.
     * It's cleaner to put this into a method than writing it directly into runApplication()
     */
    private function doStartSession()
    {
        session_start();
    }

	public function getRows() {

		$this->Get_Programs();
		$this->Print_Events();
		$this->Close_DB_connection();

	}

	public function Get_Programs(){

        $sql = "SELECT program_id, program_title, program_subtitle, program_description, language, authors, url, DATE_FORMAT(launch_date,'%W, %M %e, %Y') AS launch_date, DATE_FORMAT(expiration_date,'%W, %M %e, %Y') AS expiration_date FROM programs WHERE `sponsor` = 'Merck' ORDER BY DATE(expiration_date) ASC";
        $query = $this->db_connection->prepare($sql);
        $query->execute();

        while($result_row = $query->fetch(PDO::FETCH_ASSOC) ){
    		$program_id = $result_row['program_id'];
    		$title = $result_row['program_title'];
        	$subtitle = $result_row['program_subtitle'];  
			$description = $result_row['program_description'];  
			$language = $result_row['language'];
            $url = $result_row['url'];
            (!empty($result_row['authors'])) ? $authors = $result_row['authors'] : $authors = 'N/A';
            (!empty($result_row['launch_date'])) ? $launch_date = $result_row['launch_date'] : $launch_date = 'N/A';
            (!empty($result_row['expiration_date'])) ? $expiration_date = $result_row['expiration_date'] : $expiration_date = 'N/A';
			$this->Generate_rows($program_id, $title, $subtitle, $description, $language, $url, $authors, $launch_date, $expiration_date);
        }

		return true;

	}

 	public function setTableContent(){
 		$this->getRows();
 
		$content = "<div id='content'>
                          <table class='table table-striped table-hover' id='sortable'>
                            <thead>
                              <tr>
                                <th class='col-sm-1'>program #ID</th>
                                <th class='col-sm-1'>program title</th>
                                <th class='col-sm-2'>program subtitle</th>
                                <th class='col-sm-2'>program description</th>
                                <th class='col-sm-1'>language</th>
                                <th class='col-sm-1'>authors</th>
                                <th class='col-sm-1'>url</th>
                                <th class='col-sm-1'>launch date</th>
                                <th class='col-sm-1'>expiration date</th>
                                <th class='col-sm-1' style='padding-left: 25px;'>image</th>
                              </tr>
                            </thead>
                            <tbody>
                                 {$this->program_thread}
                            </tbody>
                          </table> 
                        </div>  ";

        echo $content;
 	}

	public function Generate_rows($program_id, $title, $subtitle, $description, $language, $url, $authors, $launch_date, $expiration_date){

            $this->programs .= "<tr >\n
                    <td>$program_id</td>\n
                    <td>$title</td>\n
                    <td>$subtitle</td>\n
                    <td>$description</td>\n
                    <td>$language</td>\n
                    <td>$url</td>\n
                    <td>$authors</td>\n
                    <td>$launch_date</td>\n
                    <td>$expiration_date</td>\n
                    <td><a href='#' class='btn btn-default' id='$program_id'>download</a></td>\n
                </tr>\n
                     ";

	}

	public function Clear_program_row(){
		$this->programs = '';
	}

	public function Print_Events(){
		if(empty($this->programs)) { return false; }
		$this->program_thread = $this->programs;

		$this->Clear_program_row(); //Clear all concatenated rows
	}

	private function Close_DB_connection(){
    	$this->db_connection = null;
	}

} //ends class

$programs = new Programs();

?>