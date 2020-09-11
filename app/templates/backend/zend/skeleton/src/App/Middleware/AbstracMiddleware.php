<?php
declare(strict_types=1);
namespace App\Middleware;

use App\Handler\AbstractHandler;
use App\Query\AbstractQuery;

abstract class AbstracMiddleware
{
    /**
     * 
     * @var AbstractHandler
     */
    protected $handler;

    /**
     *
     * @var AbstractQuery
     */
    protected $query;
    
    /**
     * Undocumented function
     *
     * @param AbstractHandler|null $handler
     * @param AbstractQuery|null $query
     */
    public function __construct(?AbstractHandler $handler = null, ?AbstractQuery $query = null)
    {
        $this->handler = $handler;
        $this->query   = $query;
    } 
}

