<?php
namespace Application\Service;
use Zend\Mail\Transport\Smtp;

abstract class AppAbstractMail
{
    
    const FROM = "email@dominio.com.br";
    /**
     *
     * @var Smtp
     */
    private $smtp;
    
    /**
     * 
     * @var mixed
     */
    private $view;
    
    /**
     *
     * @var mixed
     */
    private $renderer;
    
    /**
     *
     * @param Smtp $smtp
     */
    public function __construct(Smtp $smtp, $viewModel, $viewRenderer) {
        $this->smtp = $smtp;
        $this->view = $viewModel;
        $this->renderer = $viewRenderer;
    }
} 