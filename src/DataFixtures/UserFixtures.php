<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends BaseFixtures
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(User::class, 10, function (User $user) {
            $user
                ->setEmail($this->faker->email)
                ->setFirstName($this->faker->firstName())
                ->setPassword($this->passwordEncoder->encodePassword($user,'123456'))
            ;

            $isActive = 1;
            if (random_int(1, 10) <= 3) {
                $isActive = 0;
            }
            $user->setIsActive($isActive);
        });
    }
}
