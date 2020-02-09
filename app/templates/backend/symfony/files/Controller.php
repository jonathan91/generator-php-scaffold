<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as SWG;
use App\Service\Command\<%= className %>\Post<%= className %>Command;
use App\Service\Command\<%= className %>\Put<%= className %>Command;
use App\Service\Command\<%= className %>\Delete<%= className %>Command;
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
            $command = new PostCommand($request->query->all());
            $data = $this->getServiceBus()->handle($command);
            return $this->json($data, 200);
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 400);
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
            $command = new PutCommand($request->query->all());
            $command->setValue('id', $id);
            $data = $this->getServiceBus()->handle($command);
            return $this->json($data, 200);
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 400);
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
            return $this->json($data, 200);
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 400);
        }
    }

    /**
     * {@inheritDoc}
     * @see \App\Controller\AbstractApiController::findAll()
     * @Route("/api/<%=_.replace(_.snakeCase(className),"_","-").toLowerCase()%>s", name="fildAll<%=className%>", methods={"GET"})
     */
    public function findAll(DocumentoQuery $query)
    {
        try{
            $data = $query->search([]);
            return $this->json($data, 200);
        } catch (\Exception $e){
            return $this->json($e->getMessage(), 400);
        }
    }
    /**
     *
     * {@inheritDoc}
     * @see \App\Controller\AbstractApiController::findById()
     * @Route("/api/<%=_.replace(_.snakeCase(className),"_","-").toLowerCase()%>/{id}", name="findById<%=className%>", methods={"GET"})
     */
    public function findById(int $id, DocumentoQuery $query)
    {
        try{
            $data = $query->findById($id);
            return $this->json($data, 200);
        } catch (\Exception $e){
            return $this->json($e->getMessage(), 400);
        }
    }
}