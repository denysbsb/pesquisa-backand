<?php

namespace QuizBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ingresso\CoreBundle\Entity\Entity;
use JMS\Serializer\Annotation as Serializer;

/**
 * OpcoesQuestao
 *
 * @ORM\Table(name="opcoes_questao", uniqueConstraints={@ORM\UniqueConstraint(name="idopcoes_questao_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_opcoes_questao_questao_idx", columns={"questao_id"})})
 * @ORM\Entity(repositoryClass="QuizBundle\Repository\OpcoesQuestaoRepository")
 */
class OpcoesQuestao extends Entity
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
     * @ORM\Column(name="opcao", type="string", length=100, nullable=false)
     */
    private $opcao;

    /**
     * @var boolean
     *
     * @ORM\Column(name="st_ativo", type="boolean", nullable=false)
     */
    private $stAtivo = '1';

    /**
     * @var \Questao
     *
     * @ORM\ManyToOne(targetEntity="Questao")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="questao_id", referencedColumnName="id")
     * })
     */
    private $questao;

    /**
     * Constructor
     */
    public function __construct()
    {
    }
  
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
  public function getOpcao()
  {
    return $this->opcao;
  }
  
  /**
   * @param string $opcao
   */
  public function setOpcao($opcao)
  {
    $this->opcao = $opcao;
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
   * @return Questao
   */
  public function getQuestao()
  {
    return $this->questao;
  }
  
  /**
   * @param Questao $questao
   */
  public function setQuestao($questao)
  {
    $this->questao = $questao;
  }
}

