<?php

declare(strict_types=1);

namespace Domain\Model\Session;

use Domain\Model\BaseRepository;
use Domain\Model\CategoryCriteria\CategoryCriteria;

interface CategoryRepository extends BaseRepository
{
    public function create(Category $category): string;

    public function update(Category $category): void;

    public function remove(Category $category): void;

    public function findOneBy(CategoryCriteria $criteria): Category;

    public function findBy(CategoryCriteria $criteria): Categories;
}
