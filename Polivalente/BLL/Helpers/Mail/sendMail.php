<?php

require_once dirname(__FILE__) . '/../../../vendor/autoload.php';
set_time_limit(300);
/**
 * Envio de correo mediante un servidor SMTP
 */
include_once 'phpmailer.php';
include_once 'SimpleMail.php';

class sendMail {

    function __construct() {
        
    }


    //    <editor-fold defaultstate="collapsed" desc="Autorizacion">
    public function EnviarEmail_Autorizacion($EmpresaObj, $Nombre, $Email, $files, $Asunto) {
//        set_time_limit(300);
        $smtp = new PHPMailer();
//        $smtp->Timeout  = 36000;
# Indicamos que vamos a utilizar un servidor SMTP

        $smtp->IsSMTP();
        $smtp->SMTPDebug = 2;
        $smtp->Debugoutput = 'html';
//        $smtp->SMTPKeepAlive = true;
//        $smtp->SMTPAuth = true;
# Definimos el formato del correo con UTF-8
        $smtp->CharSet = "UTF-8";
# autenticación contra nuestro servidor smtp
        $smtp->SMTPAuth = true;      // enable SMTP authentication
//        $smtp->SMTPSecure = 'tls';





        $smtp->Port = $EmpresaObj->PuertoSmtp;
        $smtp->Host = $EmpresaObj->SMTP;   // sets MAIL as the SMTP server
        $smtp->Username = $EmpresaObj->CorreoSmtp; // MAIL username
        $smtp->Password = $EmpresaObj->PasswordSmtp;   // MAIL password
# datos de quien realiza el envio
        $smtp->From = $EmpresaObj->CorreoSmtp; // from mail
        $smtp->FromName = $EmpresaObj->Nombre; // from mail name
# Indicamos las direcciones donde enviar el mensaje con el formato
#   "correo"=>"nombre usuario"
# Se pueden poner tantos correos como se deseen
        $mailTo = array(
            $Email => $this->sanear_string($Nombre),
        );

        $Password = " a";

        //Attach multiple files one by one
//        if (array_key_exists('Archivo', $files)) {
//            for ($ct = 0; $ct < count($files['file']['name']); $ct++) {
////                $uploadfile = tempnam(sys_get_temp_dir(), hash('sha256', $_FILES['Archivo']['name'][$ct]));
//                $uploadfile = "../email_files/";
//                
//                $filename = $files['file']['name'][$ct];
//                echo $filename;
//                if (move_uploaded_file($files['file']['name'][$ct], $uploadfile)) {
//                    $smtp->addAttachment($uploadfile, $filename);
//                } else {
//                    $msg .= 'Failed to move file to ' . $uploadfile;
//                }
//            }
//        $smtp->ClearAttachments();
        echo $this->getDatetimeNow() . '<br\>';
        foreach ($files as $f) {
//            $url = dirname(__FILE__). "\..\..\..\\email_files\\$f->archivo";
//            echo $url;
//            $smtp->addAttachment($url);
            $smtp->addAttachment(dirname(__FILE__) . "\..\..\..\\email_files\\$f->archivo", $f->archivo);
        }
        echo $this->getDatetimeNow() . '<br\>';
//        }
# establecemos un limite de caracteres de anchura
        $smtp->WordWrap = 50; // set word wrap
# NOTA: Los correos es conveniente enviarlos en formato HTML y Texto para que
# cualquier programa de correo pueda leerlo.
# Definimos el contenido HTML del correo
        $contenido = "
            <h2>Bienvenido a Mant. Polivalente</h2>
            <p>
                Tu Usuario es: <strong>$Email</strong><br/>
                Tu Contraseña es: <strong>$Password</strong><br/>
                <br/>
                Recuerda cambiar tu clave. 
                Puedes Ingresar haciendo clic en el siguiente enlace http://192.168.8.125:8080/Polivalente/
            </p>";
        $contenidoHTML = '<!DOCTYPE html><html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta charset="utf-8"><meta name="viewport" content="width=device-width"><meta http-equiv="X-UA-Compatible" content="IE=edge"><title></title></head><body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#f4f4f4" style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; height: 100% !important; width: 100% !important; font-family: Arial, sans-serif; color: #024457; background: #f2f2f2; margin: 0; padding: 0;"><style type="text/css">@media screen and (max-device-width: 600px){table[class="email-container"]{width: 100% !important;}table[class="fluid"]{width: 100% !important;}img[class="fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{margin: auto !important;}td[class="force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: left; margin: 0 15px 15px 0;}img[class="col-3-img-r"]{float: right; margin: 0 0 15px 15px;}table[class="button"]{width: 100% !important;}}@media screen and (max-width: 600px){table[class="email-container"]{width: 100% !important;}table[class="fluid"]{width: 100% !important;}img[class="fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{margin: auto !important;}td[class="force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: left; margin: 0 15px 15px 0;}img[class="col-3-img-r"]{float: right; margin: 0 0 15px 15px;}table[class="button"]{width: 100% !important;}}@media screen and (max-device-width: 425px){div[class="hh-visible"]{display: block !important;}div[class="hh-center"]{text-align: center; width: 100% !important;}table[class="hh-fluid"]{width: 100% !important;}img[class="hh-fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{margin: auto !important;}td[class="hh-force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: none !important; margin: 15px auto !important; text-align: center !important;}img[class="col-3-img-r"]{float: none !important; margin: 15px auto !important; text-align: center !important;}}@media screen and (max-width: 425px){div[class="hh-visible"]{display: block !important;}div[class="hh-center"]{text-align: center; width: 100% !important;}table[class="hh-fluid"]{width: 100% !important;}img[class="hh-fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{margin: auto !important;}td[class="hh-force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: none !important; margin: 15px auto !important; text-align: center !important;}img[class="col-3-img-r"]{float: none !important; margin: 15px auto !important; text-align: center !important;}}@media (min-width: 480px){.responstable th:nth-child(2) span{display: block;}.responstable th:nth-child(2):after{display: none;}.responstable td{border: 1px solid #D9E4E6;}.responstable th{display: table-cell; padding: 1em;}.responstable td{display: table-cell; padding: 1em;}}</style><table cellpadding="0" cellspacing="0" border="0" height="100%" width="100%" bgcolor="#f4f4f4" id="bodyTable" style="border-collapse: collapse; table-layout: fixed; height: 100% !important; width: 100% !important; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: 0 auto; padding: 0;"><tr><td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><div style="display: none; visibility: hidden; opacity: 0; color: transparent; height: 0; width: 0; line-height: 0; overflow: hidden; mso-hide: all;">Mensaje enviado de forma automatica, por favor no responder a este mensaje.</div><table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" bgcolor="#00a0e3" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tr><td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><table border="0" width="600" cellpadding="0" cellspacing="0" align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: auto;" class="email-container"><tr><td height="10" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr><tr><td class="hh-force-col-center" valign="middle" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" colspan="2" align="center"><img src="{{Logo}}" alt="alt text" width="400" border="0" style="-ms-interpolation-mode: bicubic; outline: none; text-decoration: none; border: 0;"></td></tr><tr><td height="10" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr></table></td></tr></table><table border="0" width="80%" cellpadding="0" cellspacing="0" align="center" class="email-container" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: auto;"><tr><td height="30" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr><tr><td height="30" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr></table><table border="0" width="80%" cellpadding="0" cellspacing="0" align="center" bgcolor="#ffffff" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: auto; border: 1px solid #e5e5e5;" class="email-container"><tr><td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tr><td style="font-family: sans-serif; font-size: 16px; line-height: 22px; color: #444444; text-align: justify; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 30px;" align="justify">{{CONTENIDO}}</td></tr></table></td></tr><tr><td valign="middle" align="center" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 10px 0;"></td></tr><tr><td valign="middle" align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr><tr style="border-bottom-width: 1px; border-bottom-color: #e5e5e5; border-bottom-style: solid;"><td valign="middle" align="center" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 10px 0;"></td></tr></table><table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" class="email-container" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tr><td style="text-align: center; font-family: sans-serif; font-size: 12px; line-height: 18px; color: #888888; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 20px;" align="center">{{Empresa}};{{Direccion}}<span class="mobile_link" style="color: #222222 !important; text-decoration: underline !important;">Tel:{{Telefono}}</span><br><a href="http://{{SitioWeb}}" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">{{SitioWeb}}</a><br><br></td></tr></table></td></tr></table></body></html>';
        $contenidoHTML = str_replace("{{CONTENIDO}}", $contenido, $contenidoHTML);
# Definimos el subject
        $smtp->Subject = $this->sanear_string($Asunto);

# Adjuntamos el archivo "leameLWP.txt" al correo.
# Obtenemos la ruta absoluta de donde se ejecuta este script para encontrar el
# archivo leameLWP.txt para adjuntar. Por ejemplo, si estamos ejecutando nuestro
# script en: /home/xve/test/sendMail.php, nos interesa obtener unicamente:
# /home/xve/test para posteriormente adjuntar el archivo leameLWP.txt, quedando
# /home/xve/test/leameLWP.txt
#$rutaAbsoluta=substr($_SERVER["SCRIPT_FILENAME"],0,strrpos($_SERVER["SCRIPT_FILENAME"],"/"));
#$smtp->AddAttachment($rutaAbsoluta."/leameLWP.txt", "LeameLWP.txt");
# Indicamos el contenido

        $contenidoHTML = str_replace("{{Empresa}}", $EmpresaObj->Nombre, $contenidoHTML);
        $contenidoHTML = str_replace("{{Direccion}}", $EmpresaObj->Direccion, $contenidoHTML);
        $contenidoHTML = str_replace("{{Telefono}}", $EmpresaObj->Telefono, $contenidoHTML);
        $contenidoHTML = str_replace("{{SitioWeb}}", $EmpresaObj->SitioWeb, $contenidoHTML);
        $contenidoHTML = str_replace("{{Logo}}", $EmpresaObj->Logo, $contenidoHTML);
        #$smtp->AltBody = $contenidoTexto; //Text Body
        $smtp->MsgHTML($contenidoHTML); //Text body HTML
        $smtp->AltBody = $contenidoHTML; //Text Body
        foreach ($mailTo as $mail => $name) {
            $smtp->ClearAllRecipients();
            $smtp->AddAddress($mail, $name);
            echo $this->getDatetimeNow() . '        \n3333<br\>';
            if (!$smtp->Send()) {
                return "<br>Error (" . $mail . "): " . $smtp->ErrorInfo;
            } else {
                echo $this->getDatetimeNow() . '        \nOKKKKKKKKKKKKKKKKKK<br\>';
                return "<br>Envio realizado a " . $name . " (" . $mail . ")";
            }
        }
    }

