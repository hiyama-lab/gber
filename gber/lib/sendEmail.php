<?php
function sendEmail($baseurl, $subject, $messageText, $messageHtml, $mailto)
{

    require __DIR__ . '/../vendor/autoload.php';
    $dotenv = new Dotenv\Dotenv(__DIR__);
    $api_key = $_ENV["SENDGRID_APIKEY"];

    $from = "alert@mail.gber.jp";
    $fromname = "GBER運営";

    $footerText = "\r\n＝＝＝＝＝＝＝＝＝＝＝＝\r\n送信元：GBER運営\r\n" . $baseurl
        . "\r\n＝＝＝＝＝＝＝＝＝＝＝＝\r\n";
    $footerHtml = "<br />＝＝＝＝＝＝＝＝＝＝＝＝<br />送信元：GBER運営<br /><a href=\"" . $baseurl
        . "\">" . $baseurl . "</a><br />＝＝＝＝＝＝＝＝＝＝＝＝";

    $messageText .= $footerText;
    $messageHtml .= $footerHtml;

    $sendgrid = new SendGrid($api_key);
    $email = new SendGrid\Email();
    $email->addTo($mailto)->setFrom($from)->setFromName($fromname)
        ->setSubject("[GBER] $subject")->setText("$messageText\r\n")
        ->setHtml("$messageHtml");

    $response = $sendgrid->send($email);

}

?>
