<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 * @ORM\Table(name="`message`")
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $text;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTime $update;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ChatUser")
     */
    private ChatUser $chatUser;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Chat")
     */
    private Chat $chat;

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

    public function getUpdate(): ?\DateTime
    {
        return $this->update;
    }

    public function setUpdate(?\DateTime $update): self
    {
        $this->update = $update;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getChatUser(): ChatUser
    {
        return $this->chatUser;
    }

    public function setChatUser(ChatUser $chatUser): self
    {
        $this->chatUser = $chatUser;

        return $this;
    }

    public function getChat(): Chat
    {
        return $this->chat;
    }

    public function setChat(Chat $chat): self
    {
        $this->chat = $chat;

        return $this;
    }
}