    public function NotificarAutorizacion($EmpresaObj, $Nombre, $Email, $files, $Asunto) {
        set_time_limit(300);
//        $transport = (new Swift_SmtpTransport($EmpresaObj->SMTP, $EmpresaObj->PuertoSmtp))
//                ->setUsername($EmpresaObj->CorreoSmtp)
//                ->setPassword($EmpresaObj->PasswordSmtp);
        
        $transport = (new Swift_SmtpTransport("smtp.gmail.com", 25,'tls'))
                ->setUsername("zlinker89@gmail.com")
                ->setPassword("darkLINK89");

// Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);

// Create a message

        $contenido = "
            <h2>Bienvenido a Mant. Polivalente</h2>
            <p>
                Tu Usuario es: <strong>$Email</strong><br/>
                Tu Contraseña es: <strong></strong><br/>
                <br/>
                Recuerda cambiar tu clave. 
                Puedes Ingresar haciendo clic en el siguiente enlace http://192.168.8.125:8080/Polivalente/
            </p>";
        $contenidoHTML = '<!DOCTYPE html><html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta charset="utf-8"><meta name="viewport" content="width=device-width"><meta http-equiv="X-UA-Compatible" content="IE=edge"><title></title></head><body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#f4f4f4" style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; height: 100% !important; width: 100% !important; font-family: Arial, sans-serif; color: #024457; background: #f2f2f2; margin: 0; padding: 0;"><style type="text/css">@media screen and (max-device-width: 600px){table[class="email-container"]{width: 100% !important;}table[class="fluid"]{width: 100% !important;}img[class="fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{margin: auto !important;}td[class="force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: left; margin: 0 15px 15px 0;}img[class="col-3-img-r"]{float: right; margin: 0 0 15px 15px;}table[class="button"]{width: 100% !important;}}@media screen and (max-width: 600px){table[class="email-container"]{width: 100% !important;}table[class="fluid"]{width: 100% !important;}img[class="fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{margin: auto !important;}td[class="force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: left; margin: 0 15px 15px 0;}img[class="col-3-img-r"]{float: right; margin: 0 0 15px 15px;}table[class="button"]{width: 100% !important;}}@media screen and (max-device-width: 425px){div[class="hh-visible"]{display: block !important;}div[class="hh-center"]{text-align: center; width: 100% !important;}table[class="hh-fluid"]{width: 100% !important;}img[class="hh-fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{margin: auto !important;}td[class="hh-force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: none !important; margin: 15px auto !important; text-align: center !important;}img[class="col-3-img-r"]{float: none !important; margin: 15px auto !important; text-align: center !important;}}@media screen and (max-width: 425px){div[class="hh-visible"]{display: block !important;}div[class="hh-center"]{text-align: center; width: 100% !important;}table[class="hh-fluid"]{width: 100% !important;}img[class="hh-fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{margin: auto !important;}td[class="hh-force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: none !important; margin: 15px auto !important; text-align: center !important;}img[class="col-3-img-r"]{float: none !important; margin: 15px auto !important; text-align: center !important;}}@media (min-width: 480px){.responstable th:nth-child(2) span{display: block;}.responstable th:nth-child(2):after{display: none;}.responstable td{border: 1px solid #D9E4E6;}.responstable th{display: table-cell; padding: 1em;}.responstable td{display: table-cell; padding: 1em;}}</style><table cellpadding="0" cellspacing="0" border="0" height="100%" width="100%" bgcolor="#f4f4f4" id="bodyTable" style="border-collapse: collapse; table-layout: fixed; height: 100% !important; width: 100% !important; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: 0 auto; padding: 0;"><tr><td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><div style="display: none; visibility: hidden; opacity: 0; color: transparent; height: 0; width: 0; line-height: 0; overflow: hidden; mso-hide: all;">Mensaje enviado de forma automatica, por favor no responder a este mensaje.</div><table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" bgcolor="#00a0e3" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tr><td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><table border="0" width="600" cellpadding="0" cellspacing="0" align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: auto;" class="email-container"><tr><td height="10" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr><tr><td class="hh-force-col-center" valign="middle" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" colspan="2" align="center"><img src="{{Logo}}" alt="alt text" width="400" border="0" style="-ms-interpolation-mode: bicubic; outline: none; text-decoration: none; border: 0;"></td></tr><tr><td height="10" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr></table></td></tr></table><table border="0" width="80%" cellpadding="0" cellspacing="0" align="center" class="email-container" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: auto;"><tr><td height="30" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr><tr><td height="30" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr></table><table border="0" width="80%" cellpadding="0" cellspacing="0" align="center" bgcolor="#ffffff" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: auto; border: 1px solid #e5e5e5;" class="email-container"><tr><td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tr><td style="font-family: sans-serif; font-size: 16px; line-height: 22px; color: #444444; text-align: justify; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 30px;" align="justify">{{CONTENIDO}}</td></tr></table></td></tr><tr><td valign="middle" align="center" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 10px 0;"></td></tr><tr><td valign="middle" align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr><tr style="border-bottom-width: 1px; border-bottom-color: #e5e5e5; border-bottom-style: solid;"><td valign="middle" align="center" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 10px 0;"></td></tr></table><table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" class="email-container" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tr><td style="text-align: center; font-family: sans-serif; font-size: 12px; line-height: 18px; color: #888888; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 20px;" align="center">{{Empresa}};{{Direccion}}<span class="mobile_link" style="color: #222222 !important; text-decoration: underline !important;">Tel:{{Telefono}}</span><br><a href="http://{{SitioWeb}}" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">{{SitioWeb}}</a><br><br></td></tr></table></td></tr></table></body></html>';
        $contenidoHTML = str_replace("{{CONTENIDO}}", $contenido, $contenidoHTML);
        $contenidoHTML = str_replace("{{Empresa}}", $EmpresaObj->Nombre, $contenidoHTML);
        $contenidoHTML = str_replace("{{Direccion}}", $EmpresaObj->Direccion, $contenidoHTML);
        $contenidoHTML = str_replace("{{Telefono}}", $EmpresaObj->Telefono, $contenidoHTML);
        $contenidoHTML = str_replace("{{SitioWeb}}", $EmpresaObj->SitioWeb, $contenidoHTML);
        $contenidoHTML = str_replace("{{Logo}}", $EmpresaObj->Logo, $contenidoHTML);

        $message = (new Swift_Message($Asunto))
                ->setFrom([$EmpresaObj->CorreoSmtp => $EmpresaObj->Nombre])
                ->setTo([$Email => $Nombre])
                ->setBody($contenidoHTML, 'text/html');
        foreach ($files as $f) {
            // Fetch the HeaderSet from a Message object
            $message->attach(
                Swift_Attachment::fromPath(dirname(__FILE__) . "\..\..\..\\email_files\\$f->archivo")->setFilename($f->archivo)
            );
        }

// Send the message
        $result = $mailer->send($message);
        return $result;
    }

// </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Polivalente">
    public function EnviarEmail_ReporteManual($Correo, $Nombres, $TipoServicio, $Url, $EmpresaObj) {
        $smtp = new PHPMailer();

# Indicamos que vamos a utilizar un servidor SMTP
        $smtp->IsSMTP();
        // $smtp->SMTPDebug = 2;
        // $smtp->Debugoutput = 'html';
        $smtp->isHTML(true);
# Definimos el formato del correo con UTF-8
        $smtp->CharSet = "UTF-8";
# autenticación contra nuestro servidor smtp
// $smtp->SMTPDebug = 2;
// $smtp->Debugoutput = 'html';
$smtp->WordWrap = 70; // set word wrap
$smtp->isHTML(true); // set word wrap
// $smtp->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
# autenticación contra nuestro servidor smtp
$smtp->Port = 587;
$smtp->Host = 'smtp.gmail.com';   // sets MAIL as the SMTP server
$smtp->SMTPAuth = true;  // authentication enabled
$smtp->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
$smtp->SMTPAutoTLS = false;
        $smtp->Username = $EmpresaObj->CorreoSmtp; // MAIL username
        $smtp->Password = $EmpresaObj->PasswordSmtp;   // MAIL password
# datos de quien realiza el envio
        $smtp->From = $EmpresaObj->CorreoSmtp; // from mail
        $smtp->FromName = $EmpresaObj->Nombre; // from mail name
# Indicamos las direcciones donde enviar el mensaje con el formato
#   "correo"=>"nombre usuario"
# Se pueden poner tantos correos como se deseen
        $mailTo = array(
            $Correo => $this->sanear_string($Nombres),
        );

# establecemos un limite de caracteres de anchura
        $smtp->WordWrap = 70; // set word wrap
# NOTA: Los correos es conveniente enviarlos en formato HTML y Texto para que
# cualquier programa de correo pueda leerlo.
# Definimos el contenido HTML del correo
        $contenidoHTML = '<!DOCTYPE html><html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta charset="utf-8"><meta name="viewport" content="width=device-width"><meta http-equiv="X-UA-Compatible" content="IE=edge"><title></title></head><body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#f4f4f4" style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; height: 100% !important; width: 100% !important; font-family: Arial, sans-serif; color: #024457; background: #f2f2f2; margin: 0; padding: 0;"><style type="text/css">@media screen and (max-device-width: 600px){table[class="email-container"]{width: 100% !important;}table[class="fluid"]{width: 100% !important;}img[class="fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{margin: auto !important;}td[class="force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: left; margin: 0 15px 15px 0;}img[class="col-3-img-r"]{float: right; margin: 0 0 15px 15px;}table[class="button"]{width: 100% !important;}}@media screen and (max-width: 600px){table[class="email-container"]{width: 100% !important;}table[class="fluid"]{width: 100% !important;}img[class="fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{margin: auto !important;}td[class="force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: left; margin: 0 15px 15px 0;}img[class="col-3-img-r"]{float: right; margin: 0 0 15px 15px;}table[class="button"]{width: 100% !important;}}@media screen and (max-device-width: 425px){div[class="hh-visible"]{display: block !important;}div[class="hh-center"]{text-align: center; width: 100% !important;}table[class="hh-fluid"]{width: 100% !important;}img[class="hh-fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{margin: auto !important;}td[class="hh-force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: none !important; margin: 15px auto !important; text-align: center !important;}img[class="col-3-img-r"]{float: none !important; margin: 15px auto !important; text-align: center !important;}}@media screen and (max-width: 425px){div[class="hh-visible"]{display: block !important;}div[class="hh-center"]{text-align: center; width: 100% !important;}table[class="hh-fluid"]{width: 100% !important;}img[class="hh-fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{margin: auto !important;}td[class="hh-force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: none !important; margin: 15px auto !important; text-align: center !important;}img[class="col-3-img-r"]{float: none !important; margin: 15px auto !important; text-align: center !important;}}@media (min-width: 480px){.responstable th:nth-child(2) span{display: block;}.responstable th:nth-child(2):after{display: none;}.responstable td{border: 1px solid #D9E4E6;}.responstable th{display: table-cell; padding: 1em;}.responstable td{display: table-cell; padding: 1em;}}</style><table cellpadding="0" cellspacing="0" border="0" height="100%" width="100%" bgcolor="#f4f4f4" id="bodyTable" style="border-collapse: collapse; table-layout: fixed; height: 100% !important; width: 100% !important; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: 0 auto; padding: 0;"><tr><td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><div style="display: none; visibility: hidden; opacity: 0; color: transparent; height: 0; width: 0; line-height: 0; overflow: hidden; mso-hide: all;">Mensaje enviado de forma automatica, por favor no responder a este mensaje.</div><table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" bgcolor="#00a0e3" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tr><td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><table border="0" width="600" cellpadding="0" cellspacing="0" align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: auto;" class="email-container"><tr><td height="10" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr><tr><td class="hh-force-col-center" valign="middle" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" colspan="2" align="center"><img src="{{Logo}}" alt="alt text" width="400" border="0" style="-ms-interpolation-mode: bicubic; outline: none; text-decoration: none; border: 0;"></td></tr><tr><td height="10" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr></table></td></tr></table><table border="0" width="600" cellpadding="0" cellspacing="0" align="center" class="email-container" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: auto;"><tr><td height="30" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr><tr><td height="30" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr></table><table border="0" width="600" cellpadding="0" cellspacing="0" align="center" bgcolor="#ffffff" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: auto; border: 1px solid #e5e5e5;" class="email-container"><tr><td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tr><td style="font-family: sans-serif; font-size: 16px; line-height: 22px; color: #444444; text-align: justify; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 30px;" align="justify">{{CONTENIDO}}</td></tr></table></td></tr><tr><td valign="middle" align="center" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 10px 0;"></td></tr><tr><td valign="middle" align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr><tr style="border-bottom-width: 1px; border-bottom-color: #e5e5e5; border-bottom-style: solid;"><td valign="middle" align="center" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 10px 0;"></td></tr></table><table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" class="email-container" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tr><td style="text-align: center; font-family: sans-serif; font-size: 12px; line-height: 18px; color: #888888; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 20px;" align="center">{{Empresa}};{{Direccion}}<span class="mobile_link" style="color: #222222 !important; text-decoration: underline !important;">Tel:{{Telefono}}</span><br><a href="http://{{SitioWeb}}" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">{{SitioWeb}}</a><br><br></td></tr></table></td></tr></table></body></html>';
        $contenidoHTML = str_replace("{{CONTENIDO}}", $EmpresaObj->FormatoCorreo, $contenidoHTML);
# Definimos el subject
        $smtp->Subject = "Se ha generado un reporte de servicio " . $TipoServicio;

# Adjuntamos el archivo "leameLWP.txt" al correo.
# Obtenemos la ruta absoluta de donde se ejecuta este script para encontrar el
# archivo leameLWP.txt para adjuntar. Por ejemplo, si estamos ejecutando nuestro
# script en: /home/xve/test/sendMail.php, nos interesa obtener unicamente:
# /home/xve/test para posteriormente adjuntar el archivo leameLWP.txt, quedando
# /home/xve/test/leameLWP.txt
#$rutaAbsoluta=substr($_SERVER["SCRIPT_FILENAME"],0,strrpos($_SERVER["SCRIPT_FILENAME"],"/"));
#$smtp->AddAttachment($rutaAbsoluta."/leameLWP.txt", "LeameLWP.txt");
# Indicamos el contenido

        $contenidoHTML = str_replace("{{TipoServicio}}", $TipoServicio, $contenidoHTML);
        $contenidoHTML = str_replace("{{URL}}", $Url, $contenidoHTML);
        $contenidoHTML = str_replace("{{Empresa}}", $EmpresaObj->Nombre, $contenidoHTML);
        $contenidoHTML = str_replace("{{Direccion}}", $EmpresaObj->Direccion, $contenidoHTML);
        $contenidoHTML = str_replace("{{Telefono}}", $EmpresaObj->Telefono, $contenidoHTML);
        $contenidoHTML = str_replace("{{SitioWeb}}", $EmpresaObj->SitioWeb, $contenidoHTML);
        $contenidoHTML = str_replace("{{Logo}}", $EmpresaObj->Logo, $contenidoHTML);
        #$smtp->AltBody = $contenidoTexto; //Text Body
        $smtp->MsgHTML($contenidoHTML); //Text body HTML

        foreach ($mailTo as $mail => $name) {
            $smtp->ClearAllRecipients();
            $smtp->AddAddress($mail, $name);

            if (!$smtp->Send()) {
                return "<br>Error (" . $mail . "): " . $smtp->ErrorInfo;
            } else {
                return "<br>Envio realizado a " . $name . " (" . $mail . ")";
            }
        }
    }

