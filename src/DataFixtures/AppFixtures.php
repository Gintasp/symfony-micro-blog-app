<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use App\Entity\UserPreferences;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private const USERS = [
        [
            'username' => 'john_doe',
            'email' => 'john_doe@doe.com',
            'password' => 'password',
            'fullName' => 'John Doe',
            'roles' => [User::ROLE_USER]
        ],
        [
            'username' => 'rob_smith',
            'email' => 'rob_smith@smith.com',
            'password' => 'password',
            'fullName' => 'Rob Smith',
            'roles' => [User::ROLE_USER]
        ],
        [
            'username' => 'susan_doe',
            'email' => 'susan@doe.com',
            'password' => 'password',
            'fullName' => 'Susan Doe',
            'roles' => [User::ROLE_USER]
        ],
        [
            'username' => 'tom_johnson',
            'email' => 'tom.johnson@gmail.com',
            'password' => 'password',
            'fullName' => 'Tom Johnson',
            'roles' => [User::ROLE_USER]
        ],
        [
            'username' => 'mark_zuckerberg',
            'email' => 'markz@facebook.com',
            'password' => 'password',
            'fullName' => 'Mark Zuckerberg',
            'roles' => [User::ROLE_USER]
        ],
        [
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'password' => 'adminpassword',
            'fullName' => 'Admin',
            'roles' => [User::ROLE_ADMIN]
        ],
    ];

    private const POST_TEXT = [
        'Hello, how are you?',
        'It\'s nice sunny weather today',
        'I need to buy some ice cream!',
        'I wanna buy a new car',
        'There\'s a problem with my phone',
        'I need to go to the doctor',
        'What are you up to today?',
        'Did you watch the game yesterday?',
        'How was your day?'
    ];

    private const LANGUAGES = [
        'en',
        'lt'
    ];

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * AppFixtures constructor.
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadMicroPosts($manager);
    }

    public function loadUsers(ObjectManager $manager)
    {
        foreach (self::USERS as $userData) {
            $user = new User();
            $user->setUsername($userData['username']);
            $user->setFullName($userData['fullName']);
            $user->setEmail($userData['email']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $userData['password']));
            $user->setRoles($userData['roles']);
            $user->setEnabled(true);

            $this->addReference($userData['username'], $user);

            $preferences = new UserPreferences();
            $preferences->setLocale(self::LANGUAGES[rand(0, 1)]);
            $manager->persist($preferences);
            $user->setPreferences($preferences);

            $manager->persist($user);
        }

        $manager->flush();
    }

    public function loadMicroPosts(ObjectManager $manager)
    {
        for ($i = 0; $i < 30; $i++) {
            $microPost = new MicroPost();
            $microPost->setText(self::POST_TEXT[rand(0, count(self::POST_TEXT) - 1)]);
            $date = new \DateTime();
            $date->modify('-' . rand(0, 10) . ' day');
            $microPost->setTime($date);
            $microPost->setUser($this->getReference(
                self::USERS[rand(0, count(self::USERS) - 1)]['username']
            ));
            $manager->persist($microPost);
        }

        $manager->flush();
    }
}
