<?php

require_once("alumno.php");
require_once("classConexion.php");

class daoAlumno extends Conexion { //Esta clase hereda de Conexión.

    public function Insertar($alumno) //Función que me permite insertar alumno
    {  //Por un lado hago la consulta y por otro lado le paso los parámetros, que me los coge vía POST del objeto alumno que recibe como parámetro
        $consulta="insert into alumnos values (:Dni, 
                                                :Nombre,
                                                :Apellido1,
                                                :Apellido2,
                                                :Edad)";
                                      

        $param=array(":Dni"=>$alumno->__GET("Dni"),
                     ":Nombre"=>$alumno->__GET("Nombre"),
                     ":Apellido1"=>$alumno->__GET("Apellido1"),
                     ":Apellido2"=>$alumno->__GET("Apellido2"),
                     ":Edad"=>$alumno->__GET("Edad")
                     );     								
                              

        $this->ConsultaSimple($consulta,$param); //Ejecuto el Insert
        
                  
     }
               
    public function Actualizar($alumno) //Función que me permite actualizar alumnos
	           { //Por un lado hago la consulta y por otro lado le paso los parámetros, que me los coge vía GET del objeto alumno que recibe como parámetro
                            $consulta="update alumnos set  Dni=:Dni,
                                                        
                            Nombre=:Nombre,
                            Apellido1=:Apellido1,
                            Apellido2=:Apellido2,
                            Edad=:Edad
            where Dni=:Dni"; 					  

                            $param=array(":Dni"=>$alumno->__GET("Dni"),
                            ":Nombre"=>$alumno->__GET("Nombre"),
                            ":Apellido1"=>$alumno->__GET("Apellido1"),
                            ":Apellido2"=>$alumno->__GET("Apellido2"),
                            ":Edad"=>$alumno->__GET("Edad")
                            );    

                    $this->ConsultaSimple($consulta,$param); //Ejecuto el Actualizar
			   }


    public function Existe($alumno){ //Función que me permite ver si existe un alumno en la BBDD o no

				   
            $consulta="select * from alumnos where Dni=:Dni"; //Construyo la consulta SQL

            $param=array(":Dni"=>$alumno->__GET("Dni")); //Esta consulta sí lleva un parámetro, el DNI

            $this->Consulta($consulta,$param); //Ejecuto la consulta

            $existe=false;
          
            if (count($this->datos) > 0 )         //Si el DNI está en la BBDD
            {
               $fila=$this->datos[0];  //La columna solo revolveria una fila
               $existe=true;  //Y seteo a $existe el valor true
            }

            return $existe; //Devuelvo el valor

    }

}

?>