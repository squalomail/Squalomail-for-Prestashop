<?php

namespace DrewM\SqualoMail\Tests;

use DrewM\SqualoMail\SqualoMail;
use PHPUnit\Framework\TestCase;

class SqualoMailTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testInvalidAPIKey()
    {
        $this->expectException('\Exception');
        new SqualoMail('abc');
    }

    public function testTestEnvironment()
    {
        $SQM_API_KEY = getenv('SQM_API_KEY');
        $this->assertNotEmpty($SQM_API_KEY, 'No environment variables! Copy .env.example -> .env and fill out your SqualoMail account details.');
    }

    /**
     * @throws \Exception
     */
    public function testInstantiation()
    {
        $SQM_API_KEY = getenv('SQM_API_KEY');

        if (!$SQM_API_KEY) {
            $this->markTestSkipped('No API key in ENV');
        }

        $SqualoMail = new SqualoMail($SQM_API_KEY, 'https://api.squalomail.com/mc/v3');
        $this->assertInstanceOf('\DrewM\SqualoMail\SqualoMail', $SqualoMail);

        $this->assertSame('https://api.squalomail.com/mc/v3', $SqualoMail->getApiEndpoint());

        $this->assertFalse($SqualoMail->success());

        $this->assertFalse($SqualoMail->getLastError());

        $this->assertSame(array('headers' => null, 'body' => null), $SqualoMail->getLastResponse());

        $this->assertSame(array(), $SqualoMail->getLastRequest());
    }

    /**
     * @throws \Exception
     */
    public function testSubscriberHash()
    {
        $SQM_API_KEY = getenv('SQM_API_KEY');

        if (!$SQM_API_KEY) {
            $this->markTestSkipped('No API key in ENV');
        }

        $SqualoMail = new SqualoMail($SQM_API_KEY);

        $email    = 'Foo@Example.Com';
        $expected = md5(strtolower($email));
        $result   = $SqualoMail->subscriberHash($email);

        $this->assertEquals($expected, $result);
    }

    public function testResponseState()
    {
        $SQM_API_KEY = getenv('SQM_API_KEY');

        if (!$SQM_API_KEY) {
            $this->markTestSkipped('No API key in ENV');
        }

        $SqualoMail = new SqualoMail($SQM_API_KEY);

        $SqualoMail->get('lists');

        $this->assertTrue($SqualoMail->success());
    }

    /* This test requires that your test list have:
     * a) a list
     * b) enough entries that the curl request will timeout after 1 second.
     * How many this is may depend on your network connection to the Squalomail servers.
     */
    /*
    public function testRequestTimeout()
    {
        $this->markTestSkipped('CI server too fast to realistically test.');


        $SQM_API_KEY = getenv('SQM_API_KEY');

        if (!$SQM_API_KEY) {
            $this->markTestSkipped('No API key in ENV');
        }

        $SqualoMail = new SqualoMail($SQM_API_KEY);
        $result = $SqualoMail->get('lists');
        $list_id = $result['lists'][0]['id'];

        $args = array( 'count' => 1000 );
        $timeout = 1;
        $result = $SqualoMail->get("lists/$list_id/members", $args, $timeout );
        $this->assertFalse( $result );

        $error = $SqualoMail->getLastError();
        $this->assertRegExp( '/Request timed out after 1.\d+ seconds/', $error );
    }
    */
}