    public function EnviarEmail_Alerta($Correo, $Sede, $EmpresaObj, $Tabla, $Asunto) {
        $smtp = new PHPMailer();

# Indicamos que vamos a utilizar un servidor SMTP
        $smtp->IsSMTP();

# Definimos el formato del correo con UTF-8
        $smtp->CharSet = "UTF-8";
# autenticación contra nuestro servidor smtp
        $smtp->SMTPAuth = true;      // enable SMTP authentication
        #$smtp->SMTPSecure = 'tls';
# autenticación contra nuestro servidor smtp
// $smtp->SMTPDebug = 2;
//         $smtp->Debugoutput = 'html';
        $smtp->WordWrap = 70; // set word wrap
        $smtp->isHTML(true); // set word wrap
        // $smtp->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
# autenticación contra nuestro servidor smtp
        $smtp->Port = 587;
        $smtp->Host = 'smtp.gmail.com';   // sets MAIL as the SMTP server
        $smtp->SMTPAuth = true;  // authentication enabled
        $smtp->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
        $smtp->SMTPAutoTLS = false;
        $smtp->Username = $EmpresaObj->CorreoSmtp; // MAIL username
        $smtp->Password = $EmpresaObj->PasswordSmtp;   // MAIL password
# datos de quien realiza el envio
        $smtp->From = $EmpresaObj->CorreoSmtp; // from mail
        $smtp->FromName = $EmpresaObj->Nombre; // from mail name
# Indicamos las direcciones donde enviar el mensaje con el formato
#   "correo"=>"nombre usuario"
# Se pueden poner tantos correos como se deseen
        $mailTo = array(
            $Correo => $Sede,
        );

# establecemos un limite de caracteres de anchura
        $smtp->WordWrap = 70; // set word wrap
# NOTA: Los correos es conveniente enviarlos en formato HTML y Texto para que
# cualquier programa de correo pueda leerlo.
# Definimos el contenido HTML del correo
        $contenidoHTML = '<!DOCTYPE html><html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta charset="utf-8"><meta name="viewport" content="width=device-width"><meta http-equiv="X-UA-Compatible" content="IE=edge"><title></title></head><body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#f4f4f4" style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; height: 100% !important; width: 100% !important; font-family: Arial, sans-serif; color: #024457; background: #f2f2f2; margin: 0; padding: 0;"><style type="text/css">@media screen and (max-device-width: 600px){table[class="email-container"]{width: 100% !important;}table[class="fluid"]{width: 100% !important;}img[class="fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{margin: auto !important;}td[class="force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: left; margin: 0 15px 15px 0;}img[class="col-3-img-r"]{float: right; margin: 0 0 15px 15px;}table[class="button"]{width: 100% !important;}}@media screen and (max-width: 600px){table[class="email-container"]{width: 100% !important;}table[class="fluid"]{width: 100% !important;}img[class="fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{margin: auto !important;}td[class="force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: left; margin: 0 15px 15px 0;}img[class="col-3-img-r"]{float: right; margin: 0 0 15px 15px;}table[class="button"]{width: 100% !important;}}@media screen and (max-device-width: 425px){div[class="hh-visible"]{display: block !important;}div[class="hh-center"]{text-align: center; width: 100% !important;}table[class="hh-fluid"]{width: 100% !important;}img[class="hh-fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{margin: auto !important;}td[class="hh-force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: none !important; margin: 15px auto !important; text-align: center !important;}img[class="col-3-img-r"]{float: none !important; margin: 15px auto !important; text-align: center !important;}}@media screen and (max-width: 425px){div[class="hh-visible"]{display: block !important;}div[class="hh-center"]{text-align: center; width: 100% !important;}table[class="hh-fluid"]{width: 100% !important;}img[class="hh-fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{margin: auto !important;}td[class="hh-force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: none !important; margin: 15px auto !important; text-align: center !important;}img[class="col-3-img-r"]{float: none !important; margin: 15px auto !important; text-align: center !important;}}@media (min-width: 480px){.responstable th:nth-child(2) span{display: block;}.responstable th:nth-child(2):after{display: none;}.responstable td{border: 1px solid #D9E4E6;}.responstable th{display: table-cell; padding: 1em;}.responstable td{display: table-cell; padding: 1em;}}</style><table cellpadding="0" cellspacing="0" border="0" height="100%" width="100%" bgcolor="#f4f4f4" id="bodyTable" style="border-collapse: collapse; table-layout: fixed; height: 100% !important; width: 100% !important; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: 0 auto; padding: 0;"><tr><td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><div style="display: none; visibility: hidden; opacity: 0; color: transparent; height: 0; width: 0; line-height: 0; overflow: hidden; mso-hide: all;">Mensaje enviado de forma automatica, por favor no responder a este mensaje.</div><table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" bgcolor="#00a0e3" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tr><td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><table border="0" width="600" cellpadding="0" cellspacing="0" align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: auto;" class="email-container"><tr><td height="10" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr><tr><td class="hh-force-col-center" valign="middle" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" colspan="2" align="center"><img src="{{Logo}}" alt="alt text" width="400" border="0" style="-ms-interpolation-mode: bicubic; outline: none; text-decoration: none; border: 0;"></td></tr><tr><td height="10" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr></table></td></tr></table><table border="0" width="80%" cellpadding="0" cellspacing="0" align="center" class="email-container" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: auto;"><tr><td height="30" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr><tr><td height="30" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr></table><table border="0" width="80%" cellpadding="0" cellspacing="0" align="center" bgcolor="#ffffff" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: auto; border: 1px solid #e5e5e5;" class="email-container"><tr><td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tr><td style="font-family: sans-serif; font-size: 16px; line-height: 22px; color: #444444; text-align: justify; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 30px;" align="justify">{{CONTENIDO}}</td></tr></table></td></tr><tr><td valign="middle" align="center" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 10px 0;"></td></tr><tr><td valign="middle" align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr><tr style="border-bottom-width: 1px; border-bottom-color: #e5e5e5; border-bottom-style: solid;"><td valign="middle" align="center" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 10px 0;"></td></tr></table><table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" class="email-container" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tr><td style="text-align: center; font-family: sans-serif; font-size: 12px; line-height: 18px; color: #888888; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 20px;" align="center">{{Empresa}};{{Direccion}}<span class="mobile_link" style="color: #222222 !important; text-decoration: underline !important;">Tel:{{Telefono}}</span><br><a href="http://{{SitioWeb}}" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">{{SitioWeb}}</a><br><br></td></tr></table></td></tr></table></body></html>';
        $contenidoHTML = str_replace("{{CONTENIDO}}", "<table>" . $Tabla . "</table>", $contenidoHTML);
# Definimos el subject
        $smtp->Subject = $Asunto;

# Adjuntamos el archivo "leameLWP.txt" al correo.
# Obtenemos la ruta absoluta de donde se ejecuta este script para encontrar el
# archivo leameLWP.txt para adjuntar. Por ejemplo, si estamos ejecutando nuestro
# script en: /home/xve/test/sendMail.php, nos interesa obtener unicamente:
# /home/xve/test para posteriormente adjuntar el archivo leameLWP.txt, quedando
# /home/xve/test/leameLWP.txt
#$rutaAbsoluta=substr($_SERVER["SCRIPT_FILENAME"],0,strrpos($_SERVER["SCRIPT_FILENAME"],"/"));
#$smtp->AddAttachment($rutaAbsoluta."/leameLWP.txt", "LeameLWP.txt");
# Indicamos el contenido

        $contenidoHTML = str_replace("{{Empresa}}", $EmpresaObj->Nombre, $contenidoHTML);
        $contenidoHTML = str_replace("{{Direccion}}", $EmpresaObj->Direccion, $contenidoHTML);
        $contenidoHTML = str_replace("{{Telefono}}", $EmpresaObj->Telefono, $contenidoHTML);
        $contenidoHTML = str_replace("{{SitioWeb}}", $EmpresaObj->SitioWeb, $contenidoHTML);
        $contenidoHTML = str_replace("{{Logo}}", $EmpresaObj->Logo, $contenidoHTML);
        #$smtp->AltBody = $contenidoTexto; //Text Body
        $smtp->MsgHTML($contenidoHTML); //Text body HTML

        foreach ($mailTo as $mail => $name) {
            $smtp->ClearAllRecipients();
            $smtp->AddAddress($mail, $name);

            if (!$smtp->Send()) {
                return "<br>Error (" . $mail . "): " . $smtp->ErrorInfo;
            } else {
                return "<br>Envio realizado a " . $name . " (" . $mail . ")";
            }
        }
    }

