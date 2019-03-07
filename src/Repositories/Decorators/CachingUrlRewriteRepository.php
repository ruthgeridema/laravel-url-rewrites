<?php

namespace RuthgerIdema\UrlRewrite\Repositories\Decorators;

use RuthgerIdema\UrlRewrite\Entities\UrlRewrite;
use RuthgerIdema\UrlRewrite\Repositories\Interfaces\UrlRewriteInterface;

class CachingUrlRewriteRepository implements UrlRewriteInterface
{
    public function find($id)
    {
        // TODO: Implement find() method.
    }

    public function getByRequestPath($url)
    {
        // TODO: Implement getByRequestPath() method.
    }

    public function getByTypeAndAttributes($type, array $attributes)
    {
        // TODO: Implement getByTypeAndAttributes() method.
    }

    public function getByTargetPath($url)
    {
        // TODO: Implement getByTargetPath() method.
    }

    public function all()
    {
        // TODO: Implement all() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function create($requestPath, $targetPath, $type = null, $typeAttributes = null, $redirectType = 0, $store = null, $description = null)
    {
        // TODO: Implement create() method.
    }

    public function update($data)
    {
        // TODO: Implement update() method.
    }

    public function regenerateRoute($urlRewrite)
    {
        // TODO: Implement regenerateRoute() method.
    }

    public function createUnique($requestPath, $targetPath, $type, $store, $description, $id)
    {
        // TODO: Implement createUnique() method.
    }
}
