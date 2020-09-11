<?php
namespace App\Controller;

use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Service\Query\AbstractQuery;
use App\Service\Command\AbstractCommand;

abstract class AbstractApiController  extends AbstractController
{
    /**
     * 
     * @var CommandBus
     */
    protected $command;
    /**
     * 
     * @param CommandBus $command
     */
    public function __construct(CommandBus $command)
    {
        $this->command = $command;
    }
   
    /**
     * 
     * {@inheritDoc}
     * @see \Symfony\Bundle\FrameworkBundle\Controller\AbstractController::json()
     */
    protected function json($data, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        if($data instanceof  ConstraintViolationList){
            return parent::json($data, Response::HTTP_BAD_REQUEST, $headers, $context);
        }
        return parent::json($data, $status, $headers, $context);
    } 

    /**
     * 
     * @return mixed
     */
    public function getServiceBus()
    {
        return $this->command;
    }

    /**
     * @param $query
     * @param Request $request
     * @return JsonResponse
     */
    public function prepareSearch(AbstractQuery $query, Request $request)
    {
        try {
            $data = $query->search($request->query->all());
            return $this->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
    
    /**
     * @param $id
     * @param AbstractQuery $query
     * @return JsonResponse
     */
    public function prepareQueryById($id, AbstractQuery $query)
    {
        try {
            $data = $query->findById($id);
            return $this->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
    
    /**
     * @param Request $request
     * @param $commandClass
     * @return JsonResponse
     */
    public function preparePost(Request $request, AbstractCommand $command)
    {
        try {
            $newRequest = $request->request->all();
            if(empty($newRequest)){
                $newRequest = json_decode($request->getContent());
            }
            foreach ($request->files as $name => $file) {
                $newRequest[$name] = $file;
            }
            $command->setValues($newRequest);
            $data = $this->getServiceBus()->handle($command);
            return $this->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
    
    /**
     * @param $id
     * @param Request $request
     * @param $commandClass
     * @param string $keyProperty
     * @return JsonResponse
     */
    public function preparePut($id, Request $request, AbstractCommand $command, $keyProperty = "id")
    {
        try {
            $content = json_decode($request->getContent(), true);
            $content = empty($content) ? $request->request->all() : $content;
            $command->setValues($content);
            $command->setValue($keyProperty, $id);
            $data = $this->getServiceBus()->handle($command);
            return $this->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
    
    /**
     * @param $id
     * @param $commandClass
     * @param string $keyProperty
     * @return JsonResponse
     */
    public function prepareDelete($id, AbstractCommand $command, $keyProperty = "id")
    {
        try {
            $command->setValue($keyProperty, $id);
            $data = $this->getServiceBus()->handle($command);
            return $this->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
    
    /**
     * @param $id
     * @param $commandClass
     * @return JsonResponse
     */
    public function preparePatch($id, AbstractCommand $command, $keyProperty = "id")
    {
        try {
            $command->setValue($keyProperty, $id);
            $data = $this->getServiceBus()->handle($command);
            return $this->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
    
    abstract public function post(Request $request);
    
    abstract public function put($id, Request $request);
    
    abstract public function delete($id, Request $request);
    
}