<?php

namespace RuthgerIdema\UrlRewrite\Test;

use Illuminate\Support\Facades\Route;
use RuthgerIdema\UrlRewrite\Facades\UrlRewrite;

class UrlRewriteTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->assertCount(0, UrlRewrite::all());
    }

    /** @test */
    public function it_can_create_an_url_rewrite()
    {
        $urlRewrite = UrlRewrite::create('test-request-path', 'test-target-path');
        $this->assertEquals('test-request-path', $urlRewrite->request_path);
        $this->assertEquals('test-target-path', $urlRewrite->target_path);
    }

    /** @test */
    public function it_can_create_an_unique_url_rewrite()
    {
        UrlRewrite::create('test-request-path', 'test-target-path');
        $unique = UrlRewrite::create('test-request-path', 'test-target-path', null, null, 0, null, true);

        $this->assertEquals('test-request-path-1', $unique->request_path);
    }

    /** @test */
    public function it_can_create_an_url_rewrite_with_type()
    {
        $attributes = ['id' => 15];
        $urlRewrite = UrlRewrite::create('test-request-path-with-type', 'test-target-path-with-type', 'product', $attributes);
        $this->assertEquals('test-request-path-with-type', $urlRewrite->request_path);
        $this->assertEquals('test-target-path-with-type', $urlRewrite->target_path);
    }

    /** @test */
    public function it_can_find_an_url_rewrite_with_type()
    {
        $attributes = ['id' => 25];
        $urlRewrite = UrlRewrite::create('test-request-path-with-type', 'test-target-path-with-type', 'product', $attributes);
        $this->assertEquals(UrlRewrite::getByTypeAndAttributes('product', $attributes)->id, $urlRewrite->id);
    }

    /** @test */
    public function it_can_find_an_url_rewrite_by_id()
    {
        $urlRewrite = UrlRewrite::create('test-request-path', 'test-target-path');
        $this->assertEquals(UrlRewrite::find($urlRewrite->id)->id, $urlRewrite->id);
    }

    /** @test */
    public function it_can_find_an_url_rewrite_by_target_path()
    {
        $urlRewrite = UrlRewrite::create('test-request-path', 'test-target-path');
        $this->assertEquals(UrlRewrite::getByTargetPath($urlRewrite->target_path)->target_path, $urlRewrite->target_path);
    }

    /** @test */
    public function it_can_find_an_url_rewrite_by_request_path()
    {
        $urlRewrite = UrlRewrite::create('test-request-path', 'test-target-path');
        $this->assertEquals(UrlRewrite::getByRequestPath($urlRewrite->request_path)->request_path, $urlRewrite->request_path);
    }

    /** @test */
    public function it_can_delete_an_url_rewrite()
    {
        $urlRewrite = UrlRewrite::create('test-request-path', 'test-target-path');
        UrlRewrite::delete($urlRewrite->id);
        $this->assertCount(0, UrlRewrite::all());
    }

    /** @test */
    public function it_can_regenerate_an_url_rewrite()
    {
        Route::get('/catalog/product/view/id/{id}', function ($id) {
            return response()->json([$id]);
        })->name('product');

        $attributes = ['id' => 15];
        $urlRewrite = UrlRewrite::create('test-request-path-with-type', 'test-target-path-with-type', 'product', $attributes);

        $this->assertEquals(UrlRewrite::regenerateRoute($urlRewrite)->target_path, '/catalog/product/view/id/15');
    }
}
