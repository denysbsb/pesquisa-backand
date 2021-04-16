<?php

namespace QuizBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ingresso\CoreBundle\Entity\Entity;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Quiz
 *
 * @ORM\Table(name="quiz", uniqueConstraints={@ORM\UniqueConstraint(name="id_quiz_UNIQUE", columns={"id"})})
 * @ORM\Entity(repositoryClass="QuizBundle\Repository\QuizRepository")
 */
class Quiz extends Entity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=255, nullable=false)
     */
    private $nome;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_cadastro", type="datetime", nullable=false)
     */
    private $dtCadastro;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_inicio", type="datetime", nullable=true)
     */
    private $dtInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_fim", type="datetime", nullable=true)
     */
    private $dtFim;

    /**
     * @var boolean
     *
     * @ORM\Column(name="st_ativo", type="boolean", nullable=false)
     */
    private $stAtivo = '1';
  
    /**
     *
     * @OneToMany(targetEntity="Questao", mappedBy="quiz")
     */
    private $questoes;
  
  /**
   * @return int
   */
  public function getId()
  {
    return $this->id;
  }
  
  /**
   * @param int $id
   */
  public function setId($id)
  {
    $this->id = $id;
  }
  
  /**
   * @return string
   */
  public function getNome()
  {
    return $this->nome;
  }
  
  /**
   * @param string $nome
   */
  public function setNome($nome)
  {
    $this->nome = $nome;
  }
  
  /**
   * @return DateTime
   */
  public function getDtCadastro()
  {
    return $this->dtCadastro;
  }
  
  /**
   * @param DateTime $dtCadastro
   */
  public function setDtCadastro($dtCadastro)
  {
    $this->dtCadastro = $dtCadastro;
  }
  
  /**
   * @return DateTime
   */
  public function getDtInicio()
  {
    return $this->dtInicio;
  }
  
  /**
   * @param DateTime $dtInicio
   */
  public function setDtInicio($dtInicio)
  {
    $this->dtInicio = $dtInicio;
  }
  
  /**
   * @return DateTime
   */
  public function getDtFim()
  {
    return $this->dtFim;
  }
  
  /**
   * @param DateTime $dtFim
   */
  public function setDtFim($dtFim)
  {
    $this->dtFim = $dtFim;
  }
  
  /**
   * @return bool
   */
  public function isStAtivo()
  {
    return $this->stAtivo;
  }
  
  /**
   * @param bool $stAtivo
   */
  public function setStAtivo($stAtivo)
  {
    $this->stAtivo = $stAtivo;
  }
  
  /**
   * @return mixed
   */
  public function getQuestoes()
  {
    return $this->questoes;
  }
  
  /**
   * @param mixed $questoes
   */
  public function setQuestoes($questoes)
  {
    $this->questoes = $questoes;
  }
}

