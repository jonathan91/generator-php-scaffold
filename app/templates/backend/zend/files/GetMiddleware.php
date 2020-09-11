<?php declare(strict_types=1);
namespace App\Middleware\<%= _.startCase(className).replace(' ', '') %>;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Middleware\AbstracMiddleware;
use Zend\Diactoros\Response\JsonResponse;

class GetMiddleware extends AbstracMiddleware implements RequestHandlerInterface
{
    
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try{
            if($request->getAttribute('id')){
                $response = $this->query->findById((int) $request->getAttribute('id'));
            } else {
                $response = $this->query->search($request->getQueryParams());
            }
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return new JsonResponse($response);
    }
}

