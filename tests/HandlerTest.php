<?php
/**
 * Created by PhpStorm.
 * User: tboileau-desktop
 * Date: 07/05/18
 * Time: 23:04
 */

namespace App;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use TBoileau\FormHandlerBundle\Handler;

class HandlerTest extends TestCase
{
    public function testHandle()
    {
        $handler = $this->getMockBuilder(Handler::class)->setMethods(["handle"])->disableOriginalConstructor()->getMockForAbstractClass();
        $handler
            ->expects($this->once())
            ->method("handle")
            ->will($this->returnValue(new Response()));

        $this->assertInstanceOf(Response::class, $handler->handle());
    }
}