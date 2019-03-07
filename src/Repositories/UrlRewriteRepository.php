<?php

namespace RuthgerIdema\UrlRewrite\Repositories;

use RuthgerIdema\UrlRewrite\Entities\UrlRewrite;
use RuthgerIdema\UrlRewrite\Repositories\Interfaces\UrlRewriteInterface;

class UrlRewriteRepository implements UrlRewriteInterface
{
    /** @var UrlRewrite */
    protected $model;

    public function __construct(
       UrlRewrite $model
    ) {
        $this->model = $model;
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function checkIfIdExists($id)
    {
        return $this->model->where('id', $id)->exists();
    }

    public function checkIfRequestPathExists($url)
    {
        return $this->model->where('request_path', $url)->exists();
    }

    public function getByRequestPath($url)
    {
        return $this->model->where('request_path', $url)->first();
    }

    public function getByTypeAndAttributes($type, array $attributes)
    {
        return $this->model->getByTypeAndAttributes($type, $attributes);
    }

    public function getByTargetPath($url)
    {
        return $this->model->where('target_path', $url)->firstOrFail();
    }

    public function all()
    {
        return $this->model->all();
    }

    public function delete($id)
    {
        $this->find($id)->delete();
    }

    public function regenerateAll()
    {
        foreach (config('url-rewrite.types') as $type) {
            $this->regenerateRoutesFromType($type);
        }
    }

    public function regenerateRoutesFromType($type)
    {
        $collection = $this->model->where('type', $type)->get();
        foreach ($collection as $urlRewrite) {
            $this->regenerateRoute($urlRewrite);
        }
    }

    public function regenerateRoute($urlRewrite)
    {
        if (! array_key_exists($urlRewrite->type, config('url-rewrite.types'))) {
            throw new \Exception('type does not exist');
        }

        $urlRewrite->target_path = route($urlRewrite->type, $urlRewrite->type_attributes, false);
        $urlRewrite->save();

        return $urlRewrite;
    }

    public function create($requestPath, $targetPath, $type = null, $typeAttributes = null, $redirectType = 0, $store = null, $description = null)
    {
        return $this->model->create(
            [
                'type' => $type,
                'type_attributes' => $typeAttributes,
                'request_path' => $requestPath,
                'target_path' => $targetPath,
                'redirect_type' => $redirectType,
                'store_id' => $store,
                'description' => $description,
            ]
        );
    }

    public function update($data)
    {
        // TODO: Implement update() method.
    }

    public function createUnique($requestPath, $targetPath, $type = null, $typeAttributes = null, $redirectType = 0, $store = null, $description = null, $id = null)
    {
        // TODO: Implement createIfNotExists() method.
    }
}
