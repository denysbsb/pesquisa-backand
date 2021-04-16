<?php

namespace QuizBundle\Services;

use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityManager;
use Ingresso\CoreBundle\Exceptions\ValidException;
use QuizBundle\Entity\Participante;

class ParticipanteService
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->entityManager = $em;
    }
  
    public function save(Participante $entity)
    {
      $this->entityManager->persist($entity);
      $this->entityManager->flush();
    }
    
  /**
   * @return \Doctrine\ORM\EntityRepository
   */
  public function getRepository() {
    return $this->entityManager->getRepository('QuizBundle:Participante');
  }
}
