<?php

namespace RuthgerIdema\UrlRewrite\Repositories\Interfaces;

interface UrlRewriteInterface
{
    public function getModel();

    public function setModel($model);

    public function find($id);

    public function getByRequestPath($url);

    public function getByTypeAndAttributes($type, array $attributes);

    public function getByTargetPath($url);

    public function all();

    public function delete($id);

    public function create($requestPath, $targetPath, $type = null, $typeAttributes = null, $redirectType = 0, $description = null, $unique = false);

    public function update(array $data, $id);

    public function regenerateRoute($urlRewrite);
}