    public function EnviarEmail_Notificacion($EmpresaObj, $Asunto, $Contenido, $Correo, $Nombres) {
        $smtp = new PHPMailer();

        # Indicamos que vamos a utilizar un servidor SMTP
        $smtp->IsSMTP();

        # Definimos el formato del correo con UTF-8
        $smtp->CharSet = "UTF-8";
        # autenticación contra nuestro servidor smtp
        // $smtp->SMTPDebug = 2;
        //         $smtp->Debugoutput = 'html';
        $smtp->WordWrap = 70; // set word wrap
        $smtp->isHTML(true); // set word wrap
        // $smtp->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        # autenticación contra nuestro servidor smtp
        $smtp->Port = 587;
        $smtp->Host = 'smtp.gmail.com';   // sets MAIL as the SMTP server
        $smtp->SMTPAuth = true;  // authentication enabled
        $smtp->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
        $smtp->SMTPAutoTLS = false;

        $smtp->Username = $EmpresaObj->CorreoSmtp; // MAIL username
        $smtp->Password = $EmpresaObj->PasswordSmtp;   // MAIL password
        # datos de quien realiza el envio
        $smtp->From = $EmpresaObj->CorreoSmtp; // from mail
        $smtp->FromName = $EmpresaObj->Nombre; // from mail name
        # Indicamos las direcciones donde enviar el mensaje con el formato
        #   "correo"=>"nombre usuario"
        # Se pueden poner tantos correos como se deseen
        $mailTo = array(
            $Correo => $this->sanear_string($Nombres),
        );

        # establecemos un limite de caracteres de anchura
        # NOTA: Los correos es conveniente enviarlos en formato HTML y Texto para que
        # cualquier programa de correo pueda leerlo.
        # Definimos el contenido HTML del correo
        $contenidoHTML = '<!DOCTYPE html><html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta charset="utf-8"><meta name="viewport" content="width=device-width"><meta http-equiv="X-UA-Compatible" content="IE=edge"><title></title></head><body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#f4f4f4" style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; height: 100% !important; width: 100% !important; font-family: Arial, sans-serif; color: #024457; background: #f2f2f2; margin: 0; padding: 0;"><style type="text/css">@media screen and (max-device-width: 600px){table[class="email-container"]{width: 100% !important;}table[class="fluid"]{width: 100% !important;}img[class="fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{margin: auto !important;}td[class="force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: left; margin: 0 15px 15px 0;}img[class="col-3-img-r"]{float: right; margin: 0 0 15px 15px;}table[class="button"]{width: 100% !important;}}@media screen and (max-width: 600px){table[class="email-container"]{width: 100% !important;}table[class="fluid"]{width: 100% !important;}img[class="fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{margin: auto !important;}td[class="force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: left; margin: 0 15px 15px 0;}img[class="col-3-img-r"]{float: right; margin: 0 0 15px 15px;}table[class="button"]{width: 100% !important;}}@media screen and (max-device-width: 425px){div[class="hh-visible"]{display: block !important;}div[class="hh-center"]{text-align: center; width: 100% !important;}table[class="hh-fluid"]{width: 100% !important;}img[class="hh-fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{margin: auto !important;}td[class="hh-force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: none !important; margin: 15px auto !important; text-align: center !important;}img[class="col-3-img-r"]{float: none !important; margin: 15px auto !important; text-align: center !important;}}@media screen and (max-width: 425px){div[class="hh-visible"]{display: block !important;}div[class="hh-center"]{text-align: center; width: 100% !important;}table[class="hh-fluid"]{width: 100% !important;}img[class="hh-fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{margin: auto !important;}td[class="hh-force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: none !important; margin: 15px auto !important; text-align: center !important;}img[class="col-3-img-r"]{float: none !important; margin: 15px auto !important; text-align: center !important;}}@media (min-width: 480px){.responstable th:nth-child(2) span{display: block;}.responstable th:nth-child(2):after{display: none;}.responstable td{border: 1px solid #D9E4E6;}.responstable th{display: table-cell; padding: 1em;}.responstable td{display: table-cell; padding: 1em;}}</style><table cellpadding="0" cellspacing="0" border="0" height="100%" width="100%" bgcolor="#f4f4f4" id="bodyTable" style="border-collapse: collapse; table-layout: fixed; height: 100% !important; width: 100% !important; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: 0 auto; padding: 0;"><tr><td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><div style="display: none; visibility: hidden; opacity: 0; color: transparent; height: 0; width: 0; line-height: 0; overflow: hidden; mso-hide: all;">Mensaje enviado de forma automatica, por favor no responder a este mensaje.</div><table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" bgcolor="#00a0e3" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tr><td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><table border="0" width="600" cellpadding="0" cellspacing="0" align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: auto;" class="email-container"><tr><td height="10" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr><tr><td class="hh-force-col-center" valign="middle" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" colspan="2" align="center"><img src="{{Logo}}" alt="alt text" width="400" border="0" style="-ms-interpolation-mode: bicubic; outline: none; text-decoration: none; border: 0;"></td></tr><tr><td height="10" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr></table></td></tr></table><table border="0" width="80%" cellpadding="0" cellspacing="0" align="center" class="email-container" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: auto;"><tr><td height="30" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr><tr><td height="30" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr></table><table border="0" width="80%" cellpadding="0" cellspacing="0" align="center" bgcolor="#ffffff" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: auto; border: 1px solid #e5e5e5;" class="email-container"><tr><td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tr><td style="font-family: sans-serif; font-size: 16px; line-height: 22px; color: #444444; text-align: justify; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 30px;" align="justify">{{CONTENIDO}}</td></tr></table></td></tr><tr><td valign="middle" align="center" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 10px 0;"></td></tr><tr><td valign="middle" align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr><tr style="border-bottom-width: 1px; border-bottom-color: #e5e5e5; border-bottom-style: solid;"><td valign="middle" align="center" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 10px 0;"></td></tr></table><table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" class="email-container" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tr><td style="text-align: center; font-family: sans-serif; font-size: 12px; line-height: 18px; color: #888888; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 20px;" align="center">{{Empresa}};{{Direccion}}<span class="mobile_link" style="color: #222222 !important; text-decoration: underline !important;">Tel:{{Telefono}}</span><br><a href="http://{{SitioWeb}}" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">{{SitioWeb}}</a><br><br></td></tr></table></td></tr></table></body></html>';
        $contenidoHTML = str_replace("{{CONTENIDO}}", $Contenido, $contenidoHTML);
        # Definimos el subject
        $smtp->Subject = $this->sanear_string($Asunto);

        # Adjuntamos el archivo "leameLWP.txt" al correo.
        # Obtenemos la ruta absoluta de donde se ejecuta este script para encontrar el
        # archivo leameLWP.txt para adjuntar. Por ejemplo, si estamos ejecutando nuestro
        # script en: /home/xve/test/sendMail.php, nos interesa obtener unicamente:
        # /home/xve/test para posteriormente adjuntar el archivo leameLWP.txt, quedando
        # /home/xve/test/leameLWP.txt
        #$rutaAbsoluta=substr($_SERVER["SCRIPT_FILENAME"],0,strrpos($_SERVER["SCRIPT_FILENAME"],"/"));
        #$smtp->AddAttachment($rutaAbsoluta."/leameLWP.txt", "LeameLWP.txt");
        # Indicamos el contenido

        $contenidoHTML = str_replace("{{Empresa}}", $EmpresaObj->Nombre, $contenidoHTML);
        $contenidoHTML = str_replace("{{Direccion}}", $EmpresaObj->Direccion, $contenidoHTML);
        $contenidoHTML = str_replace("{{Telefono}}", $EmpresaObj->Telefono, $contenidoHTML);
        $contenidoHTML = str_replace("{{SitioWeb}}", $EmpresaObj->SitioWeb, $contenidoHTML);
        $contenidoHTML = str_replace("{{Logo}}", $EmpresaObj->Logo, $contenidoHTML);
        #$smtp->AltBody = $contenidoTexto; //Text Body
        $smtp->MsgHTML($contenidoHTML); //Text body HTML

        foreach ($mailTo as $mail => $name) {
            $smtp->ClearAllRecipients();
            $smtp->AddAddress($mail, $name);

            if (!$smtp->Send()) {
                return "Error (" . $mail . "): " . $smtp->ErrorInfo;
            } else {
                return "Envio realizado a " . $name . " (" . $mail . ")";
            }
        }
    }

