<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';



function pintarFormulario()
{
    echo '<div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    <form class="user" action="controlador.php" method="post">



                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                name = "email"
                                                placeholder="Enter Email Address...">
                                        </div>


                                        <div class="form-group">
                                            <input type="hidden" class="form-control form-control-user" 
                                            name="accion" id="exampleInputPassword" value="login">
                                        </div>



                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                            name="password"
                                                id="exampleInputPassword" placeholder="Password">
                                        </div>


                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>';
}


function addProyecto(&$id, &$nombre, &$inicio, &$final, &$dia, &$porcentaje, &$importancia)
{

    array_push($_SESSION["misProyectos"], [
        "id" => $id, "nombre" => $nombre, "fechaInicio" => $inicio, "fechaFin" => $final,
        "diasTranscurridos" => $dia, "porcentaje" => $porcentaje, "importancia" => $importancia
    ]);
}



function eliminarTodo()
{
    $vaciar = array();
    $_SESSION["misProyectos"] = $vaciar;
}


function eliminarElemento(&$id)
{
    foreach ($_SESSION["misProyectos"] as $clave => $valor) {

        if ($id == $clave) {
            unset($_SESSION["misProyectos"][$clave]);
        }
    }
}


function mostrarDato(&$id)
{
    $dato = false;
    foreach ($_SESSION["misProyectos"] as $clave => $valor) {

        if ($id == $clave) {
            $dato = true;
        }
    }

    return $dato;
}


function generarPDF()
{


    // create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->setCreator(PDF_CREATOR);
    $pdf->setAuthor('Emilio Porta');
    $pdf->setTitle('Proyectos de mi empresa');
    $pdf->setSubject('Proyectos');
    $pdf->setKeywords('PHP, PDF, proyectos');

    // set default header data
    //$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
    $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

    // set header and footer fonts
    $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // set default monospaced font
    $pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->setHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->setFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
        require_once(dirname(__FILE__) . '/lang/eng.php');
        $pdf->setLanguageArray($l);
    }

    // ---------------------------------------------------------

    // set default font subsetting mode
    $pdf->setFontSubsetting(true);

    // Set font
    // dejavusans is a UTF-8 Unicode font, if you only need to
    // print standard ASCII chars, you can use core fonts like
    // helvetica or times to reduce file size.
    $pdf->setFont('dejavusans', '', 14, '', true);

    // Add a page
    // This method has several options, check the source code documentation for more information.
    $pdf->AddPage();

    // set text shadow effect
    $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));

    // Set some content to print
    $html = "
<h1>PROYECTOS</h1>
<i>Todos los proyectos de mi empresa</i><br><br>";
    $html .= "<table border='1'>";
    $html .= "<tr><td>Nombre</td><td>Importancia</td><td>Porcentaje</td><td>Inicio</td></tr>";

    foreach ($_SESSION["misProyectos"] as $proyecto) {
        $html .= "<tr>";
        $html .= "<td>" . $proyecto['nombre'] . "</td>";
        $html .= "<td>" . $proyecto['importancia'] . "</td>";
        $html .= "<td>" . $proyecto['porcentaje'] . "</td>";
        $html .= "<td>" . $proyecto['fechaInicio'] . "</td>";

        $html .= "</tr>";
    }

    $html .= "</table>";

    // Print text using writeHTMLCell()
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

    // ---------------------------------------------------------

    echo "Generando ...";

    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
    $flujo = $pdf->Output('ejemplo.pdf', 'S');
    file_put_contents("ejemplo.pdf", $flujo);

    echo "Generado.";
}


function enviaEmail()
{

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = $_SESSION["useremail"];                     //SMTP username
        $mail->Password   = 'tu-clave-aqui';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($_SESSION["useremail"], 'Proyectos');
        $mail->addAddress($_SESSION["useremail"], 'AlumnoEP');     //Add a recipient
        //$mail->addAddress('ellen@example.com');               //Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        $mail->addAttachment('./ejemplo.pdf', 'ejemplo.pdf');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Correo de mi página de proyectos';
        $mail->Body    = 'Este el cuerpo del mensaje <b>ojo, viene con adjunto!</b>';
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
