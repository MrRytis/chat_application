<?php

namespace App\Builder;

use App\Entity\Chat;
use App\Entity\ChatUser;
use App\Entity\User;
use App\Exception\ChatException;
use Doctrine\ORM\EntityManagerInterface;

class SimpleChatBuilder implements ChatBuilder
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildChat(): Chat
    {
        return (new Chat())
            ->setTitle('Simple chat');
    }

    /**
     * @throws ChatException
     */
    public function buildChatUser(int $userId, Chat $chat): ChatUser
    {
        $user = $this->entityManager->getRepository(User::class)->find($userId);

        if (null === $user) {
            throw ChatException::userNotFoundException();
        }

        return (new ChatUser())
            ->setUsername($user->getUserIdentifier())
            ->setUser($user)
            ->setChat($chat);
    }
}
