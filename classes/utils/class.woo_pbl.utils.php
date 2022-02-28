<?php
 trait WooPBLUtils {
     public function views($template_name, $variables = array()) {
        extract($variables);
        require(WOO_PBL_DIR . "$template_name.php");
     }

     public function sendEmail($recipient, $content, $subject) {
        $mailer = \WC()->mailer();
        $headers = "Content-Type: text/html\r\n";
        $mailer->send( $recipient, $subject, $content, $headers );
     }
 }