    public function EnviarEmail_NotificacionMULTIPLE(object $EmpresaObj, string $Asunto, string $Contenido, array $mailTo, ?array $Adjuntos) {
        $smtp = new PHPMailer();

        # Indicamos que vamos a utilizar un servidor SMTP
        $smtp->IsSMTP();

        # Definimos el formato del correo con UTF-8
        $smtp->CharSet = "UTF-8";
        # autenticación contra nuestro servidor smtp
        // $smtp->SMTPDebug = 2;
        //         $smtp->Debugoutput = 'html';
        $smtp->WordWrap = 70; // set word wrap
        $smtp->isHTML(true); // set word wrap
        // $smtp->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        # autenticación contra nuestro servidor smtp
        $smtp->Port = 587;
        $smtp->Host = 'smtp.gmail.com';   // sets MAIL as the SMTP server
        $smtp->SMTPAuth = true;  // authentication enabled
        $smtp->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
        $smtp->SMTPAutoTLS = false;

        $smtp->Username = $EmpresaObj->CorreoSmtp; // MAIL username
        $smtp->Password = $EmpresaObj->PasswordSmtp;   // MAIL password
        # datos de quien realiza el envio
        $smtp->From = $EmpresaObj->CorreoSmtp; // from mail
        $smtp->FromName = $EmpresaObj->Nombre; // from mail name
        # Indicamos las direcciones donde enviar el mensaje con el formato
        #   "correo"=>"nombre usuario"
        # establecemos un limite de caracteres de anchura
        # NOTA: Los correos es conveniente enviarlos en formato HTML y Texto para que
        # cualquier programa de correo pueda leerlo.
        # Definimos el contenido HTML del correo
        $contenidoHTML = '<!DOCTYPE html><html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta charset="utf-8"><meta name="viewport" content="width=device-width"><meta http-equiv="X-UA-Compatible" content="IE=edge"><title></title></head><body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#f4f4f4" style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; height: 100% !important; width: 100% !important; font-family: Arial, sans-serif; color: #024457; background: #f2f2f2; margin: 0; padding: 0;"><style type="text/css">@media screen and (max-device-width: 600px){table[class="email-container"]{width: 100% !important;}table[class="fluid"]{width: 100% !important;}img[class="fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{margin: auto !important;}td[class="force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: left; margin: 0 15px 15px 0;}img[class="col-3-img-r"]{float: right; margin: 0 0 15px 15px;}table[class="button"]{width: 100% !important;}}@media screen and (max-width: 600px){table[class="email-container"]{width: 100% !important;}table[class="fluid"]{width: 100% !important;}img[class="fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{margin: auto !important;}td[class="force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: left; margin: 0 15px 15px 0;}img[class="col-3-img-r"]{float: right; margin: 0 0 15px 15px;}table[class="button"]{width: 100% !important;}}@media screen and (max-device-width: 425px){div[class="hh-visible"]{display: block !important;}div[class="hh-center"]{text-align: center; width: 100% !important;}table[class="hh-fluid"]{width: 100% !important;}img[class="hh-fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{margin: auto !important;}td[class="hh-force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: none !important; margin: 15px auto !important; text-align: center !important;}img[class="col-3-img-r"]{float: none !important; margin: 15px auto !important; text-align: center !important;}}@media screen and (max-width: 425px){div[class="hh-visible"]{display: block !important;}div[class="hh-center"]{text-align: center; width: 100% !important;}table[class="hh-fluid"]{width: 100% !important;}img[class="hh-fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{margin: auto !important;}td[class="hh-force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: none !important; margin: 15px auto !important; text-align: center !important;}img[class="col-3-img-r"]{float: none !important; margin: 15px auto !important; text-align: center !important;}}@media (min-width: 480px){.responstable th:nth-child(2) span{display: block;}.responstable th:nth-child(2):after{display: none;}.responstable td{border: 1px solid #D9E4E6;}.responstable th{display: table-cell; padding: 1em;}.responstable td{display: table-cell; padding: 1em;}}</style><table cellpadding="0" cellspacing="0" border="0" height="100%" width="100%" bgcolor="#f4f4f4" id="bodyTable" style="border-collapse: collapse; table-layout: fixed; height: 100% !important; width: 100% !important; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: 0 auto; padding: 0;"><tr><td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><div style="display: none; visibility: hidden; opacity: 0; color: transparent; height: 0; width: 0; line-height: 0; overflow: hidden; mso-hide: all;">Mensaje enviado de forma automatica, por favor no responder a este mensaje.</div><table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" bgcolor="#00a0e3" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tr><td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><table border="0" width="600" cellpadding="0" cellspacing="0" align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: auto;" class="email-container"><tr><td height="10" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr><tr><td class="hh-force-col-center" valign="middle" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" colspan="2" align="center"><img src="{{Logo}}" alt="alt text" width="400" border="0" style="-ms-interpolation-mode: bicubic; outline: none; text-decoration: none; border: 0;"></td></tr><tr><td height="10" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr></table></td></tr></table><table border="0" width="80%" cellpadding="0" cellspacing="0" align="center" class="email-container" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: auto;"><tr><td height="30" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr><tr><td height="30" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr></table><table border="0" width="80%" cellpadding="0" cellspacing="0" align="center" bgcolor="#ffffff" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: auto; border: 1px solid #e5e5e5;" class="email-container"><tr><td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tr><td style="font-family: sans-serif; font-size: 16px; line-height: 22px; color: #444444; text-align: justify; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 30px;" align="justify">{{CONTENIDO}}</td></tr></table></td></tr><tr><td valign="middle" align="center" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 10px 0;"></td></tr><tr><td valign="middle" align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr><tr style="border-bottom-width: 1px; border-bottom-color: #e5e5e5; border-bottom-style: solid;"><td valign="middle" align="center" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 10px 0;"></td></tr></table><table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" class="email-container" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tr><td style="text-align: center; font-family: sans-serif; font-size: 12px; line-height: 18px; color: #888888; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 20px;" align="center">{{Empresa}};{{Direccion}}<span class="mobile_link" style="color: #222222 !important; text-decoration: underline !important;">Tel:{{Telefono}}</span><br><a href="http://{{SitioWeb}}" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">{{SitioWeb}}</a><br><br></td></tr></table></td></tr></table></body></html>';
        $contenidoHTML = str_replace("{{CONTENIDO}}", $Contenido, $contenidoHTML);
        # Definimos el subject
        $smtp->Subject = $this->sanear_string($Asunto);

        # Adjuntamos el archivo "leameLWP.txt" al correo.
        # Obtenemos la ruta absoluta de donde se ejecuta este script para encontrar el
        # archivo leameLWP.txt para adjuntar. Por ejemplo, si estamos ejecutando nuestro
        # script en: /home/xve/test/sendMail.php, nos interesa obtener unicamente:
        # /home/xve/test para posteriormente adjuntar el archivo leameLWP.txt, quedando
        # /home/xve/test/leameLWP.txt
        #$rutaAbsoluta=substr($_SERVER["SCRIPT_FILENAME"],0,strrpos($_SERVER["SCRIPT_FILENAME"],"/"));
        foreach ($Adjuntos as $f) {
            if (is_string($f)) {
                $smtp->addStringAttachment($f, 'Solicitud.pdf', 'base64', 'application/pdf');
            }
        }
        
        # Indicamos el contenido
        $contenidoHTML = str_replace("{{Empresa}}", $EmpresaObj->Nombre, $contenidoHTML);
        $contenidoHTML = str_replace("{{Direccion}}", $EmpresaObj->Direccion, $contenidoHTML);
        $contenidoHTML = str_replace("{{Telefono}}", $EmpresaObj->Telefono, $contenidoHTML);
        $contenidoHTML = str_replace("{{SitioWeb}}", $EmpresaObj->SitioWeb, $contenidoHTML);
        $contenidoHTML = str_replace("{{Logo}}", $EmpresaObj->Logo, $contenidoHTML);
        #$smtp->AltBody = $contenidoTexto; //Text Body
        $smtp->MsgHTML($contenidoHTML); //Text body HTML
        $CorreosMsg = "";       
        foreach ($mailTo as $mail) {
            $smtp->ClearAllRecipients();
            $smtp->AddAddress($mail);

            if (!$smtp->Send()) {
                $CorreosMsg .= "{$this->getDatetimeNow()}: Error (" . $mail . "): " . $smtp->ErrorInfo . "\n";
            } else {
                $CorreosMsg .= "{$this->getDatetimeNow()}: Envio realizado a (" . $mail . ")" . "\n";
            }
        }
        return $CorreosMsg;
    }

