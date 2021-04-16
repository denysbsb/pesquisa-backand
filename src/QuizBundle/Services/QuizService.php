<?php

namespace QuizBundle\Services;

use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityManager;
use Ingresso\CoreBundle\Exceptions\ValidException;
use QuizBundle\Entity\OpcoesQuestao;
use QuizBundle\Entity\Participante;
use QuizBundle\Entity\Pesquisa;
use QuizBundle\Entity\Pesquisador;
use QuizBundle\Entity\Quiz;

class QuizService
{

    /**
     * @var EntityManager
     */
    private $entityManager;
  
    /**
     * @var ParticipanteService
     */
    private $participanteService;
  
    /**
     * @var PesquisadorService
     */
    private $pesquisadorService;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, ParticipanteService $participanteService, PesquisadorService $pesquisadorService)
    {
        $this->entityManager = $em;
        $this->participanteService = $participanteService;
        $this->pesquisadorService = $pesquisadorService;
    }
  
    public function getAll ()
    {
      $qb = $this->entityManager->createQueryBuilder();
      $qb->select(array('Q'))
      ->from('Quiz', 'Q')
      ->join('Q.questoes', 'QU')
      ->join('QU.opcoes', 'O');
      
      return $qb->getQuery()->getResult();
    }
    
  public function save(Participante $participante, Pesquisador $pesquisador, $listOpcoesQuestao, $inicio, $latitude, $longitude, $endereco)
  {
    if (!count($listOpcoesQuestao)) {
      throw new ValidException("Não foi selecionado questões para finalizar questionário");
    }
  
    if (!$pesquisador->getNome()) {
      throw new ValidException("Não foi identificado pesquisador");
    }
    
    if (!$participante->getNome()) {
      throw new ValidException("Não foi identificado participante");
    }
    $this->participanteService->save($participante);
    $pesquisador = $this->pesquisadorService->getRepository()->find($pesquisador->getId());
  
    
    $pesquisa = new Pesquisa();
    $pesquisa->setLatitude($latitude);
    $pesquisa->setLongitude($longitude);
    $pesquisa->setEndereco($endereco);
    $pesquisa->setDtInicio(new \DateTime($inicio));
    $pesquisa->setDtFim(new \DateTime());
    $pesquisa->setPesquisador($pesquisador);
    $pesquisa->setParticipante($participante);
  
    if (count($listOpcoesQuestao)) {
      foreach ($listOpcoesQuestao as $item) {
        $entity = $this->getOpcaoRepository()->find($item["id"]);
        $pesquisa->addOpcoesQuestao($entity);
      }
    }
  
    $this->entityManager->persist($pesquisa);
    $this->entityManager->flush();
  }
  
  /**
   * @return \Doctrine\ORM\EntityRepository
   */
  public function getRepository() {
    return $this->entityManager->getRepository('QuizBundle:Quiz');
  }
  
  /**
   * @return \Doctrine\ORM\EntityRepository
   */
  public function getPesquisaRepository() {
    return $this->entityManager->getRepository('QuizBundle:Pesquisa');
  }
  
  /**
   * @return \Doctrine\ORM\EntityRepository
   */
  public function getOpcaoRepository() {
    return $this->entityManager->getRepository('QuizBundle:OpcoesQuestao');
  }
}
