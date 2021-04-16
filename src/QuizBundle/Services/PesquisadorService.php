<?php

namespace QuizBundle\Services;

use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityManager;
use Ingresso\CoreBundle\Exceptions\ValidException;
use QuizBundle\Entity\Pesquisador;

class PesquisadorService
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
  
  public function save(Pesquisador $entity)
  {
    $this->entityManager->persist($entity);
    $this->entityManager->flush();
  }
  
  /**
   * @return \Doctrine\ORM\EntityRepository
   */
  public function getRepository() {
    return $this->entityManager->getRepository('QuizBundle:Pesquisador');
  }
}
