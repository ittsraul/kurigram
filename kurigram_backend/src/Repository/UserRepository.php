<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    private $passwordHasher;
    private $doctrine;
    public function __construct(ManagerRegistry $registry, UserPasswordHasherInterface $passwordHasher)
    {
        $this->doctrine = $registry;
        $this->passwordHasher = $passwordHasher;
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

    public function insert($data) : void {

        $User = new User;
        $hashedPassword = $this->encrypt($User, $data["password"]);
        $User
            ->setName($data["name"])
            ->setEmail($data["email"])
            ->setPassword($hashedPassword)
            ->setPhone($data["phone"])
            ->setRoles(["USER"]);

        $this->save($User, true);
    }

    public function insertUser($request) : void {

        $User = new User;
        $roles = ($request->request->get("roles") === '["USER"]') ? ["USER"] : ["ADMIN"];
        $hashedPassword = $this->encrypt($User, $request->request->get("password"));
        $User
            ->setName($request->request->get("name"))
            ->setEmail($request->request->get("email"))
            ->setPassword($hashedPassword)
            ->setPhone($request->request->get("phone"))
            ->setRoles($roles); 

        $this->save($User, true);
    }

    
    public function updateUser(int $id, $request): void
    {
        $result = $this->find($id);
        $roles = ($request->request->get("roles") === '["USER"]') ? ["USER"] : ["ADMIN"];
        $result
        ->setName($request->request->get('name'))
        ->setEmail($request->request->get('email'))
        ->setPhone($request->request->get('phone'))
        ->setRoles($roles);
        $this->save($result, true);
    } 

    public function encrypt($User, $password) : string {
        $hashedPassword = $this->passwordHasher->hashPassword(
            $User,
            $password
        );

        return $hashedPassword;
    }
    public function update($data)
    {
            $this->doctrine->getManager()->persist($data);
            $this->doctrine->getManager()->flush();
    }

    public function updateAssistant($getEvent, $getUser)
    {
        if (!empty($getUser)) { // Verifica que $getUser tenga al menos un elemento
            $getUser[0]->addEvent($getEvent[0]);
            $this->doctrine->getManager()->persist($getUser[0]);
            $this->doctrine->getManager()->flush();
        }
    }


//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
