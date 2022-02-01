<?php

namespace DrewM\SqualoMail\Tests;

use DrewM\SqualoMail\SqualoMail;
use PHPUnit\Framework\TestCase;

class BatchTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testNewBatch()
    {
        $SQM_API_KEY = getenv('SQM_API_KEY');

        if (!$SQM_API_KEY) {
            $this->markTestSkipped('No API key in ENV');
        }

        $SqualoMail = new SqualoMail($SQM_API_KEY);
        $Batch     = $SqualoMail->new_batch('1');

        $this->assertInstanceOf('\DrewM\SqualoMail\Batch', $Batch);

        $this->assertSame(array(), $Batch->get_operations());
    }

}
