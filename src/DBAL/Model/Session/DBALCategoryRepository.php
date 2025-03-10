<?php

declare(strict_types=1);

namespace Infrastructure\DBAL\Model\Session;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Domain\Model\CategoryCriteria\CategoryCriteria;
use Domain\Model\Session\Categories;
use Domain\Model\Session\Category;
use Domain\Model\Session\CategoryNotFoundException;
use Domain\Model\Session\CategoryRepository;

class DBALCategoryRepository implements CategoryRepository
{
    public function __construct(
        private readonly Connection $connection,
        private readonly string $categoryTableName,
    ) {
    }

    public function create(Category $category): string
    {
        $category->markAsUpdated();

        $this->connection->insert(
            $this->categoryTableName,
            $this->categoryToArray($category)
        );

        $category->id = \strval($this->connection->lastInsertId());

        return $category->id;
    }

    public function update(Category $category): void
    {
        $category->markAsUpdated();

        $this->connection->update(
            $this->categoryTableName,
            $this->categoryToArray($category),
            ['id' => $category->id]
        );
    }

    public function remove(Category $category): void
    {
        $category->setAsDeleted();

        $this
            ->connection
            ->update(
                $this->categoryTableName,
                ['deleted_at' => $category->deletedAt->format(self::DATE_TIME_FORMAT)],
                ['id' => $category->id]
            );
    }

    public function findOneBy(CategoryCriteria $criteria): Category
    {
        $categoryAsArray = $this
            ->createQueryBuilderByCategoryCriteria($criteria)
            ->fetchAssociative();

        if (false === $categoryAsArray) {
            throw new CategoryNotFoundException();
        }

        $category = $this->arrayToCategory($categoryAsArray);

        return $category;
    }

    public function findBy(CategoryCriteria $criteria): Categories
    {
        $categoriesAsArray = $this
            ->createQueryBuilderByCategoryCriteria($criteria)
            ->fetchAllAssociative();

        return new Categories(
            array_map(
                function (array $categoryAsArray) {
                    $category = $this->arrayToCategory($categoryAsArray);

                    return $category;
                },
                $categoriesAsArray
            ),
        );
    }

    private function categoryToArray(Category $category): array
    {
        return [
            'id' => \intval($category->id),
            'name' => \strval($category->name),
            'description' => \strval($category->description),
            'price_with_vat' => \floatval($category->priceWithVat),
            'vat_percentage' => \floatval($category->vatPercentage),
            'capacity' => \intval($category->capacity),
            'created_at' => $category->createdAt,
            'updated_at' => $category->updatedAt,
            'deleted_at' => $category->deletedAt ? $category->deletedAt->format(self::DATE_TIME_FORMAT) : null,
        ];
    }

    private function arrayToCategory(array $data): Category
    {
        $category = new Category();
        $category->id = \strval($data['id']);
        $category->name = \strval($data['name']);
        $category->description = \strval($data['description']);
        $category->priceWithVat = \floatval($data['price_with_vat']);
        $category->vatPercentage = \floatval($data['vat_percentage']);
        $category->capacity = \intval($data['capacity']);
        $category->createdAt = new \DateTimeImmutable($data['created_at']);
        $category->updatedAt = new \DateTimeImmutable($data['updated_at']);
        $category->deletedAt = $data['deleted_at'] ? new \DateTimeImmutable($data['deleted_at']) : null;

        return $category;
    }

    private function createQueryBuilderByCategoryCriteria(CategoryCriteria $criteria): QueryBuilder
    {
        $queryBuilder = $this
            ->connection
            ->createQueryBuilder()
            ->select('c.*')
            ->from($this->categoryTableName, 'c')
            ->where('c.deleted_at IS NULL')
            ->orderBy('c.start_datetime', 'ASC');

        $this->applyCategoryCriteriaFilters($criteria, $queryBuilder);

        return $queryBuilder;
    }

    private function applyCategoryCriteriaFilters(CategoryCriteria $criteria, QueryBuilder $queryBuilder): void
    {
        if (!empty($criteria->getId())) {
            $queryBuilder->andWhere('c.id = :id')
                ->setParameter('id', $criteria->getId());
        }

        if (!empty($criteria->getIds())) {
            $queryBuilder->andWhere('c.id IN (:ids)')
                ->setParameter('ids', $criteria->getIds(), ArrayParameterType::INTEGER);
        }
    }
}
