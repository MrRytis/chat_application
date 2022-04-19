<?php

namespace App\Repository;

use App\Entity\ChatUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ChatUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChatUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChatUser[]    findAll()
 * @method ChatUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChatUser::class);
    }

    public function getChatUser(int $userId, int $chatId): ?ChatUser
    {
        $builder = $this->createQueryBuilder('chat_user')->select('chat_user');

        return $builder
            ->join('chat_user.chat', 'chat')
            ->join('chat_user.user', 'user')
            ->andWhere($builder->expr()->eq('chat.id', ':chatId'))
            ->andWhere($builder->expr()->eq('user.id', ':userId'))
            ->setParameter('chatId', $chatId)
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param int $senderChatUserId
     * @param int $chatId
     * @return ChatUser[]
     */
    public function getChatUsersForNotifications(int $senderChatUserId, int $chatId): array
    {
        $builder = $this->createQueryBuilder('chat_user')->select('chat_user');

        return $builder
            ->join('chat_user.chat', 'chat')
            ->andWhere($builder->expr()->eq('chat.id', ':chatId'))
            ->andWhere($builder->expr()->neq('chat_user.id', ':userId'))
            ->setParameter('chatId', $chatId)
            ->setParameter('userId', $senderChatUserId)
            ->getQuery()
            ->getArrayResult();
    }
}
