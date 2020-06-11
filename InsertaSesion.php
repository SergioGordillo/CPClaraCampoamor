<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C.P. Clara Campoamor</title>
</head>
<!-- Empleando la BBDD y la tabla alumnos de la tarea anterior deberá hacer una página InsertaSesion.php con la siguiente funcionalidad:

a)La página tendrá un formulario que permita insertar alumnos con los mismos campos que en la tarea anterior.

b)El formulario tendrá ahora cuatro botones 'Insertar', que al pulsarlo guardará los datos del alumno introducido en una estructura de variables de sesión adecuada a los requerimientos. La diferencia con respecto al insertar de la tarea anterior, es que los datos que se recojan de ese alumno no irán directamente a la BBDD, sino que se guardarán en variables de sesión en una primera instancia.

Si el Dni de ese alumno ya existiera en la sesión, se actualizarían el resto de sus datos con los nuevos valores del resto de los campos del formulario(validando previamente que ninguno esté vacío).

Otro botón 'Mostrar' que el pulsarlo listará por pantalla en una tabla HTML los datos de los alumnos que se encuentren en ese momento almacenados en la sesión. Dicha tablá tendrá una columna con un Checkbox para cada alumno que al pulsarlo permita seleccionar ese registro.

Otro bótón 'Borrar' que al pulsarlo eliminará los alumnos seleccionados de la sesión, se tiene que permitir el poder seleccionar mas de una alumno para su borrado.

Un botón 'Volcar', que recogerá los datos de los alumnos que se encuentre en la sesión y los insertará en la tabla alumnos de la BBDD. A la hora de volcarlos en la tabla también tiene que tener en cuenta que, si ese alumno ya se encontrara en ella, deberá actualizar sus datos también en la BBDD. Una vez volcados los datos se eliminaran de la sesión. -->
<body>

<?php

    require_once "alumno.php";
    require_once "classConexion.php";
    require_once "DaoAlumno.php"; 
    require_once "funciones.php";

    session_start(); //Inicio la sesión

    if(!isset($_SESSION['alumnos'])){
        $_SESSION['alumnos']=array(); // Si la sesión no está iniciada, la seteo con un array 
    }

    if(isset($_POST['insertar'])){

        $Dni=$_POST['Dni']; //Recojo las variables enviadas vía POST
        $Nombre=$_POST['Nombre'];
        $Apellido1=$_POST['Apellido1'];
        $Apellido2=$_POST['Apellido2'];
        $Edad=$_POST['Edad'];

        if(empty($Dni)||empty($Nombre)||empty($Apellido1)||empty($Apellido2)||empty($Edad)){ //Valido que ningún campo este vacío
            echo "Has de rellenar todos los campos antes de clickar sobre el botón de Insertar. Inténtalo de nuevo. Gracias.";
        } else {
         
            $alumno=new Alumno(); //Creo un objeto alumno
            //Le seteo las variables, y así me ahorro el constructor   
            $alumno->__SET("Dni", $Dni);
            $alumno->__SET("Nombre", $Nombre);
            $alumno->__SET("Apellido1", $Apellido1);
            $alumno->__SET("Apellido2", $Apellido2);
            $alumno->__SET("Edad", $Edad);

            if(isset($_SESSION['alumnos'][$alumno->Dni])){ //Si el alumno ya está en la sesión muestra un mensaje de actualización y si no de inserción
                echo "El alumno ha sido actualizado en la sesión";
            } else {
                echo "El alumno ha sido insertado en la sesión";
            }
                                    
            $_SESSION['alumnos'][$alumno->Dni]=$alumno; //Ahora digamos que el array alumnos es una matriz, dónde inserto el dni y lo utilizo como llave que me permite acceder a los datos del alumno. Si inserto, pues inserto el alumno en la sesión y si actualizo pues machaco los datos que tuviera antes en la sesión.

        }
    } else if(isset($_POST['borrar'])){
        if(!isset($_POST['Dnis'])){ //Si no hay alumnos marcados para borrar
            echo "No has seleccionado ningún alumno para borrar";
        } else{

            $Dnis=$_POST['Dnis']; //Cojo DNIs que he marcado para borrar

            foreach ($_SESSION['alumnos'] as $key => $value) { //Key es el DNI de la sesión de los alumnos y el value es la fila
                if(in_array($value->Dni,$Dnis)){ //Miro si el Dni de la fila en concreto está en el array de los DNIs seleccionados

                    unset($_SESSION['alumnos'][$key]); //Elimino de la sesión aquellas filas de los DNIs que están en la sesión que he visto que he seleccionado con los checkbox
                }
            }

            echo "Los alumnos selecionados han sido eliminados de la sesión";  
        }
    } else if(isset($_POST['volcar'])){

        if(!isset($_SESSION['alumnos'])){
            echo "No hay ninguna sesión iniciada.";
        }else{

            if(!isset($_POST['Dnis']) ){ //Si no hay alumnos marcados para volcar
                echo "No has seleccionado ningún alumno para volcar";
            }else{

                $Dnis=$_POST['Dnis']; //Recojo los datos enviados vía POST

                $daoAlumno=new DaoAlumno("alumnos"); //Me conecto a la BBDD

                foreach($Dnis as $key => $value){

                    // if(alumnoEnLaSesion($_SESSION['alumnos'], $value) == true ){

                        $alumno = $_SESSION['alumnos'][$value]; //De esta forma cojo el objeto entonces, basándome en el array asociativo que es la sesión, cogiendo el objeto a partir de la llave (DNI)

                        $existe=$daoAlumno->Existe($alumno); 

                        if($existe==true){ //Si el alumno existe en la BBDD
                            $daoAlumno->Actualizar($alumno);
                            echo "El alumno con DNI ".$alumno->Dni." ha sido actualizado con éxito en la BBDD. "; //Aunque sólo vuelque uno en la tabla, te muestra siempre todos los DNIs que hay en sesión, eso hay que mejorarlo
                        } else {
                            $daoAlumno->Insertar($alumno);
                            echo "El alumno con DNI ".$alumno->Dni." ha sido insertado con éxito en la BBDD. "; 

                        }
                        
                        unset($_SESSION['alumnos'][$alumno->Dni]); //Elimino de la sesión aquellas filas de los DNIs que están en la sesión que he visto que he seleccionado con los checkbox, es decir, elimino de la sesión aquellos alumnos que he actualizado o insertado

                
                // }
            }
        }
}
}

