<?php

namespace App\Request;

use JMS\Serializer\Annotation as Serializer;

class RegisterRequest
{
    /**
     * @Serializer\Type("string")
     */
    private string $username;

    /**
     * @Serializer\Type("string")
     */
    private string $password;

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
}
