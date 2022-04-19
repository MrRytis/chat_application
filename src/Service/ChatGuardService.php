<?php

namespace App\Service;

use App\Entity\ChatUser;
use App\Exception\ChatException;
use Doctrine\ORM\EntityManagerInterface;

class ChatGuardService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function checkChatAccess(int $chatId, int $userId): void
    {
        $chatUser = $this->entityManager->getRepository(ChatUser::class)->getChatUser($userId, $chatId);

        if (null === $chatUser) {
            throw ChatException::forbiddenException();
        }
    }
}
