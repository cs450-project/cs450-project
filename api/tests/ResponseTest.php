<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

use App\Lib\Response;

final class ResponseTest extends TestCase {
    public function testCanBeCreatedWithOk(): void {
        $this->assertInstanceOf(
            Response::class,
            Response::ok()
        );
    }

    public function testCanBeCreatedWithError(): void {
        $this->assertInstanceOf(
            Response::class,
            Response::error()
        );
    }

    public function testCanBeCreatedWithCode(): void {
        $this->assertInstanceOf(
            Response::class,
            Response::withCode(500)
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function testSetsCode(): void {
        $res = Response::withCode(404);
        $res->toJSON();
        $this->assertEquals(
            http_response_code(),
            404
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function testOkSets200Code(): void {
        $res = Response::ok();
        $res->toJSON();
        $this->assertEquals(
            http_response_code(),
            200
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function testErrorSets400Code(): void {
        $res = Response::error();
        $res->toJSON();
        $this->assertEquals(
            http_response_code(),
            400
        );
    }

     /**
     * @runInSeparateProcess
     */
    public function testSetsJSONHeaderType(): void {
        $res = Response::error();
        $res->toJSON();
        
        $headers = xdebug_get_headers();
        $this->assertContains(
            'Content-Type: application/json',
            $headers
        );
    }
}
