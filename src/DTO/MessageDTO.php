<?php

namespace App\DTO;

class MessageDTO
{
    private int $id;
    private string $text;
    private \DateTime $created;
    private int $chatUser;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

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

    public function getChatUser(): int
    {
        return $this->chatUser;
    }

    public function setChatUser(int $chatUser): self
    {
        $this->chatUser = $chatUser;

        return $this;
    }
}
