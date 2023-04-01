<?php

namespace App\Repository;

use App\Entity\Interview;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @extends ServiceEntityRepository<Interview>
 *
 * @method Interview|null find($id, $lockMode = null, $lockVersion = null)
 * @method Interview|null findOneBy(array $criteria, array $orderBy = null)
 * @method Interview[]    findAll()
 * @method Interview[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InterviewRepository extends ServiceEntityRepository
{
    private $security;
  
  
    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Interview::class);
        $this->security =$security;
    }
     
    public function saveInterview(Interview $entity, bool $isNewEntry = false): void
    {
        if (true === $isNewEntry) {
            $this->getEntityManager()->persist($entity);
        }
         
        $this->getEntityManager()->flush();
    }
   
    public function remove(Interview $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function countInterviews(){
        return $this->createQueryBuilder('i')
            ->select('count(i.id)')
            ->getQuery()
            ->execute();
    }

    public function isReadyToSend(Interview $interview){
       
       $datetime_now = new DateTime();  
       $date_interview = $interview->getDate(); 
       $interval = $date_interview;
       date_sub($interval, date_interval_create_from_date_string("24 hours"));
       $date_interval = date_format($interval, "Y-m-d H:i:s");
       return ($datetime_now > $date_interval && $datetime_now < $interview->getDate());
    }
     // check if user is interlocutor
     public function IsInterlocutor()
     {
        return ($this->security->isGranted('ROLE_INTERLOCUTOR')) === true;
     }
 
     /**
      * @return ?Interview[] set of interviews of the given user 
      */
    public function findInterview(User $user): ?array
    {
      return $this->createQueryBuilder('i')
            ->andWhere('i.interlocutor = :val')
            ->setParameter('val', $user)
            ->getQuery()
            ->getResult();
    }
 
    
      /**
      * Gets all today's interviews.
      *
      * @return ?Interview[] list of interviews ordered by the closest meeting to the farther
      */
     public function listDailyInterviews(): ?array
     {
         $queryBuilder = $this->createQueryBuilder('i');
 
         return $queryBuilder->select()
             ->where('date(i.date) = date(:q)')
             ->setParameter('q', (new \DateTime('now')))
             ->orderBy('i.date', 'asc')
             ->getQuery()
             ->getResult();
     }
 

//    /**
//     * @return Interview[] Returns an array of Interview objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Interview
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

//     public function showInterview()
//     {
//     return $this->createQueryBuilder('f')
//     ->innerJoin('App\Entity\User','u','with','u.interlocutor = f.fullName')
//     ->where('f.fullName = :name')
//     ->setParameter('user',$user)
//     ->setParameter('name', 'u.interlocutor')
//     ->getQuery()
//     ->getResult();
//         ;
//     }
    
    
}