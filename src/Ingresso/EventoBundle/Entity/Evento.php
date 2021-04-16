<?php

namespace Ingresso\EventoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Ingresso\CoreBundle\Entity\Entity;

/**
 * Evento
 *
 * @ORM\Table(name="evento")
 * @ORM\Entity(repositoryClass="Ingresso\EventoBundle\Repository\EventoRepository")
 */
class Evento extends Entity
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
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=255)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="imagem", type="text")
     */
    private $imagem;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dataEvento", type="datetime")
     */
    private $dataEvento;

    /**
     * @var string
     *
     * @ORM\Column(name="local", type="string", length=255)
     */
    private $local;

    /**
     * @var string
     *
     * @ORM\Column(name="informacao", type="string", length=4000, nullable=true)
     */
    private $informacao;

    /**
     * @var string
     *
     * @ORM\Column(name="uf", type="string", length=2)
     */
    private $uf;

    /**
     * @OneToMany(targetEntity="Ingresso\EventoBundle\Entity\Lote", mappedBy="evento")
     */
    protected $lotes;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ativo", type="boolean", nullable=true, options={"default" : true})
     */
    private $ativo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="stBanner", type="boolean", nullable=true, options={"default" : false})
     */
    private $stBanner;

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

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
     * Set nome
     *
     * @param string $nome
     *
     * @return Evento
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get nome
     *
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set imagem
     *
     * @param string $imagem
     *
     * @return Evento
     */
    public function setImagem($imagem)
    {
        $this->imagem = $imagem;

        return $this;
    }

    /**
     * Get imagem
     *
     * @return string
     */
    public function getImagem()
    {
        return $this->imagem;
    }

    /**
     * Set dataEvento
     *
     * @param \DateTime $dataEvento
     *
     * @return Evento
     */
    public function setDataEvento($dataEvento)
    {
        $this->dataEvento = $dataEvento;

        return $this;
    }

    /**
     * Get dataEvento
     *
     * @return \DateTime
     */
    public function getDataEvento()
    {
        return $this->dataEvento;
    }

    /**
     * Set local
     *
     * @param string $local
     *
     * @return Evento
     */
    public function setLocal($local)
    {
        $this->local = $local;

        return $this;
    }

    /**
     * Get local
     *
     * @return string
     */
    public function getLocal()
    {
        return $this->local;
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
    public function getUf()
    {
        return $this->uf;
    }

    /**
     * @param mixed $lotes
     */
    public function addLotes($lotes)
    {
        $this->lotes[] = $lotes;
    }

    /**
     * @return mixed
     */
    public function getLotes()
    {
        return $this->lotes;
    }

    /**
     * @param string $informacao
     */
    public function setInformacao($informacao)
    {
        $this->informacao = $informacao;
    }

    /**
     * @return string
     */
    public function getInformacao()
    {
        return $this->informacao;
    }

    /**
     * @param boolean $ativo
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
    }

    /**
     * @return boolean
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * @return boolean
     */
    public function getStBanner()
    {
        return $this->stBanner;
    }

    /**
     * @param boolean $stBanner
     */
    public function setStBanner($stBanner)
    {
        $this->stBanner = $stBanner;
    }
}
