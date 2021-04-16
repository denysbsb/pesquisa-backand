<?php

namespace QuizBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ingresso\CoreBundle\Entity\Entity;

/**
 * Participante
 *
 * @ORM\Table(name="participante", uniqueConstraints={@ORM\UniqueConstraint(name="idparticipante_UNIQUE", columns={"id_participante"})})
 * @ORM\Entity(repositoryClass="QuizBundle\Repository\ParticipanteRepository")
 */
class Participante extends Entity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_participante", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idParticipante;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=100, nullable=true)
     */
    private $nome;
  
    /**
     * @var string
     *
     * @ORM\Column(name="sexo", type="string", length=1, nullable=true)
     */
    private $sexo;

    /**
     * @var string
     *
     * @ORM\Column(name="documento", type="string", length=45, nullable=true)
     */
    private $documento;

    /**
     * @var string
     *
     * @ORM\Column(name="endereco", type="string", length=100, nullable=true)
     */
    private $endereco;

    /**
     * @var string
     *
     * @ORM\Column(name="uf", type="string", length=2, nullable=true)
     */
    private $uf;

    /**
     * @var string
     *
     * @ORM\Column(name="Bairro", type="string", length=100, nullable=true)
     */
    private $bairro;

    /**
     * @var string
     *
     * @ORM\Column(name="Cidade", type="string", length=100, nullable=true)
     */
    private $cidade;

    /**
     * @var integer
     *
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero;
  
    /**
     * @var integer
     *
     * @ORM\Column(name="idade", type="integer", nullable=true)
     */
    private $idade;
  
    /**
     * @var string
     *
     * @ORM\Column(name="celular", type="string", length=20, nullable=true)
     */
    private $celular;

    /**
     * @var string
     *
     * @ORM\Column(name="complemento", type="string", length=45, nullable=true)
     */
    private $complemento;
  
  /**
   * @return int
   */
  public function getIdParticipante()
  {
    return $this->idParticipante;
  }
  
  /**
   * @param int $idParticipante
   */
  public function setIdParticipante($idParticipante)
  {
    $this->idParticipante = $idParticipante;
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
  public function getSexo()
  {
    return $this->sexo;
  }
  
  /**
   * @param string $sexo
   */
  public function setSexo($sexo)
  {
    $this->sexo = $sexo;
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
   * @return string
   */
  public function getUf()
  {
    return $this->uf;
  }
  
  /**
   * @param string $uf
   */
  public function setUf($uf)
  {
    $this->uf = $uf;
  }
  
  /**
   * @return string
   */
  public function getBairro()
  {
    return $this->bairro;
  }
  
  /**
   * @param string $bairro
   */
  public function setBairro($bairro)
  {
    $this->bairro = $bairro;
  }
  
  /**
   * @return string
   */
  public function getCidade()
  {
    return $this->cidade;
  }
  
  /**
   * @param string $cidade
   */
  public function setCidade($cidade)
  {
    $this->cidade = $cidade;
  }
  
  /**
   * @return int
   */
  public function getNumero()
  {
    return $this->numero;
  }
  
  /**
   * @param int $numero
   */
  public function setNumero($numero)
  {
    $this->numero = $numero;
  }
  
  /**
   * @return string
   */
  public function getComplemento()
  {
    return $this->complemento;
  }
  
  /**
   * @param string $complemento
   */
  public function setComplemento($complemento)
  {
    $this->complemento = $complemento;
  }
  
  /**
   * @return int
   */
  public function getIdade()
  {
    return $this->idade;
  }
  
  /**
   * @param int $idade
   */
  public function setIdade($idade)
  {
    $this->idade = $idade;
  }
  
  /**
   * @return string
   */
  public function getCelular()
  {
    return $this->celular;
  }
  
  /**
   * @param string $celular
   */
  public function setCelular($celular)
  {
    $this->celular = $celular;
  }
}

