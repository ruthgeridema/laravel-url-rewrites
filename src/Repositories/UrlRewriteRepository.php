<?php

namespace RuthgerIdema\UrlRewrite\Repositories;

use RuthgerIdema\UrlRewrite\Entities\UrlRewrite;
use RuthgerIdema\UrlRewrite\Exceptions\UrlRewriteRegenerationFailed;
use RuthgerIdema\UrlRewrite\Exceptions\UrlRewriteAlreadyExistsException;
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

    public function getModel()
    {
        return $this->model;
    }

    public function setModel($model)
    {
        $this->model = $model;

        return $this;
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
        return $this->model->getByTypeAndAttributes($type, $attributes)->first();
    }

    public function getByTargetPath($url)
    {
        return $this->model->where('target_path', $url)->first();
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
        if (empty($this->getTypes())) {
            throw UrlRewriteRegenerationFailed::noConfiguration();
        }

        foreach ($this->getTypes() as $type) {
            $this->regenerateRoutesFromType($type);
        }
    }

    public function regenerateRoutesFromType($type)
    {
        if (! array_key_exists($type, $this->getTypes())) {
            throw UrlRewriteRegenerationFailed::invalidType($type);
        }

        $collection = $this->model->where('type', $type)->get();
        foreach ($collection as $urlRewrite) {
            $this->regenerateRoute($urlRewrite);
        }
    }

    public function regenerateRoute($urlRewrite)
    {
        if (! array_key_exists($urlRewrite->type, $this->getTypes())) {
            throw UrlRewriteRegenerationFailed::invalidType($urlRewrite->type);
        } elseif (! is_array($urlRewrite->type_attributes)) {
            throw UrlRewriteRegenerationFailed::columnNotSet($urlRewrite, 'type_attributes');
        }

        return $this->update(
            ['target_path' => $this->targetPathFromRoute($urlRewrite->type, $urlRewrite->type_attributes)],
            $urlRewrite->id
        );
    }

    public function create(
        $requestPath,
        $targetPath,
        $type = null,
        $typeAttributes = null,
        $redirectType = 0,
        $description = null,
        $unique = false
    ) {
        if ($this->checkIfRequestPathExists($requestPath)) {
            if (! $unique) {
                throw UrlRewriteAlreadyExistsException::requestPath($requestPath);
            }

            $requestPath = $this->generateUnique($requestPath);
        }

        if ($targetPath === null && isset($type, $typeAttributes)) {
            $targetPath = $this->targetPathFromRoute($type, $typeAttributes);
        }

        return $this->model->create(
            [
                'type' => $type,
                'type_attributes' => $typeAttributes,
                'request_path' => $requestPath,
                'target_path' => $targetPath,
                'redirect_type' => $redirectType,
                'description' => $description,
            ]
        );
    }

    public function update(array $data, $id)
    {
        $record = $this->find($id);

        $record->update($data);

        return $record;
    }

    protected function generateUnique($requestPath, $id = 1)
    {
        if ($this->checkIfRequestPathExists($requestPath.'-'.$id)) {
            return $this->generateUnique($requestPath, $id + 1);
        }

        return $requestPath.'-'.$id;
    }

    protected function getTypes()
    {
        return config('url-rewrite.types');
    }

    /**
     * @param $urlRewrite
     * @return string
     */
    protected function targetPathFromRoute($type, $attributes): string
    {
        return route($type, $attributes, false);
    }
}
