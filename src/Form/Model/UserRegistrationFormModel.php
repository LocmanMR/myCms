<?php
declare(strict_types=1);

namespace App\Form\Model;

use App\Validator\UniqueUser;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegistrationFormModel
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     * @UniqueUser()
     */
    private string $email;
    private string $firstName;
    /**
     * @var string
     * @Assert\NotBlank(message="Enter password")
     * @Assert\Length(min="6", minMessage="Password must be longer 6 symbols")
     */
    private string $plainPassword;
    /**
     * @var bool
     * @Assert\IsTrue(message="You must agree to the terms")
     */
    private bool $agreeTerms;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return bool
     */
    public function isAgreeTerms(): bool
    {
        return $this->agreeTerms;
    }

    /**
     * @param bool $agreeTerms
     */
    public function setAgreeTerms(bool $agreeTerms): void
    {
        $this->agreeTerms = $agreeTerms;
    }
}

