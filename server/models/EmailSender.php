<?php
/**
 * Created by PhpStorm.
 * User: Paulius
 * Date: 05/11/2017
 * Time: 09:33
 */

class EmailSender
{

    // Sends an HTML email. You can use basic HTML tags. You have to insert
    // new lines using <br /> or paragraphs. Uses the UTF-8 encoding.
    public function send($recipient, $subject, $message, $from)
    {
        $header = "From: " . $from;
        $header .= "\nMIME-Version: 1.0\n";
        $header .= "Content-Type: text/html; charset=\"utf-8\"\n";
        $result = mb_send_mail($recipient, $subject, $message, $header);

         return $result;
    }

}
