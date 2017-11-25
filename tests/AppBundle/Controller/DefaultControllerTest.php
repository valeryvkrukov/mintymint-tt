<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{

    public function testUrlCRUD()
    {
        $faker = \Faker\Factory::create('en_US');
        $client = $this->makeClient();
        $fakeUrl = $faker->url();
        // Create
        $client->request('POST', '/url/minify', [], [], [
            'HTTP_Content-Type' => 'application/json',
            'HTTP_X-Requested-With' => 'XMLHttpRequest'
        ], json_encode(['url' => $fakeUrl]));
        // Read
        $response = $client->getResponse();
        $this->isSuccessful($response);
        $data = stripslashes($response->getContent());
        $this->assertContains($fakeUrl, $data);
        $data = json_decode($response->getContent());
        $recordId = $data->id;
        // Update
        $client->request('POST', '/url/update', [], [], [
            'HTTP_Content-Type' => 'application/json',
            'HTTP_X-Requested-With' => 'XMLHttpRequest'
        ], json_encode([
            'id' => $recordId,
            'shortCode' => 'shortCode',
        ]));
        $response = $client->getResponse();
        $this->isSuccessful($response);
        $data = stripslashes($response->getContent());
        $this->assertContains('ok', $data);
        $data = json_decode($response->getContent());
        // Delete
        $client->request('POST', '/url/delete', [], [], [
            'HTTP_Content-Type' => 'application/json',
            'HTTP_X-Requested-With' => 'XMLHttpRequest'
        ], json_encode(['id' => $recordId]));
        $response = $client->getResponse();
        $this->isSuccessful($response);
    }

}
