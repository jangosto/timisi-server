<?php

namespace Domain\Model;

class CriteriaWithId
{
    protected ?string $id = null;
    protected array $ids = [];

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getIds(): array
    {
        return $this->ids;
    }

    public static function createEmpty(): self
    {
        return new static();
    }

    public function isEmpty(): bool
    {
        return \is_null($this->id) && empty($this->ids);
    }

    public static function createById(string $id): self
    {
        return (new static())->filterById($id);
    }

    public static function createByIds(array $ids): self
    {
        return (new static())->filterByIds($ids);
    }

    public function filterById(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function filterByIds(array $ids): self
    {
        $this->ids = $ids;

        return $this;
    }
}