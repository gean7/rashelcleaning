<?php
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php'; // <- esta es la que faltaba

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: text/plain; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $servicio = trim($_POST['servicio'] ?? '');

    if (!$nombre || !$correo || !$servicio) {
        http_response_code(400);
        echo "❌ Todos los campos son obligatorios.";
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'admin@rashelcleaning.com'; // Usa aquí tu correo de Hostinger, no Gmail
        $mail->Password = 'Sanignacio.1';          // Usa la contraseña SMTP que Hostinger te da
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('admin@rashelcleaning.com', 'Rashel Cleaning');
        $mail->addAddress('admin@rashelcleaning.com', 'Destinatario');
        $mail->addAddress('gescoto9@gmail.com');       // correo personal

        $mail->isHTML(true);
        $mail->Subject = 'Nuevo servicio solicitado desde web';
        $mail->Body = "
            <h2>Solicitud de Servicio</h2>
            <p><strong>Nombre:</strong> {$nombre}</p>
            <p><strong>Correo:</strong> {$correo}</p>
            <p><strong>Servicio:</strong> {$servicio}</p>
        ";

        $mail->send();
        echo "✅ El correo fue enviado correctamente.";
    } catch (Exception $e) {
        http_response_code(500);
        echo "❌ Error al enviar el correo: {$mail->ErrorInfo}";
    }
} else {
    http_response_code(405);
    echo "Método no permitido";
}