?>

<h1> C.P. Clara Campoamor </h1>
<h2>Este es un programa que realiza distintas operaciones con la BBDD de una escuela</h2>
<form method="POST" name="formulario" action="<?php $_SERVER['PHP_SELF']?>">

    <label for="Dni">Escribe tu DNI<label>
    <input type="text" name="Dni" id="Dni">
    <br> <br>
    <label for="Nombre">Escribe tu Nombre<label>
    <input type="text" name="Nombre" id="Nombre">
    <br> <br>
    <label for="Apellido1">Escribe tu primer apellido<label>
    <input type="text" name="Apellido1" id="Apellido1" >
    <br> <br>
    <label for="Apellido2">Escribe tu segundo apellido<label>
    <input type="text" name="Apellido2" id="Apellido2">
    <br> <br>
    <label for="Edad">Escribe tu edad<label>
    <input type="text" name="Edad" id="Edad">
    <br> <br>

    <button type="submit" name="insertar">Insertar</button>
    <button type="submit" name="mostrar">Mostrar</button>
    <button type="submit" name="borrar">Borrar</button>
    <button type="submit" name="volcar">Volcar</button>


<?php
    if(isset($_POST['mostrar'])){ //Mostrar los alumnos de la sesión y con una columna checkbox para cada alumno que permita seleccionarlo

        if(isset($_POST['mostrar'])){ //Cojo los valores de las variables enviadas por POST

            if($_SESSION['alumnos']===0){ //Controlo en caso de que no haya alumnos en la sesión para informar al usuario
                echo "No hay alumnos en la sesión";
            } else{
                echo "<table border=1>";
                echo "<tr>";
                echo "<th></th>"; //th vacío para el checkbox
                echo "<th>DNI</th>";
                echo "<th>Nombre</th>";
                echo "<th>Apellido 1</th>";
                echo "<th>Apellido 2</th>";
                echo "<th>Edad</th>";
                echo "</tr>";

                foreach ($_SESSION['alumnos'] as $key => $value) { //Recorro la sesión y voy creando la tabla
                    ?>
                    <tr>

                    <td><input type="checkbox" name="Dnis[]" value=<?php echo $value->Dni;?>></td>
                    <td><?php echo $value->Dni?></td>
                    <td><?php echo $value->Nombre;?></td>
                    <td><?php echo $value->Apellido1;?></td>
                    <td><?php echo $value->Apellido2;?></td>
                    <td><?php echo $value->Edad;?></td>

                    </tr>





                    <?php
                }




                echo "</table>"; 
            }
          

    
        }   

    }


    ?>


</body>
</html>


