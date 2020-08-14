<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
         $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {

        for($i = 0; $i < 5; $i++){
            $user= new User;
            $user->setFirstname('Gabs');
            $user->setLastname('Not');
            $user->setEmail('notgabs'.$i.'@yahoo.fr');

            $user->setPassword($this->passwordEncoder->encodePassword($user,'arey' ));

            $manager->persist($user);
        }

        $manager->flush();
    }
}
