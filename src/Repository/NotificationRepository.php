<?php

namespace App\Repository;

use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Notification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notification[]    findAll()
 * @method Notification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    /**
     * @param int $userId
     * @param int $limit
     * @return Notification[]
     */
    public function getAllUsersNotifications(int $userId, int $limit): array
    {
        $builder = $this->createQueryBuilder('notification')->addSelect('notification');

        return $builder
            ->join('notification.chatUser', 'chatUser')
            ->join('chatUser.user', 'user')
            ->andWhere($builder->expr()->eq('user.id', ':userId'))
            ->orderBy($builder->expr()->desc('notification.created'))
            ->setParameter('userId', $userId)
            ->setMaxResults($limit)
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @param int $userId
     * @return Notification[]
     */
    public function getAllUsersUnreadNotifications(int $userId): array
    {
        $builder = $this->createQueryBuilder('notification')->addSelect('notification');

        return $builder
            ->join('notification.chatUser', 'chatUser')
            ->join('chatUser.user', 'user')
            ->andWhere($builder->expr()->eq('user.id', ':userId'))
            ->andWhere($builder->expr()->eq('notification.read', ':isRead'))
            ->orderBy($builder->expr()->desc('notification.created'))
            ->setParameter('userId', $userId)
            ->setParameter('isRead', false)
            ->getQuery()
            ->getArrayResult();
    }
}
