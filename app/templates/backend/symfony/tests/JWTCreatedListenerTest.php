<?php
namespace Tests\Unit\<%=packageName%>\EventListener;

use Tests\Unit\AbstractUnitTestCase;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Mockery as M;
use <%=packageName%>\EventListener\JWTCreatedListener;


class JWTCreatedListenerTest extends AbstractUnitTestCase
{
    public function testAddDataInToken()
    {
        //implement it
    }
}
