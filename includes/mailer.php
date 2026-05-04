<?php
function send_mail($to, $subject, $body) {
    $headers  = "From: noreply@peerlearn.local\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    return mail($to, $subject, $body, $headers);
}
