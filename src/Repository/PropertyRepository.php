<?php

namespace App\Repository;

use App\Entity\Property;
use App\Entity\PropertySearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Property>
 *
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }

    // ici ca creer une requete qui va recupere tout les enregistrement de la tab property 
    // pour lesquelles sold = false (recup des propriete pas encore vendu)
    public function findAllVisibleQuery(PropertySearch $search = null): Query
    {
        $query = $this->findVisibleQuerrry();

        if ($search && $search->getMaxPrice()) {

            $query = $query
                ->andWhere('p.price < :maxPrice')
                ->setParameter('maxPrice', $search->getMaxPrice());
        }

        if ($search && $search->getMinSurface()) { 

            $query = $query
                ->andWhere('p.surface >= :minSurface')
                ->setParameter('minSurface', $search->getMinSurface());
        }

        if($search->getOptions()->count() > 0){

            $k = 0;
            foreach ($search->getOptions() as $option) {

                $k++;
                $query = $query
                    ->andWhere(":option$k MEMBER OF p.options")
                    ->setParameter("option$k", $option);
            }
        }
        // En résumé, ce code ci dessus construit dynamiquement une requête en fonction des options fournies dans
        //  l'objet $search. Chaque option est vérifiée pour son appartenance à l'entité en cours de 
        //  recherche, et cela est ajouté à la requête en utilisant andWhere

        return $query->getQuery();
    }

    public function findLatest(): array
    {
        return $this->findVisibleQuerrry()
            ->orderBy('p.created_at', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }

    private function findVisibleQuerrry(): QueryBuilder
    {

        return $this->createQueryBuilder('p')
            ->orderBy('p.created_at', 'DESC')
            ->andWhere('p.sold = false');
    }



    //    /**
//     * @return Property[] Returns an array of Property objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    //    public function findOneBySomeField($value): ?Property
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}