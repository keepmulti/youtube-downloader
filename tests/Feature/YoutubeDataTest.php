<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class YoutubeDataTest extends TestCase
{

    /**
     * test request valid
     */
    public function testValidRequest()
    {
        $testData = [
            [
                'input' => 'https://www.youtube.com/watch?v=JCOQFGJk6Q4&list=RDM8U7PR6fQNA&index=26',
                'output' => [
                    'title' => '1 Phút - Andiez | MV Lyrics HD',
                    'downloadlink' => 26
                ]
            ],
            [
                'input' => 'https://www.youtube.com/watch?v=HXkh7EOqcQ4&list=RDM8U7PR6fQNA&index=25',
                'output' => [
                    'title' => 'THẰNG ĐIÊN | JUSTATEE x PHƯƠNG LY | OFFICIAL MV',
                    'downloadlink' => 24,
                ]
            ],
        ];

        foreach ($testData as $testCase) {
            $response = $this->post(route('getinfo'),
                [
                    'url' => $testCase['input'],
                    '_token' => csrf_token()
                ]);

            $response->assertStatus(200);

            $requestOutput = json_decode($response->content(), true);

            $this->assertSame($requestOutput['status'], 200);
            $this->assertSame($requestOutput['data']['title'], $testCase['output']['title']);
            $this->assertSame(count($requestOutput['data']['downloadlink']), $testCase['output']['downloadlink']);
        }
    }

    /**
     * test invalid request
     */
    public function testInvalidRequest()
    {
        $testData = [
            [
                'input' => 'https://www.youtube.com/',
                'output' => [
                    'status' => 500,
                    'message' => ''
                ]
            ],
            [
                'input' => 'https://abc',
                'output' => [
                    'status' => 500,
                    'message' => ''
                ]
            ],
        ];

        foreach ($testData as $testCase) {
            $response = $this->post(route('getinfo'),
                [
                    'url' => $testCase['input'],
                    '_token' => csrf_token()
                ]);

            $requestOutput = json_decode($response->content(), true);

            $this->assertSame($requestOutput, $testCase['output']);
        }
    }
}
