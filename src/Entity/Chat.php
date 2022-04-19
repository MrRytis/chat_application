<?php

namespace App\Entity;

use App\Repository\ChatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChatRepository::class)
 * @ORM\Table(name="`chat`")
 */
class Chat
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
    private string $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $created;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTime $update;

    /**
     * @var Message[] | ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="chat")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private ArrayCollection $messages;

    /**
     * @var ChatUser[] | ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\ChatUser", mappedBy="chat")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private ArrayCollection $chatUsers;

    public function __construct()
    {
        $this->created = new \DateTime();
        $this->chatUsers = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Chat
     */
    public function setId(int $id): Chat
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Chat
     */
    public function setTitle(string $title): Chat
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     * @return Chat
     */
    public function setCreated(\DateTime $created): Chat
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdate(): ?\DateTime
    {
        return $this->update;
    }

    /**
     * @param \DateTime|null $update
     * @return Chat
     */
    public function setUpdate(?\DateTime $update): Chat
    {
        $this->update = $update;

        return $this;
    }

    /**
     * @return Message[]|ArrayCollection
     */
    public function getMessages(): ArrayCollection
    {
        return $this->messages;
    }

    /**
     * @param Message[]|ArrayCollection $messages
     * @return Chat
     */
    public function setMessages(ArrayCollection $messages): self
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * @return ChatUser[]|ArrayCollection
     */
    public function getChatUsers(): ArrayCollection
    {
        return $this->chatUsers;
    }

    /**
     * @param ChatUser[]|ArrayCollection $chatUsers
     * @return Chat
     */
    public function setChatUsers(ArrayCollection $chatUsers): self
    {
        $this->chatUsers = $chatUsers;

        return $this;
    }
}
