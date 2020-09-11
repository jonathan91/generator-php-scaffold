<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as SWG;
use App\Service\Command\<%= _.startCase(className).replace(' ', '') %>\PostCommand;
use App\Service\Command\<%= _.startCase(className).replace(' ', '') %>\PutCommand;
use App\Service\Command\<%= _.startCase(className).replace(' ', '') %>\DeleteCommand;
use App\Service\Query\<%= _.startCase(className).replace(' ', '') %>Query;

class <%= _.startCase(className).replace(' ', '') %>Controller extends AbstractApiController
{
    /**
     * 
     * {@inheritDoc}
     * @see \App\Controller\AbstractApiController::post()
     * @Route("/api/<%=_.kebabCase(className).toLowerCase()%>", name="new<%= _.startCase(className).replace(' ', '') %>", methods={"POST"})
     * @SWG\Tag(name="<%=_.replace(_.kebabCase(className),"-"," ")%>")
     * @SWG\Post(
     *   path="/api/<%=_.kebabCase(className).toLowerCase()%>",
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
        return $this->preparePost($request, new PostCommand());
    }
    /**
     *
     * {@inheritDoc}
     * @see \App\Controller\AbstractApiController::put()
     * @Route("/api/<%=_.kebabCase(className).toLowerCase()%>/{id}", name="edit<%=_.startCase(className).replace(' ', '')%>", methods={"PUT"})
     * @SWG\Tag(name="<%=_.replace(_.kebabCase(className),"-"," ")%>")
     * @SWG\Put(
     *   path="/api/<%=_.kebabCase(className).toLowerCase()%>/{id}",
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
        return $this->preparePut($id, $request, new PutCommand());
    }
    /**
     *
     * {@inheritDoc}
     * @see \App\Controller\AbstractApiController::delete()
     * @Route("/api/<%=_.kebabCase(className).toLowerCase()%>/{id}", name="delete<%=_.startCase(className).replace(' ', '')%>", methods={"DELETE"})
     * @SWG\Tag(name="<%=_.replace(_.kebabCase(className),"-"," ")%>")
     * @SWG\Response(
     *     response=200,
     *     description="API Result",
     * )
     */
    public function delete($id, Request $request)
    {
        return $this->prepareDelete($id, new DeleteCommand());
    }

    /**
     * {@inheritDoc}
     * @see \App\Controller\AbstractApiController::getAll()
     * @Route("/api/<%=_.kebabCase(className).toLowerCase()%>", name="getAll<%=_.startCase(className).replace(' ', '')%>", methods={"GET"})
     * @SWG\Tag(name="<%=_.replace(_.snakeCase(className),"_"," ")%>")
     * @SWG\Response(
     *     response=200,
     *     description="API Result",
     * )
     */
    public function getAll(<%= className %>Query $query, Request $request)
    {
        return $this->prepareSearch($query, $request);
    }
    /**
     *
     * {@inheritDoc}
     * @see \App\Controller\AbstractApiController::findById()
     * @Route("/api/<%=_.kebabCase(className).toLowerCase()%>/{id}", name="getById<%=_.startCase(className).replace(' ', '')%>", methods={"GET"})
     * @SWG\Tag(name="<%=_.replace(_.snakeCase(className),"_"," ")%>")
     * @SWG\Response(
     *     response=200,
     *     description="API Result",
     * )
     */
    public function getById(int $id, <%= className %>Query $query)
    {
        try{
            $data = $query->findById($id);
            return $this->json($data, Response::HTTP_OK);
        } catch (\Exception $e){
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}