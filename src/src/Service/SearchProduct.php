<?php


namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class SearchProduct
{

    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public static function validStr(string $str): bool
    {
        if (strlen($str) > 10)
            return false;
        return true;
    }

    public function search(string $input): iterable
    {
        if ($this->validStr($input)) {
            $productRepository = $this->manager->getRepository(Product::class);
            return $productRepository->createQueryBuilder('product')->where('product.Name like :query')->setParameter('query', "%" . $input . "%")->getQuery()->getResult();
        }
    }

    public function searchByBrand(string $input): iterable
    {
        $productRepository = $this->manager->getRepository(Product::class);
        return $productRepository->createQueryBuilder('product')->where('product.Category = :queryByBrand')->setParameter('queryByBrand', $input)->getQuery()->getResult();
    }
}