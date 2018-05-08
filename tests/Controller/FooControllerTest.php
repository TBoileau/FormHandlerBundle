<?php
/**
 * Created by PhpStorm.
 * User: tboileau-desktop
 * Date: 08/05/18
 * Time: 02:41
 */

namespace TBoileau\FormHandlerBundle\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FooControllerTest extends WebTestCase
{
    public function testFoo()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $crawler = $client->request('POST', '/', ["foo" => ["bar" => ""]]);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());


    }
}