<?php

namespace App\Entity;

use App\Repository\ChatUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Entity(repositoryClass=ChatUserRepository::class)
 * @ORM\Table(name="`chat_user`")
 */
class ChatUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=180)
     */
    private string $username;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="chatUsers")
     */
    private User $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $created;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Chat", inversedBy="chatUsers")
     */
    private Chat $chat;

    /**
     * @var PersistentCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Notification", mappedBy="chatUser", fetch="EXTRA_LAZY")
     */
    private PersistentCollection $notifications;

    public function __construct()
    {
        $this->created = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

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

    public function getChat(): Chat
    {
        return $this->chat;
    }

    public function setChat(Chat $chat): self
    {
        $this->chat = $chat;

        return $this;
    }

    public function getNotifications(): PersistentCollection
    {
        return $this->notifications;
    }

    public function setNotifications(PersistentCollection $notifications): self
    {
        $this->notifications = $notifications;

        return $this;
    }
}
