<?php
/**
 * Userservice.
 */

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserService.
 */
class UserService
{
    /**
     * UserRepository repository.
     *
     * @var \App\Repository\UserRepository
     */
    private $userRepository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * Account constructor.
     *
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder Password encoder
     */
    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Change password.
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(User $user): void
    {
        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
        $this->userRepository->save($user);
    }

    /**
     * Change email.
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveEmail(User $user): void
    {
        $user->setEmail($user->getEmail($user));
        $this->userRepository->save($user);
    }

    /**
     * Delete user.
     *
     * @param \App\Entity\User $user User entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(User $user): void
    {
        $this->userRepository->delete($user);
    }
}
