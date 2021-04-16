<?php

namespace QuizBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ingresso\CoreBundle\Entity\Entity;

/**
 * Pesquisa
 *
 * @ORM\Table(name="pesquisa", uniqueConstraints={@ORM\UniqueConstraint(name="idpesquisa_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_pesquisa_pesquisador1_idx", columns={"pesquisador_id"}), @ORM\Index(name="fk_pesquisa_participante1_idx", columns={"participante_id"})})
 * @ORM\Entity(repositoryClass="QuizBundle\Repository\PesquisaRepository")
 */
class Pesquisa extends Entity
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
     * @var \DateTime
     *
     * @ORM\Column(name="dt_inicio", type="datetime", nullable=false)
     */
    private $dtInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_fim", type="datetime", nullable=false)
     */
    private $dtFim;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="string", length=45, nullable=false)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="string", length=45, nullable=false)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="endereco", type="string", length=255, nullable=true)
     */
    private $endereco;

    /**
     * @var \Participante
     *
     * @ORM\ManyToOne(targetEntity="Participante")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="participante_id", referencedColumnName="id_participante")
     * })
     */
    private $participante;

    /**
     * @var \Pesquisador
     *
     * @ORM\ManyToOne(targetEntity="Pesquisador")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pesquisador_id", referencedColumnName="id")
     * })
     */
    private $pesquisador;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="OpcoesQuestao")
     * @ORM\JoinTable(name="pesquisa_opcoes_questao",
     *   joinColumns={
     *     @ORM\JoinColumn(name="pesquisa_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="opcoes_questao_id", referencedColumnName="id")
     *   }
     * )
     */
    private $opcoesQuestao;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->opcoesQuestao = new \Doctrine\Common\Collections\ArrayCollection();
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
   * @return string
   */
  public function getLatitude()
  {
    return $this->latitude;
  }
  
  /**
   * @param string $latitude
   */
  public function setLatitude($latitude)
  {
    $this->latitude = $latitude;
  }
  
  /**
   * @return string
   */
  public function getLongitude()
  {
    return $this->longitude;
  }
  
  /**
   * @param string $longitude
   */
  public function setLongitude($longitude)
  {
    $this->longitude = $longitude;
  }
  
  /**
   * @return string
   */
  public function getEndereco()
  {
    return $this->endereco;
  }
  
  /**
   * @param string $endereco
   */
  public function setEndereco($endereco)
  {
    $this->endereco = $endereco;
  }
  
  /**
   * @return Participante
   */
  public function getParticipante()
  {
    return $this->participante;
  }
  
  /**
   * @param Participante $participante
   */
  public function setParticipante($participante)
  {
    $this->participante = $participante;
  }
  
  /**
   * @return Pesquisador
   */
  public function getPesquisador()
  {
    return $this->pesquisador;
  }
  
  /**
   * @param Pesquisador $pesquisador
   */
  public function setPesquisador($pesquisador)
  {
    $this->pesquisador = $pesquisador;
  }
  
  /**
   * @return \Doctrine\Common\Collections\Collection
   */
  public function getOpcoesQuestao()
  {
    return $this->opcoesQuestao;
  }
  
  /**
   * @param \Doctrine\Common\Collections\Collection $opcoesQuestao
   */
  public function addOpcoesQuestao($opcoesQuestao)
  {
    $this->opcoesQuestao->add($opcoesQuestao);
  }
}

