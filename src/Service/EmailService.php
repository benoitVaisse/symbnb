<?php 

namespace App\Service;

use Twig\Environment;

class EmailService{

    private $mail;
    private $renderer;

    public function __construct(\Swift_Mailer $mail, Environment $renderer){
        $this->mail = $mail;
        $this->renderer = $renderer;
    }

    public function sendAdminNewAd($ad, $user){
        $message = (new \Swift_Message('Nouvelle annonce'))
        ->setFrom('info@symbnb.com')
        ->setTo('benoit.vaisse@gmail.com')
        ->setBody(
            $this->renderer->render(
                'email/sendAdminNewAdd.html.twig',[
                    'ad' => $ad,
                    'user'=>$user,
                ]
                ),'text/html'
        )
    ;
    $this->mail->send($message);
    }


}