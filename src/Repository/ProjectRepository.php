<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    /**
     * Find featured projects for homepage
     */
    public function findFeatured(int $limit = 3): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.featured = :featured')
            ->andWhere('p.published = :published')
            ->setParameter('featured', true)
            ->setParameter('published', true)
            ->orderBy('p.sortOrder', 'ASC')
            ->addOrderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find all published projects
     */
    public function findAllPublished(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.published = :published')
            ->setParameter('published', true)
            ->orderBy('p.sortOrder', 'ASC')
            ->addOrderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find projects by technology
     */
    public function findByTechnology(string $technology): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('JSON_CONTAINS(p.technologies, :technology) = 1')
            ->andWhere('p.published = :published')
            ->setParameter('technology', json_encode($technology))
            ->setParameter('published', true)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find all published projects with images
     */
    public function findAllPublishedWithImages(): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.images', 'i')
            ->addSelect('i')
            ->andWhere('p.published = :published')
            ->setParameter('published', true)
            ->orderBy('p.sortOrder', 'ASC')
            ->addOrderBy('p.createdAt', 'DESC')
            ->addOrderBy('i.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find one project with its images
     */
    public function findOneWithImages(int $id): ?Project
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.images', 'i')
            ->addSelect('i')
            ->andWhere('p.id = :id')
            ->setParameter('id', $id)
            ->addOrderBy('i.sortOrder', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
