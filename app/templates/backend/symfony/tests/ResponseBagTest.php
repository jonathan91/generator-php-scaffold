<?php
namespace Tests\Unit\<%=packageName%>\Http;

use Tests\Unit\AbstractUnitTestCase;
use <%=packageName%>\Http\ResponseBag;

class ResponseBagTest extends AbstractUnitTestCase
{
    /**
     * @return ResponseBag
     */
    protected function getResponseBag()
    {
        return new ResponseBag('data', true, 'message');
    }

    public function testBuildSuccess()
    {
        $message = 'an unexpected error was thrown';
        $bag = new ResponseBag('data', false, $message);
        $this->assertEquals('data', $bag->getData());
        $this->assertEquals(false, $bag->getSuccess());
        $this->assertEquals($message, $bag->getMessage());
    }

    public function testSetDataSuccess()
    {
        $bag = $this->getResponseBag();
        $bag->setData('new data');
        $this->assertEquals('new data', $bag->getData());
    }

    public function testSetMensagemSuccess()
    {
        $bag = $this->getResponseBag();
        $bag->setMessage('message');
        $this->assertEquals('message', $bag->getMessage());
    }

    public function testSetStatusSuccess()
    {
        $bag = $this->getResponseBag();
        $bag->setSuccess(false);
        $this->assertFalse($bag->getSuccess());
    }
}