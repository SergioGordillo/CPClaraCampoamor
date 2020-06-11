<?php

class Alumno {
    
    //Atributos de la clase
     private $Dni;
     private $Nombre;
     private $Apellido1;
     private $Apellido2;
     private $Edad;

    //Creo los getters y setters. Por cómo voy a hacer el programa, no necesito constructor.
	 public function __GET($propiedad)
	 {
		 return $this->$propiedad;
	 }
	 public function __SET($propiedad,$valor)
	 {
		 $this->$propiedad=$valor;
	 }
		
}

?>