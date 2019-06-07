<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;

class ResetPassword
{
   
    private $oldPassword;

    /**
     * @Assert\Length(min=8, minMessage="Votre mot de passe doit faire au minimum 8 caractères !")
     * 
     */
    private $newPassword;

    /*
    * @Assert\Length(min=8, minMessage="Votre mot de passe doit faire au minimum 8 caractères !")
    * 
    */
    private $confirmNewPassword;

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfirmNewPassword(): ?string
    {
        return $this->confirmNewPassword;
    }

    public function setConfirmNewPassword(string $confirmNewPassword): self
    {
        $this->confirmNewPassword = $confirmNewPassword;

        return $this;
    }
}
