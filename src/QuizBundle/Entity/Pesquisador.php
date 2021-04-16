<?php

namespace QuizBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ingresso\CoreBundle\Entity\Entity;

/**
 * Pesquisador
 *
 * @ORM\Table(name="pesquisador", uniqueConstraints={@ORM\UniqueConstraint(name="idtb_pesquisador_UNIQUE", columns={"id"})})
 * @ORM\Entity(repositoryClass="QuizBundle\Repository\PesquisadorRepository")
 */
class Pesquisador extends Entity
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
     * @ORM\Column(name="nome", type="string", length=100, nullable=false)
     */
    private $nome;
  
    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=5, nullable=false)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="documento", type="string", length=45, nullable=false)
     */
    private $documento;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_cadastro", type="datetime", nullable=true)
     */
    private $dtCadastro;

    /**
     * @var boolean
     *
     * @ORM\Column(name="st_ativo", type="boolean", nullable=true)
     */
    private $stAtivo = '1';
  
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
   * @return string
   */
  public function getCodigo()
  {
    return $this->codigo;
  }
  
  /**
   * @param string $codigo
   */
  public function setCodigo($codigo)
  {
    $this->codigo = $codigo;
  }
  
  /**
   * @return string
   */
  public function getDocumento()
  {
    return $this->documento;
  }
  
  /**
   * @param string $documento
   */
  public function setDocumento($documento)
  {
    $this->documento = $documento;
  }
  
  /**
   * @return string
   */
  public function getEmail()
  {
    return $this->email;
  }
  
  /**
   * @param string $email
   */
  public function setEmail($email)
  {
    $this->email = $email;
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
}

