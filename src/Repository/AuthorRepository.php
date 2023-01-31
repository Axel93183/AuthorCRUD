<?php

namespace App\Repository;

use App\Entity\Author;
use App\DTO\SearchAuthorCriteria;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Author>
 *
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function save(Author $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Author $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Author[] Returns an array of Author objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Author
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

        public function findByAuthorCriteria(SearchAuthorCriteria $criteria): array
        {
            $queryBuilder = $this->createQueryBuilder('author');

            if($criteria->name){
                $queryBuilder->andWhere('author.name LIKE :name')
                             ->setParameter('name', "%$criteria->name%");
             }

            return $queryBuilder->orderBy("author.$criteria->orderBy" , "$criteria->direction")
                                ->setMaxResults($criteria->limit)
                                ->setFirstResult(($criteria->page - 1) * $criteria->limit)
                                ->getQuery() //ecrire la requete
                                ->getResult()//recuperer les resultats de la requet
                                ; 
        }

}


// public ?string $orderBY= 'id';

// public ?string $direction= 'DESC';

// public ?int $limit = 25;

// public ?int $page = 1;