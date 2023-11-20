<?php

include_once ("Modelo.php");

/* *******************************************************************************************
 
 * CLASE Usuario

 * ***************************************************************************************** */

class Usuario extends Modelo  {

	private $id;
	private $login;
	private $password;


	/* *******************************************************************************************
	 * CONSTRUCTOR
	 * ***************************************************************************************** */

	function __construct($login) {
		parent::__construct();

		$this->id = 0;

		// se carga el usuario
		$this->cargar($login);
		
	}


	/* *******************************************************************************************
	 * METODOS PRIVADOS
	 * ***************************************************************************************** */	
	
	private function cargar($login) {

		// cadena sql para realizar la carga
		$sql = "select id, login, password from usuario where login like '".$login."' ";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// si se ha encontrado el usuario se cargan los valores
		if (pg_num_rows($query)) {
		
			// obteniendo los valores
			$arrCampos = pg_fetch_Array($query);
			$this->id = $arrCampos['id'];
			$this->login = $arrCampos['login'];
			$this->password = $arrCampos['password'];
		}
	}
		

	/* *******************************************************************************************
	 * METODOS PUBLICOS
	 * ***************************************************************************************** */	

	
	/* Métodos getters y setters */

	public function getId() {
		return $this->id;
	}

	public function getLogin() {
		return $this->login;
	}

	public function getPassword() {
		return $this->password;
	}


	public function setId($id) {
		$this->id = $id;
	}

	public function setLogin($login) {
		$this->login = $login;
	}	

	public function setPassword($password) {
		$this->password = $password;
	}	

}

?>