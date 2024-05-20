<?php

use Tests\TestCase;

class RetrieveCompensationReportControllerTest extends TestCase
{


    /**
     * Test file retrieving failure scenario.
     *
     */
    public function testInvokeFailure()
    {
        $filename = 'non_existant_report.csv';

        $response = $this->get(route('retrieve', ['filename' => $filename ]));
        $response->assertStatus(404);

    }
}
