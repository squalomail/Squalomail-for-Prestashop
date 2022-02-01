<?php

use DrewM\SqualoMail\SqualoMail;
use PHPUnit\Framework\TestCase;

class ListsTest extends TestCase
{
    public function testGetLists()
    {
        $SQM_API_KEY = getenv('SQM_API_KEY');

        if (!$SQM_API_KEY) {
            $this->markTestSkipped('No API key in ENV');
        }

        $SqualoMail = new SqualoMail($SQM_API_KEY);
        $lists     = $SqualoMail->get('lists');

        $this->assertArrayHasKey('lists', $lists);
    }
}
