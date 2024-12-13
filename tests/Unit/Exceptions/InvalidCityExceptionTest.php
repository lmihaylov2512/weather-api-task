<?php

namespace Tests\Unit\Exceptions;

use Tests\TestCase;
use App\Exceptions\InvalidCityException;
use Illuminate\Http\JsonResponse;

class InvalidCityExceptionTest extends TestCase
{
    public function test_exception_is_thrown(): void
    {
        $invalidCity = 'InvalidCity';
        $this->expectException(InvalidCityException::class);
        $this->expectExceptionMessage("The passed $invalidCity city does not exist");

        throw new InvalidCityException("The passed $invalidCity city does not exist");
    }

    public function test_render_method(): void
    {
        $exception = new InvalidCityException('The city does not exist');
        $response = $exception->render();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(422, $response->status());
        $this->assertArrayHasKey('message', $response->getData(true));
        $this->assertEquals('The city does not exist', $response->getData(true)['message']);
    }
}
