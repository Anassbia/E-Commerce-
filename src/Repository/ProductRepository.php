<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Product[]
     */
    public function findByCategoryOrdered(Category $category): array
    {
        return $this->findBy(['category' => $category], ['id' => 'ASC']);
    }

    public function findFirstProduct(): ?Product
    {
        return $this->findOneBy([], ['id' => 'ASC']);
    }
}
