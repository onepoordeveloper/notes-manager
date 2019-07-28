<?

class Database{
	
	private $DbHost;
	private $DbName;
	private $DbUser;
	private $DbPwd;
	private $DbType;	
	private $Conn;
	// private $ConnReport;
	static  $SelfInstance;

	/**
	 * Constructor of the Class
	 * This Will also set ADODB Connection Object in its Property
	 *
	 */
 	public function __construct(){  	
 		
	  	$this->DbHost= "localhost";
	  	$this->DbName="notes-manager";
	  	$this->DbUser="root";
	  	$this->DbPwd="";
        $this->DbType="mysqli";	
        $this->Conn = new PDO("mysql:host=$this->DbHost;dbname=$this->DbName", $this->DbUser, $this->DbPwd);
        // set the PDO error mode to exception
        $this->Conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		// $this->Conn = mysqli_connect($this->DbHost, $this->DbUser, $this->DbPwd, $this->DbName);
	}
  
	/**
	 * This function Check if there is an instance of this class already avaialable
	 * Then return ADODB connection Object from that instance.
	 * Otherwise try to create new instance and return ADODB connection
	 *
	 * @return ADODB Connection Object
	 */
	
	static function ConnectDb(){   
        if (!isset(self::$SelfInstance)) {
            $c = __CLASS__;
            self::$SelfInstance = new $c;           
        } // if
 		return self::$SelfInstance->Conn;
	}
	
	/**
	 * Desctruct of the database class
	 *
	 */
	public function __destruct(){
		unset($this->DbHost);
		unset($this->DbName);
		unset($this->DbUser);
		unset($this->DbPwd);
		unset($this->DbType);
		if (isset($this->Conn)){
			$this->Conn = null;
	    }

    }
}