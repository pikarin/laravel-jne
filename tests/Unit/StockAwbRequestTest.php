<?php

namespace Aditia\Jne\Tests\Unit;

use Aditia\Jne\Facades\Jne;
use Aditia\Jne\Tests\TestCase;
use Illuminate\Support\Collection;
use Aditia\Jne\Http\Requests\StockAwbRequest;
use Aditia\Jne\Http\Exceptions\InvalidStockAwbRequestException;
use Aditia\Jne\Http\Requests\Contracts\Request as RequestContract;

class StockAwbRequestTest extends TestCase
{
    /** @test */
    public function it_implements_request_contract()
    {
        $this->assertInstanceOf(RequestContract::class, new StockAwbRequest([]));
    }

    /**
     * @test
     * @dataProvider requestBodyValidationProvider
     */
    public function test_body_validation($bodyName, $bodyValue, $exceptionMessage)
    {
        $this->expectException(InvalidStockAwbRequestException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $stockAwbRequest = new StockAwbRequest($this->body([
            $bodyName => $bodyValue,
        ]));

        $stockAwbRequest->validate();
    }

    /** @test */
    public function it_transform_body_param_names_to_uppercase()
    {
        $stockAwbRequest = new StockAwbRequest([
            'branch' => '::BRANCH::',
            'cust_id' => '::CUST_ID::',
            'create_by' => '::CREATE_BY::',
            'request_awb' => '::REQUEST_AWB::',
            'request_by' => '::REQUEST_BY::',
            'request_no' => '::REQUEST_NO::',
            'reason' => '::REASON::',
        ]);

        foreach ($stockAwbRequest->toArray() as $key => $value) {
            $this->assertEquals(strtoupper($key), $key);
        }
    }

    /** @test */
    public function it_doesnt_transform_the_credential_param_names()
    {
        $stockAwbRequest = new StockAwbRequest([
            'username' => '::username::',
            'api_key' => '::api_key::',
        ]);

        foreach ($stockAwbRequest->toArray() as $key => $value) {
            $this->assertEquals(strtolower($key), $key);
        }
    }

    protected function body(?array $overrides = []): array
    {
        return array_merge([
            'username' => '::username::',
            'api_key' => '::api_key::',
            'BRANCH' => '::BRANCH::',
            'CUST_ID' => '::CUST_ID::',
            'CREATE_BY' => '::CREATE_BY::',
            'REQUEST_AWB' => '::REQUEST_AWB::',
            'REQUEST_BY' => '::REQUEST_BY::',
            'REQUEST_NO' => '::REQUEST_NO::',
            'REASON' => '::REASON::',
        ], $overrides);
    }

    public function requestBodyValidationProvider(): array
    {
        return [
            'Test username is required' => ['username', '', 'username is required.'],
            'Test api_key is required' => ['api_key', '', 'api_key is required.'],
            'Test BRANCH is required' => ['BRANCH', '', 'BRANCH is required.'],
            'Test CUST_ID is required' => ['CUST_ID', '', 'CUST_ID is required.'],
            'Test CREATE_BY is required' => ['CREATE_BY', '', 'CREATE_BY is required.'],
            'Test REQUEST_AWB is required' => ['REQUEST_AWB', '', 'REQUEST_AWB is required.'],
            'Test REQUEST_BY is required' => ['REQUEST_BY', '', 'REQUEST_BY is required.'],
            'Test REQUEST_NO is required' => ['REQUEST_NO', '', 'REQUEST_NO is required.'],
            'Test REASON is required' => ['REASON', '', 'REASON is required.'],
        ];
    }
}
