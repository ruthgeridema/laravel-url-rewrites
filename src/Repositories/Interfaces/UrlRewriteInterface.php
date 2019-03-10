<?php

declare(strict_types=1);

namespace RuthgerIdema\UrlRewrite\Repositories\Interfaces;

interface UrlRewriteInterface
{
    public function getModel(): object;

    public function setModel(object $model): object;

    public function find(int $id): ?object;

    public function getByRequestPath(string $url): ?object;

    public function getByTypeAndAttributes(string $type, array $attributes): ?object;

    public function getByTargetPath(string $url): ?object;

    public function all(): ?object;

    public function delete(int $id): bool;

    public function create(
        string $requestPath,
        ?string $targetPath,
        ?string $type = null,
        ?array $typeAttributes = null,
        int $redirectType = 0,
        ?string $description = null,
        bool $unique = false
    );

    public function update(array $data, int $id): object;

    public function regenerateRoute(object $urlRewrite): object;
}
