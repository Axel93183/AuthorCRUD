<?php

namespace App\Repository;

use App\Entity\Book;
use App\DTO\SearchBookCriteria;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function save(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findLastTwenty(): array //
    {
        $queryBuilder = $this->createQueryBuilder('book');//créer la requete pour book

        return $queryBuilder-> orderBy('book.updatedAt', 'DESC') //
                            -> setMaxResults(20) //
                            -> getQuery()//ecrire la requete
                            -> getResult();//recuperer les resultats de la requete

    }

    public function findBookByCategory(int $id): array //
    {
        $queryBuilder = $this->createQueryBuilder('book');//créer la requete pour book

        return $queryBuilder-> leftJoin('book.categories', 'category')//jointure entre les livres et les catégories
                            -> andWhere('category.id = :id')//condition sur le id de la category
                            -> setParameter('id' , $id) // paramétre à ajouter pour la protection contre les injections SQL
                            -> orderBy('book.price', 'DESC') //
                            -> getQuery()
                            -> getResult();

    }

    public function findBookByCriteria(SearchBookCriteria $criteria): array
    {
        //Création du query builder qui interroge la table book
        $queryBuilder = $this->createQueryBuilder('book'); 

        //Filtrer les résultats selon le titre si c'est spécifié
        if($criteria->title){
           $queryBuilder->andWhere('book.title LIKE :title')
                        ->setParameter('title', "%$criteria->title%");
        }

        //Filtrer les résultats par auteurs
        if(!empty($criteria->authors)){
           $queryBuilder->leftJoin('book.author', 'author')// le join est fait entre book et author
                        ->andWhere('author.id IN (:authorIds)')
                        ->setParameter('authorIds' , $criteria->authors);
        }

        //Filtrer les résultats par catégories
        if(!empty($criteria->categories)){
           $queryBuilder->leftJoin('book.categories', 'category')// le join est fait entre book et category
                        ->andWhere('category.id IN (:catIds)')
                        ->setParameter('catIds' , $criteria->categories);
        }

        //Filtrer par les prix minimals
        if($criteria->minPrice){
           $queryBuilder->andWhere('book.price >= :minPrice')
                        ->setParameter('minPrice' , $criteria->minPrice);
        }

        //Filtrer par les prix maximals
        if($criteria->maxPrice){
           $queryBuilder->andWhere('book.price <= :maxPrice')
                        ->setParameter('maxPrice' , $criteria->maxPrice);
        }

        //filtre par maison d édition
        if(!empty($criteria->publishingHouses)){
            $queryBuilder->leftJoin('book.publishingHouse', 'PublishingHouse')// le join est fait entre book et category
            ->andWhere('PublishingHouse.id IN (:publishing)')
            ->setParameter('publishing' , $criteria->publishingHouses);
        }

        $queryBuilder->orderBy("book.$criteria->orderBy", $criteria->direction)
                    ->setMaxResults($criteria->limit)
                    ->setFirstResult(($criteria->page - 1) * $criteria->limit);

        return $queryBuilder->getQuery() //ecrire la requete
                            ->getResult(); //recuperer les resultats de la requete
    }
}
