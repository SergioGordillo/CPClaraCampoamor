<?php


class Conexion {

	private $con; //Es el atributo para conectar
	private $bbdd=""; //BBDD
	private $host="localhost"; //Servidor al que se conecta, localhost
	private $usu="root"; //Usuario 
	private $clave=""; //Contraseña
	public $datos; //Devolvera un array de filas con los datos de la consulta
	 
	 
     //El constructor recibira el nombre de la BBDD a conectar y realizara la conexión
	  
	 public function __construct($base) //Base es el nombre de la BBDD a la que me conecto y se le pasa por instancia cada vez que hago una conexión
	 {
	   
       try { //Esto es el código que luego va a hacer la conexión
		   $this->bbdd=$base;
           $this->con = new PDO("mysql:host=$this->host;dbname=$this->bbdd",$this->usu,$this->clave);
           $this->con->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
           $this->con->exec("set names utf8mb4");
          
           } 
	  catch(PDOException $e) //En caso de error salta la excepción
	       {  
             echo "  <p>Error: No puede conectarse con la base de datos.</p>\n\n";
             echo "  <p>Error: " . $e->getMessage() . "</p>\n";
             
          exit(); //y termina la ejecución
           }
	 
	 }
	 
    
	 public function ConsultaSimple($consulta,$param) //Para las consultas que no devuelvan datos (como un INSERT, UPDATE, DELETE)
	 {
		   
		    $sta=$this->con->prepare($consulta); //Este código te prepara la consulta		
		 
			if	(!$sta->execute($param) ) //Ejecuto la consulta preparada. Si la consulta no se ejecuta lo muestro en pantalla.
			{
			     echo " <p>Error en la consulta<p>\n";
            } 
				
	 }

	 public function Consulta($consulta,$param)  //Para las consultas que devuelven datos (SELECT)
	 {

		 $sta=$this->con->prepare($consulta); //Este código te prepara la consulta
		 
		 $this->datos=array(); //Limpiamos la variable datos entre consulta y consulta 
			
		 if	($sta->execute($param))  // Ejecuto la consulta    
		 {
			
			while( $fila=$sta->fetch()) //Asocia la variable fila con cada fila de la BBDD
			{ 
				
			  $this->datos[]=$fila;	//Voy metiendo las filas en el array $datos y ya
				
			}
			
		 }		
		 else //En caso de error lo muestro en pantalla
		 {

		   echo "  <p>Error en la consulta<p>\n";	
		 }	 
			
	  } 

	  public function Cerrar() //Cierro la conexión
	  {
		$this->con= null ;
		
		
	  }
	  
	 public function __destruct()   //Al liberar el objeto de la memoria cerramos la conexion
	  								
	 {
	    $this->Cerrar();
	 }

}

?>