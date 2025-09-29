<?php

namespace App\Repository;

use App\Entity\ProjectImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjectImage>
 */
class ProjectImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectImage::class);
    }

    /**
     * Find images for a project ordered by sort order
     */
    public function findByProjectOrdered(int $projectId): array
    {
        return $this->createQueryBuilder('pi')
            ->andWhere('pi.project = :projectId')
            ->setParameter('projectId', $projectId)
            ->orderBy('pi.sortOrder', 'ASC')
            ->addOrderBy('pi.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find the primary image for a project
     */
    public function findPrimaryByProject(int $projectId): ?ProjectImage
    {
        return $this->createQueryBuilder('pi')
            ->andWhere('pi.project = :projectId')
            ->andWhere('pi.isPrimary = true')
            ->setParameter('projectId', $projectId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Get all filenames from database
     */
    public function getAllFilenames(): array
    {
        $result = $this->createQueryBuilder('pi')
            ->select('pi.filename')
            ->getQuery()
            ->getResult();
            
        return array_column($result, 'filename');
    }

    /**
     * Find images with missing files
     */
    public function findImagesWithMissingFiles(): array
    {
        $images = $this->findAll();
        $missingFiles = [];
        
        foreach ($images as $image) {
            if (!$image->fileExists()) {
                $missingFiles[] = $image;
            }
        }
        
        return $missingFiles;
    }
}
