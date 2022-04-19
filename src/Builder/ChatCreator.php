<?php

namespace App\Builder;

use App\Entity\Chat;

class ChatCreator
{
    private ChatBuilder $builder;

    public function __construct(ChatBuilder $builder)
    {
        $this->builder = $builder;
    }

    public function createChat(array $userIds): Chat
    {
        $chat = $this->builder->buildChat();

        $chatUsers = [];
        foreach ($userIds as $userId) {
            $chatUsers[] = $this->builder->buildChatUser($userId, $chat);
        }

        return $chat->setChatUsers($chatUsers);
    }
}
