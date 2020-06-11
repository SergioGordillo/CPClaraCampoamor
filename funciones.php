<?php

function alumnoEnLaSesion($array, $Dni){ //Función que me permite saber si un alumno (del array del checkbox DNIs) está en la sesión o no
    
    foreach ($array as $key => $alumno) {
        
        if($alumno->__GET("Dni") == $Dni){ //Comparo el alumno del array (de las sesiones) con el alumno del texto
            return true;
        } //Si coincide devuelvo true y sino devuelvo false
    }
    return false;
    
}

?>