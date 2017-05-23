<?php

use Silex\WebTestCase;

class controllersTest extends WebTestCase
{
    public function testGetHomepage()
    {
        $client = $this->createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isOk());
    }

    public function createApplication()
    {
        $app = require __DIR__.'/../app/app.php';
        require __DIR__.'/../config/dev.php';
        require __DIR__.'/../app/controllers.php';
        $app['session.test'] = true;

        return $this->app = $app;
    }
}
