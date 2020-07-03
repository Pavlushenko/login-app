<?php

namespace App\Repository;

use App\Entity\Topic;
use App\Entity\User;
use App\Service\StatusValueChecker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Topic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Topic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Topic[]    findAll()
 * @method Topic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TopicRepository extends ServiceEntityRepository
{
    private $statusValueCkecker;

    public function __construct(ManagerRegistry $registry, StatusValueChecker $statusValueCkecker)
    {
        parent::__construct($registry, Topic::class);
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

    public function findTopicsWithMessages(User $user) {
        $result = $this->createQueryBuilder('t')
            ->select('t.id', 'm.body as message', 't.name', 'u.id as userId', 'u.firstName', 'u.lastName')
            ->innerJoin('App\Entity\Message', 'm', 'WITH', 't.id = m.topic')
            ->innerJoin('m.user', 'u')
            ->andWhere('u.id = :userId OR u.role = :userRole')
            ->setParameter('userId', $user->getId())
            ->setParameter('userRole', RoleRepository::ADMIN_ROLE)
            ->getQuery()
            ->getResult()
        ;

        $resultFormated = [];
        foreach ($result as $item) {
            if (!array_key_exists($item['id'], $resultFormated)) {
                $resultFormated[$item['id']]['id'] = $item['id'];
                $resultFormated[$item['id']]['name'] = $item['name'];
                $resultFormated[$item['id']]['messages'] = [];
            }
            $resultFormated[$item['id']]['messages'][] = array(
                'userId' => $item['userId'],
                'firstName' => $item['firstName'],
                'lastName' => $item['lastName'],
                'message' => $item['message'],
            );
        }

        return $resultFormated;
    }

    // /**
    //  * @return Topic[] Returns an array of Topic objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Topic
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
