<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Controle;
use App\Enum\TypeControleEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ControleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Controle::class);
    }

    public function search(string $keyword): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.titre LIKE :keyword')
            ->setParameter('keyword', '%' . $keyword . '%')
            ->orderBy('c.dateControle', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findPublished(): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.publie = :publie')
            ->setParameter('publie', true)
            ->orderBy('c.dateControle', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByType(TypeControleEnum $type): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.type = :type')
            ->setParameter('type', $type)
            ->orderBy('c.dateControle', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findRecentExamens(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.dateControle', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function countPublished(): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.publie = :publie')
            ->setParameter('publie', true)
            ->getQuery()
            ->getSingleScalarResult();
    }
}

