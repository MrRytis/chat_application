<?php

namespace App\Repository;

use App\Entity\Chat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Chat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chat[]    findAll()
 * @method Chat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chat::class);
    }

    /**
     * @param int $userId
     * @return Chat[]
     */
    public function getAllUsersChats(int $userId, int $limit): array
    {
        $builder = $this->createQueryBuilder('chat')->select('chat');

        return $builder
            ->join('chat.chatUsers', 'chatUsers')
            ->join('chatUsers.user', 'user')
            ->andWhere($builder->expr()->eq('user.id', ':userId'))
            ->setParameter('userId', $userId)
            ->setMaxResults($limit)
            ->getQuery()
            ->getArrayResult();
    }
}
