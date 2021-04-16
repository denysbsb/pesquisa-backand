<?php

namespace Ingresso\FinanceiroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ingresso\CoreBundle\Entity\Entity;

/**
 * Cartao
 *
 * @ORM\Table(name="cartao")
 * @ORM\Entity(repositoryClass="Ingresso\FinanceiroBundle\Repository\CartaoRepository")
 */
class Cartao extends Entity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \Ingresso\UsuarioBundle\Entity\Usuario
     *
     * @ORM\ManyToOne(targetEntity="Ingresso\UsuarioBundle\Entity\Usuario", cascade={"persist"})
     * @ORM\JoinColumn(name="idUsuario", referencedColumnName="id", nullable=true)
     */
    private $usuario;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=20)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="numero", type="string", length=50)
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="imagem", type="text", nullable=true)
     */
    private $imagem;

    /**
     * @var int
     *
     * @ORM\Column(name="mes", type="integer")
     */
    private $mes;

    /**
     * @var string
     *
     * @ORM\Column(name="ano", type="integer")
     */
    private $ano;

    /**
     * @var int
     *
     * @ORM\Column(name="codigo", type="integer")
     */
    private $codigo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dtAceiteTermo", type="datetime")
     */
    private $dtAceiteTermo;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \Ingresso\UsuarioBundle\Entity\Usuario $usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * @return \Ingresso\UsuarioBundle\Entity\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param string $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param string $imagem
     */
    public function setImagem($imagem)
    {
        $this->imagem = $imagem;
    }

    /**
     * @return string
     */
    public function getImagem()
    {
        return $this->imagem;
    }

    /**
     * Set numero
     *
     * @param string $numero
     *
     * @return Cartao
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set mes
     *
     * @param integer $mes
     *
     * @return Cartao
     */
    public function setMes($mes)
    {
        $this->mes = $mes;

        return $this;
    }

    /**
     * Get mes
     *
     * @return int
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * Set ano
     *
     * @param string $ano
     *
     * @return Cartao
     */
    public function setAno($ano)
    {
        $this->ano = $ano;

        return $this;
    }

    /**
     * Get ano
     *
     * @return string
     */
    public function getAno()
    {
        return $this->ano;
    }

    /**
     * Set codigo
     *
     * @param integer $codigo
     *
     * @return Cartao
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set dtAceiteTermo
     *
     * @param \DateTime $dtAceiteTermo
     *
     * @return Cartao
     */
    public function setDtAceiteTermo($dtAceiteTermo)
    {
        $this->dtAceiteTermo = $dtAceiteTermo;

        return $this;
    }

    /**
     * Get dtAceiteTermo
     *
     * @return \DateTime
     */
    public function getDtAceiteTermo()
    {
        return $this->dtAceiteTermo;
    }
}