    public function EnviarEmail_Notificacion_pdf($EmpresaObj, $Asunto, $Contenido, $Correo, $Nombres, $pdf) {
        $smtp = new PHPMailer();

        # Indicamos que vamos a utilizar un servidor SMTP
        $smtp->IsSMTP();

        # Definimos el formato del correo con UTF-8
        $smtp->CharSet = "UTF-8";
        # autenticación contra nuestro servidor smtp
        // $smtp->SMTPDebug = 2;
        //         $smtp->Debugoutput = 'html';
        $smtp->WordWrap = 70; // set word wrap
        $smtp->isHTML(true); // set word wrap
        // $smtp->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        # autenticación contra nuestro servidor smtp
        $smtp->Port = 587;
        $smtp->Host = 'smtp.gmail.com';   // sets MAIL as the SMTP server
        $smtp->SMTPAuth = true;  // authentication enabled
        $smtp->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
        $smtp->SMTPAutoTLS = false;

        $smtp->Username = $EmpresaObj->CorreoSmtp; // MAIL username
        $smtp->Password = $EmpresaObj->PasswordSmtp;   // MAIL password
        # datos de quien realiza el envio
        $smtp->From = $EmpresaObj->CorreoSmtp; // from mail
        $smtp->FromName = $EmpresaObj->Nombre; // from mail name
        # Indicamos las direcciones donde enviar el mensaje con el formato
        #   "correo"=>"nombre usuario"
        # Se pueden poner tantos correos como se deseen
        $mailTo = array(
            $Correo => $this->sanear_string($Nombres),
        );

        # establecemos un limite de caracteres de anchura
        # NOTA: Los correos es conveniente enviarlos en formato HTML y Texto para que
        # cualquier programa de correo pueda leerlo.
        # Definimos el contenido HTML del correo
        $contenidoHTML = '<!DOCTYPE html><html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta charset="utf-8"><meta name="viewport" content="width=device-width"><meta http-equiv="X-UA-Compatible" content="IE=edge"><title></title></head><body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#f4f4f4" style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; height: 100% !important; width: 100% !important; font-family: Arial, sans-serif; color: #024457; background: #f2f2f2; margin: 0; padding: 0;"><style type="text/css">@media screen and (max-device-width: 600px){table[class="email-container"]{width: 100% !important;}table[class="fluid"]{width: 100% !important;}img[class="fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{margin: auto !important;}td[class="force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: left; margin: 0 15px 15px 0;}img[class="col-3-img-r"]{float: right; margin: 0 0 15px 15px;}table[class="button"]{width: 100% !important;}}@media screen and (max-width: 600px){table[class="email-container"]{width: 100% !important;}table[class="fluid"]{width: 100% !important;}img[class="fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{margin: auto !important;}td[class="force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: left; margin: 0 15px 15px 0;}img[class="col-3-img-r"]{float: right; margin: 0 0 15px 15px;}table[class="button"]{width: 100% !important;}}@media screen and (max-device-width: 425px){div[class="hh-visible"]{display: block !important;}div[class="hh-center"]{text-align: center; width: 100% !important;}table[class="hh-fluid"]{width: 100% !important;}img[class="hh-fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{margin: auto !important;}td[class="hh-force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: none !important; margin: 15px auto !important; text-align: center !important;}img[class="col-3-img-r"]{float: none !important; margin: 15px auto !important; text-align: center !important;}}@media screen and (max-width: 425px){div[class="hh-visible"]{display: block !important;}div[class="hh-center"]{text-align: center; width: 100% !important;}table[class="hh-fluid"]{width: 100% !important;}img[class="hh-fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{margin: auto !important;}td[class="hh-force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: none !important; margin: 15px auto !important; text-align: center !important;}img[class="col-3-img-r"]{float: none !important; margin: 15px auto !important; text-align: center !important;}}@media (min-width: 480px){.responstable th:nth-child(2) span{display: block;}.responstable th:nth-child(2):after{display: none;}.responstable td{border: 1px solid #D9E4E6;}.responstable th{display: table-cell; padding: 1em;}.responstable td{display: table-cell; padding: 1em;}}</style><table cellpadding="0" cellspacing="0" border="0" height="100%" width="100%" bgcolor="#f4f4f4" id="bodyTable" style="border-collapse: collapse; table-layout: fixed; height: 100% !important; width: 100% !important; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: 0 auto; padding: 0;"><tr><td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><div style="display: none; visibility: hidden; opacity: 0; color: transparent; height: 0; width: 0; line-height: 0; overflow: hidden; mso-hide: all;">Mensaje enviado de forma automatica, por favor no responder a este mensaje.</div><table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" bgcolor="#00a0e3" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tr><td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><table border="0" width="600" cellpadding="0" cellspacing="0" align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: auto;" class="email-container"><tr><td height="10" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr><tr><td class="hh-force-col-center" valign="middle" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" colspan="2" align="center"><img src="{{Logo}}" alt="alt text" width="400" border="0" style="-ms-interpolation-mode: bicubic; outline: none; text-decoration: none; border: 0;"></td></tr><tr><td height="10" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr></table></td></tr></table><table border="0" width="80%" cellpadding="0" cellspacing="0" align="center" class="email-container" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: auto;"><tr><td height="30" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr><tr><td height="30" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr></table><table border="0" width="80%" cellpadding="0" cellspacing="0" align="center" bgcolor="#ffffff" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: auto; border: 1px solid #e5e5e5;" class="email-container"><tr><td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tr><td style="font-family: sans-serif; font-size: 16px; line-height: 22px; color: #444444; text-align: justify; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 30px;" align="justify">{{CONTENIDO}}</td></tr></table></td></tr><tr><td valign="middle" align="center" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 10px 0;"></td></tr><tr><td valign="middle" align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr><tr style="border-bottom-width: 1px; border-bottom-color: #e5e5e5; border-bottom-style: solid;"><td valign="middle" align="center" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 10px 0;"></td></tr></table><table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" class="email-container" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tr><td style="text-align: center; font-family: sans-serif; font-size: 12px; line-height: 18px; color: #888888; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 20px;" align="center">{{Empresa}};{{Direccion}}<span class="mobile_link" style="color: #222222 !important; text-decoration: underline !important;">Tel:{{Telefono}}</span><br><a href="http://{{SitioWeb}}" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">{{SitioWeb}}</a><br><br></td></tr></table></td></tr></table></body></html>';
        $contenidoHTML = str_replace("{{CONTENIDO}}", $Contenido, $contenidoHTML);
        # Definimos el subject
        $smtp->Subject = $this->sanear_string($Asunto);

        # Adjuntamos el archivo "leameLWP.txt" al correo.
        # Obtenemos la ruta absoluta de donde se ejecuta este script para encontrar el
        # archivo leameLWP.txt para adjuntar. Por ejemplo, si estamos ejecutando nuestro
        # script en: /home/xve/test/sendMail.php, nos interesa obtener unicamente:
        # /home/xve/test para posteriormente adjuntar el archivo leameLWP.txt, quedando
        # /home/xve/test/leameLWP.txt
        #$rutaAbsoluta=substr($_SERVER["SCRIPT_FILENAME"],0,strrpos($_SERVER["SCRIPT_FILENAME"],"/"));
        #$smtp->AddAttachment($rutaAbsoluta."/leameLWP.txt", "LeameLWP.txt");
        if (!empty($pdf)) {
                $smtp->AddStringAttachment($pdf, 'Entrega.pdf', 'base64', 'application/pdf');
        }
        # Indicamos el contenido
        $contenidoHTML = str_replace("{{Empresa}}", $EmpresaObj->Nombre, $contenidoHTML);
        $contenidoHTML = str_replace("{{Direccion}}", $EmpresaObj->Direccion, $contenidoHTML);
        $contenidoHTML = str_replace("{{Telefono}}", $EmpresaObj->Telefono, $contenidoHTML);
        $contenidoHTML = str_replace("{{SitioWeb}}", $EmpresaObj->SitioWeb, $contenidoHTML);
        $contenidoHTML = str_replace("{{Logo}}", $EmpresaObj->Logo, $contenidoHTML);
        #$smtp->AltBody = $contenidoTexto; //Text Body
        $smtp->MsgHTML($contenidoHTML); //Text body HTML

        foreach ($mailTo as $mail => $name) {
            $smtp->ClearAllRecipients();
            $smtp->AddAddress($mail, $name);

            if (!$smtp->Send()) {
                return "Error (" . $mail . "): " . $smtp->ErrorInfo;
            } else {
                return "Envio realizado a " . $name . " (" . $mail . ")";
            }
        }
    }

