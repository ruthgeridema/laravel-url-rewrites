<?php

namespace RuthgerIdema\UrlRewrite\Repositories\Decorators;

use Illuminate\Cache\TaggableStore;
use Illuminate\Contracts\Cache\Repository as Cache;
use RuthgerIdema\UrlRewrite\Repositories\Interfaces\UrlRewriteInterface;

class CachingUrlRewriteRepository implements UrlRewriteInterface
{
    /** @var string */
    public const URL_REWRITE_ALL = 'url_rewrite_all';

    /** @var string */
    public const URL_REWRITE_ID = 'url_rewrite_id_';

    /** @var string */
    public const URL_REWRITE_REQUEST_PATH = 'url_rewrite_request_path_';

    /** @var string */
    public const URL_REWRITE_TARGET_PATH = 'url_rewrite_target_path_';

    /** @var string */
    public const URL_REWRITE_TYPE_ATTRIBUTES = 'url_rewrite_type_attributes_';

    /** @var UrlRewriteInterface */
    protected $repository;

    /** @var Cache */
    protected $cache;

    public function __construct(
        UrlRewriteInterface $repository,
        Cache $cache
    ) {
        $this->repository = $repository;
        $this->cache = $cache;
        $this->addTagIfPossible();
    }

    protected function remember($key, $method, ...$arguments)
    {
        return $this->cache->remember(
            $key,
            $this->getTtl(),
            function () use ($method, $arguments) {
                return $this->repository->{$method}(...$arguments);
            }
        );
    }

    protected function addTagIfPossible()
    {
        if ($this->cache->getStore() instanceof TaggableStore) {
            $this->cache = $this->cache->tags(config('url-rewrite.cache-key'));
        }
    }

    protected function getTtl()
    {
        return env('URL_REWRITE_TTL', 86400);
    }

    public function find($id)
    {
        return $this->remember(self::URL_REWRITE_ID.$id, __FUNCTION__, $id);
    }

    public function getByRequestPath($url)
    {
        return $this->remember(static::URL_REWRITE_REQUEST_PATH.md5($url), __FUNCTION__, $url);
    }

    public function all()
    {
        return $this->remember(static::URL_REWRITE_ALL, __FUNCTION__);
    }

    public function getByTargetPath($url)
    {
        return $this->remember(static::URL_REWRITE_TARGET_PATH.md5($url), __FUNCTION__, $url);
    }

    public function getByTypeAndAttributes($type, array $attributes)
    {
        return $this->remember(
            self::URL_REWRITE_TYPE_ATTRIBUTES.md5($type.json_encode($attributes)),
            __FUNCTION__,
            $type,
            $attributes
        );
    }

    public function getModel()
    {
        return $this->repository->getModel();
    }

    public function setModel($model)
    {
        return $this->repository->setModel($model);
    }

    public function delete($id)
    {
        $this->forgetById($id);

        return $this->repository->delete($id);
    }

    protected function forgetById($id)
    {
        if ($model = $this->find($id)) {
            $this->cache->forget(static::URL_REWRITE_ALL);
            $this->cache->forget(static::URL_REWRITE_ID.$model->id);
            $this->cache->forget(static::URL_REWRITE_REQUEST_PATH.md5($model->request_path));
            $this->cache->forget(static::URL_REWRITE_TARGET_PATH.md5($model->target_path));
            $this->cache->forget(static::URL_REWRITE_TYPE_ATTRIBUTES.md5($model->type.json_encode($model->type_attributes)));
        }
    }

    public function create($requestPath, $targetPath, $type = null, $typeAttributes = null, $redirectType = 0, $site = null, $description = null)
    {
        return $this->repository->create($requestPath, $targetPath, $type, $typeAttributes, $redirectType, $site, $description);
    }

    public function update(array $data, $id)
    {
        $updated = $this->repository->update($data, $id);

        $this->forgetById($id);

        return $updated;
    }

    public function regenerateRoute($urlRewrite)
    {
        return $this->repository->regenerateRoute($urlRewrite);
    }
}
