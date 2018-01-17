<?php
class VariablesEntorno{
	private $portal;
	private $host;
	private $user;
	private $pass;
	
	public function __construct(){
		$this->load();
	}

	public function load(){
		//var_dump($_SERVER['argv']);
		foreach ($_SERVER['argv'] as $argumentos){//RECORRO LOS ARGUMENTOS CON UN CICLO
		
			$pivote = explode(":", $argumentos);// PARTO EL ARGUMENTO RECORRIDO CON UN SPLIT : EJEMPLO "USUARIO:KRONOTIME" QUEDA UN ARREGLO DE DOS
			if(isset($pivote[0])){

				if (strtoupper(strtoupper($pivote[0])) == 'USER'){// ASIGNO MIS VARIABLE CON IF, SI EL PRIMER NIVEL DEL ARRGLO ES usuario ENTONCES ASIGNO EL VALOR A MI VARIABLE usuario
					if(isset ($pivote[1])){
						$this->user = $pivote[1];
					}
				}
			
				if (strtoupper(strtoupper($pivote[0])) == 'PASS'){// ASIGNO MIS VARIABLE CON IF, SI EL PRIMER NIVEL DEL ARRGLO ES usuario ENTONCES ASIGNO EL VALOR A MI VARIABLE usuario
					if(isset ($pivote[1])){
						$this->pass = $pivote[1];
					}
				}

				if (strtoupper(strtoupper($pivote[0])) == 'PORTAL'){// ASIGNO MIS VARIABLE CON IF, SI EL PRIMER NIVEL DEL ARRGLO ES usuario ENTONCES ASIGNO EL VALOR A MI VARIABLE usuario
					if(isset ($pivote[1])){
						$this->portal = $pivote[1];
					}
				}

				$this->host = "{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX";
			}
		}
	}

	public function getUser(){
		return $this->user;
	}

	public function getPass(){
		return $this->pass;
	}

	public function getPortal(){
		return $this->portal;
	}

	public function getHost(){
		return $this->host;
	}
}