    public function EnviarEmail_Bienvenida($EmpresaObj, $Nombre, $Email, $Password, $Asunto) {
        $smtp = new PHPMailer();

# Indicamos que vamos a utilizar un servidor SMTP
        $smtp->IsSMTP();

# Definimos el formato del correo con UTF-8
        $smtp->CharSet = "UTF-8";
# autenticación contra nuestro servidor smtp
        $smtp->SMTPAuth = true;      // enable SMTP authentication
        #$smtp->SMTPSecure = 'tls';


        $smtp->Port = $EmpresaObj->PuertoSmtp;
        $smtp->Host = $EmpresaObj->SMTP;   // sets MAIL as the SMTP server
        $smtp->Username = $EmpresaObj->CorreoSmtp; // MAIL username
        $smtp->Password = $EmpresaObj->PasswordSmtp;   // MAIL password
# datos de quien realiza el envio
        $smtp->From = $EmpresaObj->CorreoSmtp; // from mail
        $smtp->FromName = $EmpresaObj->Nombre; // from mail name
# Indicamos las direcciones donde enviar el mensaje con el formato
#   "correo"=>"nombre usuario"
# Se pueden poner tantos correos como se deseen
        $mailTo = array(
            $Email => $this->sanear_string($Nombre),
        );

# establecemos un limite de caracteres de anchura
        $smtp->WordWrap = 50; // set word wrap
# NOTA: Los correos es conveniente enviarlos en formato HTML y Texto para que
# cualquier programa de correo pueda leerlo.
# Definimos el contenido HTML del correo
        $contenido = "
            <h2>Bienvenido a Mant. Polivalente</h2>
            <p>
                Tu Usuario es: <strong>$Email</strong><br/>
                Tu Contraseña es: <strong>$Password</strong><br/>
                <br/>
                Recuerda cambiar tu clave. 
                Puedes Ingresar haciendo clic en el siguiente enlace <a href='http://192.168.8.125:8080/Polivalente/'>http://192.168.8.125:8080/Polivalente/</a>
            </p>";
        $contenidoHTML = '<!DOCTYPE html><html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta charset="utf-8"><meta name="viewport" content="width=device-width"><meta http-equiv="X-UA-Compatible" content="IE=edge"><title></title></head><body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#f4f4f4" style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; height: 100% !important; width: 100% !important; font-family: Arial, sans-serif; color: #024457; background: #f2f2f2; margin: 0; padding: 0;"><style type="text/css">@media screen and (max-device-width: 600px){table[class="email-container"]{width: 100% !important;}table[class="fluid"]{width: 100% !important;}img[class="fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{margin: auto !important;}td[class="force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: left; margin: 0 15px 15px 0;}img[class="col-3-img-r"]{float: right; margin: 0 0 15px 15px;}table[class="button"]{width: 100% !important;}}@media screen and (max-width: 600px){table[class="email-container"]{width: 100% !important;}table[class="fluid"]{width: 100% !important;}img[class="fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{margin: auto !important;}td[class="force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: left; margin: 0 15px 15px 0;}img[class="col-3-img-r"]{float: right; margin: 0 0 15px 15px;}table[class="button"]{width: 100% !important;}}@media screen and (max-device-width: 425px){div[class="hh-visible"]{display: block !important;}div[class="hh-center"]{text-align: center; width: 100% !important;}table[class="hh-fluid"]{width: 100% !important;}img[class="hh-fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{margin: auto !important;}td[class="hh-force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: none !important; margin: 15px auto !important; text-align: center !important;}img[class="col-3-img-r"]{float: none !important; margin: 15px auto !important; text-align: center !important;}}@media screen and (max-width: 425px){div[class="hh-visible"]{display: block !important;}div[class="hh-center"]{text-align: center; width: 100% !important;}table[class="hh-fluid"]{width: 100% !important;}img[class="hh-fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{margin: auto !important;}td[class="hh-force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: none !important; margin: 15px auto !important; text-align: center !important;}img[class="col-3-img-r"]{float: none !important; margin: 15px auto !important; text-align: center !important;}}@media (min-width: 480px){.responstable th:nth-child(2) span{display: block;}.responstable th:nth-child(2):after{display: none;}.responstable td{border: 1px solid #D9E4E6;}.responstable th{display: table-cell; padding: 1em;}.responstable td{display: table-cell; padding: 1em;}}</style><table cellpadding="0" cellspacing="0" border="0" height="100%" width="100%" bgcolor="#f4f4f4" id="bodyTable" style="border-collapse: collapse; table-layout: fixed; height: 100% !important; width: 100% !important; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: 0 auto; padding: 0;"><tr><td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><div style="display: none; visibility: hidden; opacity: 0; color: transparent; height: 0; width: 0; line-height: 0; overflow: hidden; mso-hide: all;">Mensaje enviado de forma automatica, por favor no responder a este mensaje.</div><table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" bgcolor="#00a0e3" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tr><td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><table border="0" width="600" cellpadding="0" cellspacing="0" align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: auto;" class="email-container"><tr><td height="10" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr><tr><td class="hh-force-col-center" valign="middle" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" colspan="2" align="center"><img src="{{Logo}}" alt="alt text" width="400" border="0" style="-ms-interpolation-mode: bicubic; outline: none; text-decoration: none; border: 0;"></td></tr><tr><td height="10" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr></table></td></tr></table><table border="0" width="80%" cellpadding="0" cellspacing="0" align="center" class="email-container" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: auto;"><tr><td height="30" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr><tr><td height="30" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr></table><table border="0" width="80%" cellpadding="0" cellspacing="0" align="center" bgcolor="#ffffff" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: auto; border: 1px solid #e5e5e5;" class="email-container"><tr><td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tr><td style="font-family: sans-serif; font-size: 16px; line-height: 22px; color: #444444; text-align: justify; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 30px;" align="justify">{{CONTENIDO}}</td></tr></table></td></tr><tr><td valign="middle" align="center" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 10px 0;"></td></tr><tr><td valign="middle" align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr><tr style="border-bottom-width: 1px; border-bottom-color: #e5e5e5; border-bottom-style: solid;"><td valign="middle" align="center" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 10px 0;"></td></tr></table><table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" class="email-container" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tr><td style="text-align: center; font-family: sans-serif; font-size: 12px; line-height: 18px; color: #888888; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 20px;" align="center">{{Empresa}};{{Direccion}}<span class="mobile_link" style="color: #222222 !important; text-decoration: underline !important;">Tel:{{Telefono}}</span><br><a href="http://{{SitioWeb}}" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">{{SitioWeb}}</a><br><br></td></tr></table></td></tr></table></body></html>';
        $contenidoHTML = str_replace("{{CONTENIDO}}", $contenido, $contenidoHTML);
# Definimos el subject
        $smtp->Subject = $Asunto;

# Adjuntamos el archivo "leameLWP.txt" al correo.
# Obtenemos la ruta absoluta de donde se ejecuta este script para encontrar el
# archivo leameLWP.txt para adjuntar. Por ejemplo, si estamos ejecutando nuestro
# script en: /home/xve/test/sendMail.php, nos interesa obtener unicamente:
# /home/xve/test para posteriormente adjuntar el archivo leameLWP.txt, quedando
# /home/xve/test/leameLWP.txt
#$rutaAbsoluta=substr($_SERVER["SCRIPT_FILENAME"],0,strrpos($_SERVER["SCRIPT_FILENAME"],"/"));
#$smtp->AddAttachment($rutaAbsoluta."/leameLWP.txt", "LeameLWP.txt");
# Indicamos el contenido

        $contenidoHTML = str_replace("{{Empresa}}", $EmpresaObj->Nombre, $contenidoHTML);
        $contenidoHTML = str_replace("{{Direccion}}", $EmpresaObj->Direccion, $contenidoHTML);
        $contenidoHTML = str_replace("{{Telefono}}", $EmpresaObj->Telefono, $contenidoHTML);
        $contenidoHTML = str_replace("{{SitioWeb}}", $EmpresaObj->SitioWeb, $contenidoHTML);
        $contenidoHTML = str_replace("{{Logo}}", $EmpresaObj->Logo, $contenidoHTML);
        #$smtp->AltBody = $contenidoTexto; //Text Body
        $smtp->MsgHTML($contenidoHTML); //Text body HTML

        foreach ($mailTo as $mail => $name) {
            $smtp->ClearAllRecipients();
            $smtp->AddAddress($mail, $name);

            if (!$smtp->Send()) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

// </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="SST">

    public function EnviarEmail_NotificacionExtintores($EmpresaObj, $Asunto, $Contenido, $Correo, $Nombres) {
        $smtp = new PHPMailer();

# Indicamos que vamos a utilizar un servidor SMTP
        $smtp->IsSMTP();

# Definimos el formato del correo con UTF-8
        $smtp->CharSet = "UTF-8";
# autenticación contra nuestro servidor smtp
        $smtp->SMTPAuth = true;      // enable SMTP authentication
        #$smtp->SMTPSecure = 'tls';


        $smtp->Port = $EmpresaObj->PuertoSmtp;
        $smtp->Host = $EmpresaObj->SMTP;   // sets MAIL as the SMTP server
        $smtp->Username = $EmpresaObj->CorreoSmtp; // MAIL username
        $smtp->Password = $EmpresaObj->PasswordSmtp;   // MAIL password
# datos de quien realiza el envio
        $smtp->From = $EmpresaObj->CorreoSmtp; // from mail
        $smtp->FromName = $EmpresaObj->Nombre; // from mail name
# Indicamos las direcciones donde enviar el mensaje con el formato
#   "correo"=>"nombre usuario"
# Se pueden poner tantos correos como se deseen
        $mailTo = array(
            $Correo => $this->sanear_string($Nombres),
        );

# establecemos un limite de caracteres de anchura
        $smtp->WordWrap = 50; // set word wrap
# NOTA: Los correos es conveniente enviarlos en formato HTML y Texto para que
# cualquier programa de correo pueda leerlo.
# Definimos el contenido HTML del correo
        $contenidoHTML = '<!DOCTYPE html><html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta charset="utf-8"><meta name="viewport" content="width=device-width"><meta http-equiv="X-UA-Compatible" content="IE=edge"><title></title></head><body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#f4f4f4" style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; height: 100% !important; width: 100% !important; font-family: Arial, sans-serif; color: #024457; background: #f2f2f2; margin: 0; padding: 0;"><style type="text/css">@media screen and (max-device-width: 600px){table[class="email-container"]{width: 100% !important;}table[class="fluid"]{width: 100% !important;}img[class="fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{margin: auto !important;}td[class="force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: left; margin: 0 15px 15px 0;}img[class="col-3-img-r"]{float: right; margin: 0 0 15px 15px;}table[class="button"]{width: 100% !important;}}@media screen and (max-width: 600px){table[class="email-container"]{width: 100% !important;}table[class="fluid"]{width: 100% !important;}img[class="fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="force-col-center"]{margin: auto !important;}td[class="force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: left; margin: 0 15px 15px 0;}img[class="col-3-img-r"]{float: right; margin: 0 0 15px 15px;}table[class="button"]{width: 100% !important;}}@media screen and (max-device-width: 425px){div[class="hh-visible"]{display: block !important;}div[class="hh-center"]{text-align: center; width: 100% !important;}table[class="hh-fluid"]{width: 100% !important;}img[class="hh-fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{margin: auto !important;}td[class="hh-force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: none !important; margin: 15px auto !important; text-align: center !important;}img[class="col-3-img-r"]{float: none !important; margin: 15px auto !important; text-align: center !important;}}@media screen and (max-width: 425px){div[class="hh-visible"]{display: block !important;}div[class="hh-center"]{text-align: center; width: 100% !important;}table[class="hh-fluid"]{width: 100% !important;}img[class="hh-fluid"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{width: 100% !important; max-width: 100% !important; height: auto !important;}img[class="hh-force-col-center"]{margin: auto !important;}td[class="hh-force-col"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{display: block !important; width: 100% !important; clear: both;}td[class="hh-force-col-center"]{text-align: center !important;}img[class="col-3-img-l"]{float: none !important; margin: 15px auto !important; text-align: center !important;}img[class="col-3-img-r"]{float: none !important; margin: 15px auto !important; text-align: center !important;}}@media (min-width: 480px){.responstable th:nth-child(2) span{display: block;}.responstable th:nth-child(2):after{display: none;}.responstable td{border: 1px solid #D9E4E6;}.responstable th{display: table-cell; padding: 1em;}.responstable td{display: table-cell; padding: 1em;}}</style><table cellpadding="0" cellspacing="0" border="0" height="100%" width="100%" bgcolor="#f4f4f4" id="bodyTable" style="border-collapse: collapse; table-layout: fixed; height: 100% !important; width: 100% !important; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: 0 auto; padding: 0;"><tr><td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><div style="display: none; visibility: hidden; opacity: 0; color: transparent; height: 0; width: 0; line-height: 0; overflow: hidden; mso-hide: all;">Mensaje enviado de forma automatica, por favor no responder a este mensaje.</div><table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" bgcolor="#00a0e3" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tr><td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><table border="0" width="600" cellpadding="0" cellspacing="0" align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: auto;" class="email-container"><tr><td height="10" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr><tr><td class="hh-force-col-center" valign="middle" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" colspan="2" align="center"><img src="{{Logo}}" alt="alt text" width="400" border="0" style="-ms-interpolation-mode: bicubic; outline: none; text-decoration: none; border: 0;"></td></tr><tr><td height="10" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr></table></td></tr></table><table border="0" width="80%" cellpadding="0" cellspacing="0" align="center" class="email-container" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: auto;"><tr><td height="30" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr><tr><td height="30" style="font-size: 0; line-height: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr></table><table border="0" width="80%" cellpadding="0" cellspacing="0" align="center" bgcolor="#ffffff" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: auto; border: 1px solid #e5e5e5;" class="email-container"><tr><td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tr><td style="font-family: sans-serif; font-size: 16px; line-height: 22px; color: #444444; text-align: justify; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 30px;" align="justify">{{CONTENIDO}}</td></tr></table></td></tr><tr><td valign="middle" align="center" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 10px 0;"></td></tr><tr><td valign="middle" align="center" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"> </td></tr><tr style="border-bottom-width: 1px; border-bottom-color: #e5e5e5; border-bottom-style: solid;"><td valign="middle" align="center" style="text-align: center; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 10px 0;"></td></tr></table><table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" class="email-container" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tr><td style="text-align: center; font-family: sans-serif; font-size: 12px; line-height: 18px; color: #888888; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 20px;" align="center">{{Empresa}};{{Direccion}}<span class="mobile_link" style="color: #222222 !important; text-decoration: underline !important;">Tel:{{Telefono}}</span><br><a href="http://{{SitioWeb}}" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">{{SitioWeb}}</a><br><br></td></tr></table></td></tr></table></body></html>';
        $contenidoHTML = str_replace("{{CONTENIDO}}", $Contenido, $contenidoHTML);
# Definimos el subject
        $smtp->Subject = $this->sanear_string($Asunto);

# Adjuntamos el archivo "leameLWP.txt" al correo.
# Obtenemos la ruta absoluta de donde se ejecuta este script para encontrar el
# archivo leameLWP.txt para adjuntar. Por ejemplo, si estamos ejecutando nuestro
# script en: /home/xve/test/sendMail.php, nos interesa obtener unicamente:
# /home/xve/test para posteriormente adjuntar el archivo leameLWP.txt, quedando
# /home/xve/test/leameLWP.txt
#$rutaAbsoluta=substr($_SERVER["SCRIPT_FILENAME"],0,strrpos($_SERVER["SCRIPT_FILENAME"],"/"));
#$smtp->AddAttachment($rutaAbsoluta."/leameLWP.txt", "LeameLWP.txt");
# Indicamos el contenido

        $contenidoHTML = str_replace("{{Empresa}}", $EmpresaObj->Nombre, $contenidoHTML);
        $contenidoHTML = str_replace("{{Direccion}}", $EmpresaObj->Direccion, $contenidoHTML);
        $contenidoHTML = str_replace("{{Telefono}}", $EmpresaObj->Telefono, $contenidoHTML);
        $contenidoHTML = str_replace("{{SitioWeb}}", $EmpresaObj->SitioWeb, $contenidoHTML);
        $contenidoHTML = str_replace("{{Logo}}", $EmpresaObj->Logo, $contenidoHTML);
        #$smtp->AltBody = $contenidoTexto; //Text Body
        $smtp->MsgHTML($contenidoHTML); //Text body HTML

        foreach ($mailTo as $mail => $name) {
            $smtp->ClearAllRecipients();
            $smtp->AddAddress($mail, $name);

            if (!$smtp->Send()) {
                return "<br>Error (" . $mail . "): " . $smtp->ErrorInfo;
            } else {
                return "<br>Envio realizado a " . $name . " (" . $mail . ")";
            }
        }
    }

// </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Helpers">
    function sanear_string($string) {

        $string = trim($string);

        $string = str_replace(
                array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string
        );

        $string = str_replace(
                array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string
        );

        $string = str_replace(
                array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string
        );

        $string = str_replace(
                array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string
        );

        $string = str_replace(
                array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string
        );

        $string = str_replace(
                array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $string
        );

        //Esta parte se encarga de eliminar cualquier caracter extraño
        $string = str_replace(
                array("\\", "¨", "º", "-", "~",
            "·", "$", "%", "&", "/",
            "(", ")", "?", "'", "¡",
            "¿", "[", "^", "<code>", "]",
            "+", "}", "{", "¨", "´",
            ">", "< ", ";", ",", ":",
            "."), '', $string
        );


        return $string;
    }

    function getDatetimeNow() {
        $tz_object = new DateTimeZone('America/Bogota');
        //date_default_timezone_set('Brazil/East');

        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y-m-d h:i:s');
    }

// </editor-fold>
}
