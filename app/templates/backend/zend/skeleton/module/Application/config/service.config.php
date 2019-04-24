<?php
namespace Application;

use Zend\Mail;

return [
    'factories' => [
        "Application\ApplicationService"=> function($sm){
            return null; # service return of application if exists 
        },
        "Application\Mail" => function ($sm) {
            $options = new Mail\Transport\SmtpOptions([
                "host" => '',
                "port" => ''
            ]);
            return new Mail\Transport\Smtp($options);
        },
    ]
];