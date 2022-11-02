<?php
session_start();
include("lib.php");
?>
<?php
header("Content-Type: text/html; charset=UTF-8");
?>
<?php

$error;
$comprobador = false;


if (isset($_POST["accion"])) {


    if ($_POST["accion"] == "login") {
       

        if (isset($_POST["email"])) {
            $_SESSION["useremail"] = $_POST["email"];
            $_SESSION["userpassword"] = $_POST["password"];

            //comprobamos si la contraseña tiene mayuscula con el metodo ctype_upper
            for ($i = 0; $i < strlen($_SESSION["userpassword"]); $i++) {
                if (ctype_upper(substr($_SESSION["userpassword"], $i, $i))) {
                    $comprobador = true;
                }
            }

            if (strlen($_SESSION["userpassword"]) >= 8 && $comprobador == true) {
                header("Location: proyectos.php");
            } else {
                $error = "dato incorrecto";
                header("Location: login.php?error=$error");
            }
        } else {
            header("Location: login.php");
        }
    }



    if ($_POST["accion"] == "nuevo") {

        if(empty($_SESSION["misProyectos"])){
            $id=0;
        }else{
            $ids = array_column($_SESSION['misProyectos'], 'id');
            $id = max($ids)+1;
        }
        
        if (isset($_POST["name"]) && isset($_POST["inicio"]) && isset($_POST["final"]) && isset($_POST["dia"]) && isset($_POST["porcentaje"]) && isset($_POST["importancia"])) {
            $id = count($_SESSION["misProyectos"]);
            addProyecto(
                $id,
                $_POST["name"],
                $_POST["inicio"],
                $_POST["final"],
                $_POST["dia"],
                $_POST["porcentaje"],
                $_POST["importancia"]
            );

            header("Location: proyectos.php");
        }
    }
}





if (isset($_GET["accion"])) {

    if ($_GET['accion'] == "borrarTodo") {
        eliminarTodo();
        echo '<script>window.location="' . "proyectos.php" . '"</script>';
        
    }

    if ($_GET['accion'] == "borrarID") {
        eliminarElemento($_GET["id"]);
        echo '<script>window.location="' . "proyectos.php" . '"</script>';
    }

    if($_GET["accion"] == "info"){
        $i=$_GET["id"];
        if(mostrarDato($_GET["id"])){
            
            header("Location:datoProyecto.php?ids=".$i);
        }
        
    }

    if($_GET["accion"] == "pdf"){
        generarPDF();
        enviaEmail();
        echo '<script>window.location="' . "proyectos.php" . '"</script>';
    }

    if($_GET["accion"] == "cerrar"){
        
        echo '<script>window.location="' . "login.php" . '"</script>';
        session_destroy();
    }


}


?>