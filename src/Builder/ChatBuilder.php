<?php

namespace App\Builder;

use App\Entity\Chat;
use App\Entity\ChatUser;

interface ChatBuilder
{
    public function buildChat(): Chat;
    public function buildChatUser(int $userId, Chat $chat): ChatUser;
}
