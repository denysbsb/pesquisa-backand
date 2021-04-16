<?php

namespace QuizBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ingresso\CoreBundle\Entity\Entity;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Questao
 *
 * @ORM\Table(name="questao", uniqueConstraints={@ORM\UniqueConstraint(name="id_questao_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_questao_quiz1_idx", columns={"quiz_id"})})
 * @ORM\Entity(repositoryClass="QuizBundle\Repository\QuestaoRepository")
 */
class Questao extends Entity
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
     * @ORM\Column(name="questao", type="string", length=100, nullable=false)
     */
    private $questao;

    /**
     * @var string
     *
     * @ORM\Column(name="descricao", type="string", length=255, nullable=true)
     */
    private $descricao;

    /**
     * @var boolean
     *
     * @ORM\Column(name="st_ativo", type="boolean", nullable=false)
     */
    private $stAtivo = '1';

    /**
     * @var \Quiz
     *
     * @ORM\ManyToOne(targetEntity="Quiz")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="quiz_id", referencedColumnName="id")
     * })
     */
    private $quiz;
  
    /**
     *
     * @OneToMany(targetEntity="OpcoesQuestao", mappedBy="questao")
     */
    private $opcoes;
  
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
  public function getQuestao()
  {
    return $this->questao;
  }
  
  /**
   * @param string $questao
   */
  public function setQuestao($questao)
  {
    $this->questao = $questao;
  }
  
  /**
   * @return string
   */
  public function getDescricao()
  {
    return $this->descricao;
  }
  
  /**
   * @param string $descricao
   */
  public function setDescricao($descricao)
  {
    $this->descricao = $descricao;
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
   * @return Quiz
   */
  public function getQuiz()
  {
    return $this->quiz;
  }
  
  /**
   * @param Quiz $quiz
   */
  public function setQuiz($quiz)
  {
    $this->quiz = $quiz;
  }
  
  /**
   * @return mixed
   */
  public function getOpcoes()
  {
    return $this->opcoes;
  }
  
  /**
   * @param mixed $opcoes
   */
  public function setOpcoes($opcoes)
  {
    $this->opcoes = $opcoes;
  }
}

