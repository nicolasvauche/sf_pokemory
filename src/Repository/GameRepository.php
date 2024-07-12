<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function getLeaderboards(array $modes = []): array
    {
        $leaderboards = [];

        foreach($modes as $mode) {
            $leaderboards[$mode] = $this->createQueryBuilder('g')
                ->where('g.mode = :mode')
                ->andWhere('g.completed = true')
                ->orderBy('g.tries', 'ASC')
                ->addOrderBy('g.completedAt - g.createdAt', 'ASC')
                ->setParameter('mode', $mode)
                ->getQuery()
                ->getResult();
        }

        return $leaderboards;
    }
}
