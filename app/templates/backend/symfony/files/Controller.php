<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as SWG;
use App\Service\Command\<%= className %>\PostCommand;
use App\Service\Command\<%= className %>\PutCommand;
use App\Service\Command\<%= className %>\DeleteCommand;
use App\Service\Query\<%= className %>Query;

class <%= className %>Controller extends AbstractApiController
{
    /**
     * 
     * {@inheritDoc}
     * @see \App\Controller\AbstractApiController::post()
     * @Route("/api/<%=_.replace(_.snakeCase(className),"_","-").toLowerCase()%>", name="new<%= className %>", methods={"POST"})
     * @SWG\Post(
     *   path="/api/<%=_.replace(_.snakeCase(className),"_","-").toLowerCase()%>",
     *   summary="Post to URL",
     *   @SWG\Parameter(
     *      name="body",
     *      in="body",
     *      description="JSON Payload",
     *      required=true,
     *      format="application/json",
     *      @SWG\Schema(
     *          type="object",
     *          <% attributs.fields.forEach(function(element, index, elements){ %>
     *          @SWG\Property(
     *              property="<%= element.fieldName %>",
     *              type="string"
     *          ),
     *          <% }); %>
     *      )
     *   )
     * )
     * @SWG\Response(
     *     response=200,
     *     description="API Result",
     * )
     * @param Request $request 
     */
    public function post(Request $request)
    {
        try{
            $content = json_decode($request->getContent(), true);
            $command = new PostCommand($content);
            $data = $this->getServiceBus()->handle($command);
            return $this->json($data, self::SUCCESS);
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), self::BAD_REQUEST);
        }
    }
    /**
     *
     * {@inheritDoc}
     * @see \App\Controller\AbstractApiController::put()
     * @Route("/api/<%=_.replace(_.snakeCase(className),"_","-").toLowerCase()%>/{id}", name="edit<%=className%>", methods={"PUT"})
     * @SWG\Put(
     *   path="/api/<%=_.replace(_.snakeCase(className),"_","-").toLowerCase()%>/{id}",
     *   summary="Put to URL",
     *   @SWG\Parameter(
     *      name="body",
     *      in="body",
     *      description="JSON Payload",
     *      required=true,
     *      format="application/json",
     *      @SWG\Schema(
     *          type="object",
     *          <% attributs.fields.forEach(function(element, index, elements){ %>
     *          @SWG\Property(
     *              property="<%= element.fieldName %>",
     *              type="string"
     *          ),
     *          <% }); %>
     *      )
     *   )
     * )
     * @SWG\Response(
     *     response=200,
     *     description="API Result",
     * )
     * @param int $id
     * @param Request $request
     */
    public function put($id, Request $request)
    {
        try {
            $content = json_decode($request->getContent(), true);
            $command = new PutCommand($content);
            $command->setValue('id', $id);
            $data = $this->getServiceBus()->handle($command);
            return $this->json($data, self::SUCCESS);
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), self::BAD_REQUEST);
        }
    }
    /**
     *
     * {@inheritDoc}
     * @see \App\Controller\AbstractApiController::delete()
     * @Route("/api/<%=_.replace(_.snakeCase(className),"_","-").toLowerCase()%>/{id}", name="delete<%=className%>", methods={"DELETE"})
     */
    public function delete($id, Request $request)
    {
        try {
            $command = new DeleteCommand();
            $command->setValue('id', $id);
            $data = $this->getServiceBus()->handle($command);
            return $this->json($data, self::SUCCESS);
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), self::BAD_REQUEST);
        }
    }

    /**
     * {@inheritDoc}
     * @see \App\Controller\AbstractApiController::findAll()
     * @Route("/api/<%=_.replace(_.snakeCase(className),"_","-").toLowerCase()%>s", name="fildAll<%=className%>", methods={"GET"})
     */
    public function findAll(<%= className %>Query $query, Request $request)
    {
        try{
            $data = $query->search($request->query->all());
            return $this->json($data, self::SUCCESS);
        } catch (\Exception $e){
            return $this->json($e->getMessage(), self::BAD_REQUEST);
        }
    }
    /**
     *
     * {@inheritDoc}
     * @see \App\Controller\AbstractApiController::findById()
     * @Route("/api/<%=_.replace(_.snakeCase(className),"_","-").toLowerCase()%>/{id}", name="findById<%=className%>", methods={"GET"})
     */
    public function findById(int $id, <%= className %>Query $query)
    {
        try{
            $data = $query->findById($id);
            return $this->json($data, self::SUCCESS);
        } catch (\Exception $e){
            return $this->json($e->getMessage(), self::BAD_REQUEST);
        }
    }
}