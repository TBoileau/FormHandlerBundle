<?php
/**
 * Created by PhpStorm.
 * User: tboileau-desktop
 * Date: 08/05/18
 * Time: 02:41
 */

namespace TBoileau\FormHandlerBundle\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\StreamOutput;

class FooControllerTest extends WebTestCase
{
    public function testFoo()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client->request('POST', '/', ["foo" => ["bar" => ""]]);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client->request('POST', '/', ["foo" => ["bar" => "foobar"]]);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}