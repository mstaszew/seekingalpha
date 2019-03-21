<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', "http://localhost:8080/index.php/");

        // this may need to be changed to 403
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertContains('You must be signed in to access this site', $crawler->filter('h1')->text());
    }
}
