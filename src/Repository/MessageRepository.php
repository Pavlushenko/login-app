<?php

namespace App\Repository;

use App\Entity\Message;
use App\Service\StatusValueChecker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    private $statusValueCkecker;

    public function __construct(ManagerRegistry $registry, StatusValueChecker $statusValueCkecker)
    {
        parent::__construct($registry, Message::class);
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

    /*
     * Returns an associative array: key is user's id, value is quantity of last messages
     */
    public function findUsersWithLastMessageInTopic()
    {
        $lastMessages = $this->createQueryBuilder('m')
            ->select('t.id', 'max(m.createdAt) as max')
            ->innerJoin('m.topic', 't')
            ->innerJoin('m.user', 'u')
            ->groupBy('t.id')
            ->getQuery()
            ->getResult()
        ;

        $result = [];
        foreach($lastMessages as $lastMessage) {
            $temp = $this->createQueryBuilder('m')
                ->select('u.id')
                ->innerJoin('m.user', 'u')
                ->andWhere('m.createdAt = :date')
                ->andWhere('m.topic = :tId')
                ->setParameter('date', $lastMessage['max'])
                ->setParameter('tId', $lastMessage['id'])
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult()
            ;
            if ($temp) {
                if (array_key_exists($temp['id'], $result)) {
                    $result[$temp['id']]++;
                } else {
                    $result[$temp['id']] = 1;
                }
            }

        }

        return $result;
    }

    // /**
    //  * @return Message[] Returns an array of Message objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Message
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
