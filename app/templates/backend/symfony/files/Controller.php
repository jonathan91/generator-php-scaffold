<?php
namespace <%= packageName %>\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use <%= packageName %>\Http\ResponseBag;
use <%= packageName %>\Service\Command\<%= className %>\Post<%= className %>Command;
use <%= packageName %>\Service\Command\<%= className %>\Put<%= className %>Command;
use <%= packageName %>\Service\Command\<%= className %>\Delete<%= className %>Command;

/**
 * 
 * @RouteResource("<%= className %>", pluralize=false)
 */
class <%= className %>Controller extends AbstractApiController
{
    private $query  = '<%= _.snakeCase(_.replace(packageName,"Bundle","")).toLowerCase() %>.<%= _.snakeCase(className).toLowerCase() %>_query';
    
   /*
    *
    * @param Request $request
    * @return ResponseBag
    */
    public function cgetAction(Request $request)
    {
        $service = $this->get($this->query);
        $search = $service->search($request->query);
        return new Response(json_encode($search), Response::HTTP_OK, ['X-Total-Count'=>count($search)]);
    }

    public function getAction($id)
    {
        return new ResponseBag($this->get($this->query)->findById($id));
    }

    public function postAction(Request $request)
    {
        $command = new Post<%= className %>Command(
            <% attributs.fields.forEach(function(element, index, elements){ %>$request->get('<%= element.fieldName %>')<% if (index !== elements.length - 1){ %>,
            <% }}); %>
        );
        return new ResponseBag($this->getServiceBus()->handle($command));
    }

    public function putAction(int $id, Request $request)
    {
        $sb = $this->getServiceBus();
        $command = new Put<%= className %>Command(
            $id,
            <% attributs.fields.forEach(function(element, index, elements){ %>$request->get('<%= element.fieldName %>')<% if (index !== elements.length - 1){ %>,
            <% }}); %>
        );
        return new ResponseBag($this->getServiceBus()->handle($command));
    }

    public function deleteAction($id)
    {
        $command = new Delete<%= className %>Command($id);
        $this->getServiceBus()->handle($command);
        return new ResponseBag(null, true);
    }
}