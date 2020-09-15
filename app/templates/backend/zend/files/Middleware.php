<?php declare(strict_types=1);
namespace App\Middleware\<%= className %>;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Middleware\AbstracMiddleware;
use App\Entity\<%= _.startCase(className).replace(' ', '') %>;
use App\Command\<%= _.startCase(className).replace(' ', '') %>\<%= attributs.type %>Command;

class <%= attributs.type %>Middleware extends AbstracMiddleware implements RequestHandlerInterface
{

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $command = new <%= attributs.type %>Command();
        <% if (attributs.type === 'Put' || attributs.type === 'Post') { %>$data = json_decode($request->getBody()->getContents(), true);
        $files = $request->getUploadedFiles();
        $command->setValues(array_merge(array_map('utf8_decode', $data), $files));
        <% } if (attributs.type !== 'Post') { %>$command->setValue('id', $request->getAttribute('id')); <% } %>
        return $this->handler->process(new <%= _.startCase(className).replace(' ', '') %>(), $command);
    }
}

