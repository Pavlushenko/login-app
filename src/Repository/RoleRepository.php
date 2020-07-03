<?php

namespace App\Repository;

use App\Entity\Role;
use App\Service\StatusValueChecker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Role|null find($id, $lockMode = null, $lockVersion = null)
 * @method Role|null findOneBy(array $criteria, array $orderBy = null)
 * @method Role[]    findAll()
 * @method Role[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoleRepository extends ServiceEntityRepository
{
    private $statusValueCkecker;

    const ADMIN_ROLE = 1;

    public function __construct(ManagerRegistry $registry, StatusValueChecker $statusValueCkecker)
    {
        parent::__construct($registry, Role::class);
        $this->statusValueCkecker = $statusValueCkecker;
    }

    public function setStatus(string $status): void
    {
        if ($this->statusValueCkecker::isValidValue($status)) {
            $this->status = $status;
        } else {
            throw new \InvalidArgumentException("Invalid value: $status");
        }
    }

    // /**
    //  * @return Role[] Returns an array of Role objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Role
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
