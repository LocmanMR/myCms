<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\ApiToken;
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
        $this->create(User::class, function (User $user) use ($manager) {
            $user
                ->setEmail('admin@symfony.ru')
                ->setFirstName('Admin')
                ->setPassword($this->passwordEncoder->encodePassword($user, '123456'))
                ->setIsActive(true)
                ->setRoles(['ROLE_ADMIN'])
            ;

            $manager->persist(new ApiToken($user));
        });

        $this->create(User::class, function (User $user) use ($manager) {
            $user
                ->setEmail('api@symfony.ru')
                ->setFirstName('Api')
                ->setPassword($this->passwordEncoder->encodePassword($user, '123456'))
                ->setIsActive(true)
                ->setRoles(['ROLE_API'])
            ;

            for ($i = 0; $i < 3; $i++) {
                $manager->persist(new ApiToken($user));
            }
        });

        $this->createMany(User::class, 10, function (User $user) use ($manager) {
            $user
                ->setEmail($this->faker->email)
                ->setFirstName($this->faker->firstName())
                ->setPassword($this->passwordEncoder->encodePassword($user, '123456'))
                ->setIsActive($this->faker->boolean(70))
                ->setRoles(['ROLE_USER'])
            ;

            $manager->persist(new ApiToken($user));
        });
    }
}
