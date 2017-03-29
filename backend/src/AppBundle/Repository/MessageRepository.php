<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;
use AppBundle\Entity\User;

class MessageRepository extends BaseRepository
{
    /**
     * Returns last private messages of a user on a specific project.
     *
     * @param User    $user
     * @param Project $project
     *
     * @return array
     */
    public function findPrivateByUserAndProject(User $user, Project $project)
    {
        $qb = $this->createQueryBuilder('m');
        $ids = $qb
            ->select('MAX(m.id) as id')
            ->where('m.chatRoom is Null')
            ->andWhere('m.project = :project')
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->eq('m.from', ':user'),
                    $qb->expr()->eq('m.to', ':user')
                )
            )
            ->setParameters(['user' => $user, 'project' => $project])
            ->groupBy('m.chatKey')
            ->getQuery()
            ->getArrayResult()
        ;
        $msgIds = [];
        foreach ($ids as $mId) {
            $msgIds[] = $mId['id'];
        }

        return $this->findBy(['id' => $msgIds]);
    }

    /**
     * Returns undeleted private messages.
     *
     * @param Project $project
     * @param User    $user
     * @param $chatKey
     *
     * @return array
     */
    public function findUndeletedPrivateMessages(Project $project, User $user, $chatKey)
    {
        $qb = $this
            ->createQueryBuilder('m')
            ->where('m.chatRoom is Null')
            ->andWhere('m.project = :project')
            ->andWhere('m.chatKey = :chatKey')
        ;

        return $qb
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->andX(
                        $qb->expr()->eq('m.from', ':user'),
                        $qb->expr()->isNull('m.deletedFromAt')
                    ),
                    $qb->expr()->andX(
                        $qb->expr()->eq('m.to', ':user'),
                        $qb->expr()->isNull('m.deletedToAt')
                    )
                )
            )
            ->orderBy('m.createdAt', 'ASC')
            ->setParameters(['project' => $project, 'user' => $user, 'chatKey' => $chatKey])
            ->getQuery()
            ->getResult()
        ;
    }
}
