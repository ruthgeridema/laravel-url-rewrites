<?php

declare(strict_types=1);

namespace RuthgerIdema\UrlRewrite\Http;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use RuthgerIdema\UrlRewrite\Repositories\Interfaces\UrlRewriteInterface;

class UrlRewriteController
{
    /** @var UrlRewriteInterface */
    protected $repository;

    public function __construct(
        UrlRewriteInterface $repository
    ) {
        $this->repository = $repository;
    }

    public function __invoke($url): Response
    {
        if (! $urlRewrite = $this->repository->getByRequestPath($url)) {
            abort(404);
        }

        if ($urlRewrite->isForward()) {
            return $this->forwardResponse($urlRewrite->target_path);
        }

        return redirect($urlRewrite->target_path, $urlRewrite->getRedirectType());
    }

    /**
     * @param $url
     * @return mixed
     */
    protected function forwardResponse($url): Response
    {
        return Route::dispatch(
            Request::create(
                $url,
                request()->getMethod()
            )
        );
    }
}
