<?php

declare(strict_types=1);

namespace Telegram\Controller;


use Laminas\Json\Json;
use Laminas\Mvc\Controller\AbstractController;
use Laminas\Mvc\MvcEvent;
use Laminas\ServiceManager\ServiceManager;
use Doctrine\ORM\EntityManager;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Telegram\Options\ModuleOptions;
use Longman\TelegramBot\Telegram;


class Service extends AbstractActionController
{
    
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * @var ServiceManager
     */
    protected $serviceManager;
    
    
    /**
     * Categorys constructor.
     *
     * @param EntityManager                $entityManager
     * @param ServiceManager               $serviceManager
     */
    public function __construct(
        EntityManager $entityManager,
        ServiceManager $serviceManager)
    {
        $this->entityManager = $entityManager;
        $this->serviceManager = $serviceManager;
        
    }
    
    /**
     * Запрос на установку хука для запросов от Телеграм
     * @return JsonModel
     */
    public function setHookAction()
    {
        if ($this->isDisabledSet()) {
            $view = new JsonModel();
            $view->setVariables(['response' =>['success' => true,'result' => 'disable']]);
            $view->setTemplate('application/index/json');
            $headers = $this->getResponse()->getHeaders();
            $this->getResponse()->setHeaders($headers->addHeaders(['Content-Type'=>'application/json','X-Powered-By' => 'Bot']));
    
            return $view;
        }

        /** @var \Telegram\Options\ModuleOptions $options */
        $options = $this->serviceManager->get(ModuleOptions::class);
        $viewResult = '';
        try {
            $telegram = new Telegram($options->getApiKey(),$options->getBotUsername());
            $result = $telegram->setWebhook($options->getBootHookUrl());
            if ($result->isOk()) {
                $viewResult =  $result->getDescription();
            }
        } catch (Longman\TelegramBot\Exception\TelegramException $e) {
            $viewResult =  $e->getMessage();
        } finally {
            $view = new JsonModel();
            $view->setVariables(['response' =>['success' => true,'result' => $viewResult]]);
            $view->setTemplate('application/index/json');
            $headers = $this->getResponse()->getHeaders();
            $this->getResponse()->setHeaders($headers->addHeaders(['Content-Type'=>'application/json','X-Powered-By' => 'Bot']));
            
            return $view;
        }
        
        
        
    }
    
    /**
     * Отключен ли метод установки Хука
     * @return bool
     */
    protected function isDisabledSet()
    {
        $config = $this->serviceManager->get('config');
        
        if (!array_key_exists('disableRouteSet',$config[\Telegram\Module::class])) {
            return true;
        }
        
        $isDisable = (boolean)$config[\Telegram\Module::class]['disableRouteSet'];
        return $isDisable;
    }
    
    
    
    
    
}