<?php 
	class Conexion{
		public function Conectar(){
		
		define('host', '127.0.0.1');
		define('port', 1521);
		define('name', 'XE');
		define('user', 'Administrador');
		define('pass', 'Administrador');
		
		$bd_settings = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST= " . host . ")(PORT=" . port . "))(CONNECT_DATA=(SERVICE_NAME=" . name . ")))";		
		try {
			$bd = new PDO('oci:dbname='.$bd_settings, user, pass);
			$bd->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
			$bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			echo 'CONEXION EXITOSA';
			return $bd;
			} catch (Exception $e) {
				echo "ERROR DE CONEXION: ".$e->getMessage();
			}
		}
	}
?>