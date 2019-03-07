<?php

namespace RuthgerIdema\UrlRewrite\Repositories\Interfaces;

interface UrlRewriteInterface
{
    public function find($id);

    public function getByRequestPath($url);

    public function getByTypeAndAttributes($type, array $attributes);

    public function getByTargetPath($url);

    public function all();

    public function delete($id);

    public function create($requestPath, $targetPath, $type = null, $typeAttributes = null, $redirectType = 0, $store = null, $description = null);

    public function update($data);

    public function regenerateRoute($urlRewrite);

    public function createUnique($requestPath, $targetPath, $type = null, $typeAttributes = null, $redirectType = 0, $store = null, $description = null, $id = null);
}
