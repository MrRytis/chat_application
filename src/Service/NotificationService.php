<?php

namespace App\Service;

use App\Entity\Chat;
use App\Entity\ChatUser;
use App\Entity\Message;
use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;

class NotificationService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createNotification(int $chatId, Message $message, ChatUser $sender): void
    {
        $chatUsers = $this->entityManager->getRepository(ChatUser::class)->getChatUsersForNotifications($chatId, $sender->getId());

        foreach ($chatUsers as $chatUser) {
            $notification = (new Notification())
                ->setMessage($message)
                ->setChatUser($chatUser)
                ->setRead(false);

            $this->entityManager->persist($notification);
        }

        $this->entityManager->flush();
    }
}
