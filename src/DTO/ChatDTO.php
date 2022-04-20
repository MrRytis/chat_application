<?php

namespace App\DTO;

class ChatDTO
{
    private int $id;
    private string $title;
    private \DateTime $created;
    private int $notificationCount;
    private string $lastMessage;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    public function setCreated(\DateTime $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getNotificationCount(): int
    {
        return $this->notificationCount;
    }

    public function setNotificationCount(int $notificationCount): self
    {
        $this->notificationCount = $notificationCount;

        return $this;
    }

    public function getLastMessage(): string
    {
        return $this->lastMessage;
    }

    public function setLastMessage(string $lastMessage): self
    {
        $this->lastMessage = $lastMessage;

        return $this;
    }
}
