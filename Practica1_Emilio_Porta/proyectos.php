<?php
include("cabecera.php");
include("lib.php")
?>


<?php


$misProyectos = array();

//Metemos en la sesión todos los productos
if (!isset($_SESSION['misProyectos']))
    $_SESSION['misProyectos'] = $misProyectos;


echo "<table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>";
echo "<tbody>";
echo "<thead>";
echo "<tr>";
echo "<td>" . strtoupper("nombre") . "</td>";
echo "<td>" . strtoupper("fecha Inicio") . "</td>";
echo "<td>" . strtoupper("fecha Fin") . "</td>";
echo "<td>" . strtoupper("dias Transcurridos") . "</td>";
echo "<td>" . strtoupper("porcentaje") . "</td>";
echo "<td>" . strtoupper("importancia") . "</td>";


echo "</tr>";
echo "</thead>";
foreach ($_SESSION['misProyectos'] as $clave => $linea) {
    echo "<tr>";
    echo "<td>" . $linea['nombre'] . "</td>";
    echo "<td>" . $linea['fechaInicio'] . "</td>";
    echo "<td>" . $linea['fechaFin'] . "</td>";
    echo "<td>" . $linea['diasTranscurridos'] . "</td>";
    echo "<td>" . $linea['porcentaje'] . "</td>";
    echo "<td>" . $linea['importancia'] . "</td>";
    echo "<td>" .
        '<a href="controlador.php?accion=borrarID&id='.$clave.'" class="nav-link dropdown-toggle">
            Borrar
        </a>'
        . "</td>";
        echo "<td>" .
        '<a href="controlador.php?accion=info&id='.$clave.'" class="nav-link dropdown-toggle">
            Info
        </a>'
        . "</td>";

    echo "</tr>";
}
echo "</tbody>";
echo "</table>";



?>


<?php

include("pie.php");

?>

