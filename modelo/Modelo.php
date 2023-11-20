<?php



/* *******************************************************************************************
 
 * CLASE Caracteristica

 * ***************************************************************************************** */
class Modelo {

	// Atributos para conectar a la base de datos
	private $host = 'localhost';
	//private $dbname = 'relatosapp_dev';
	private $dbname = 'relatosapp_test';
	private $puerto = '5432';	

	// usuario de conexión con el servidor de BD
	private $user = 'postgres';
	private $password = '12345678';

	// Otros atributos
	public $conn; // conexión a la base de datos
	protected $N;
	protected $limitar = true; // si está a true se limitarán los resultados del listado a N. 



	/* *******************************************************************************************
	 * CONSTRUCTOR
	 * ***************************************************************************************** */
	function __construct() {
		$this->crearConexion();
		$this->N = 10;
	}


	/* *******************************************************************************************
	 * METODOS PRIVADOS
	 * ***************************************************************************************** */	
	private function crearConexion() {

		// Conexión a la base de datos
		$CadenaConexion =
			"host=".$this->host.
			" user=".$this->user.
			" password=".$this->password.
			" dbname=".$this->dbname.
			" port=".$this->puerto;	

			$this->conn = pg_connect($CadenaConexion);
	}



	/* *******************************************************************************************
	 * METODOS PRIVADOS
	 * ***************************************************************************************** */	
	public function limitar($booLimitar) {
		$this->limitar = $booLimitar;
	}

		
}

?>