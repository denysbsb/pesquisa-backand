<?php

namespace Ingresso\EventoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ingresso\CoreBundle\Entity\Entity;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Lote
 *
 * @ORM\Table(name="lote")
 * @ORM\Entity(repositoryClass="Ingresso\EventoBundle\Repository\LoteRepository")
 */
class Lote extends Entity
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
     * @var int
     *
     * @ORM\Column(name="numero", type="integer")
     */
    private $numero;

    /**
     * @var \Ingresso\EventoBundle\Entity\Evento
     *
     * @ORM\ManyToOne(targetEntity="Ingresso\EventoBundle\Entity\Evento", cascade={"persist"})
     * @ORM\JoinColumn(name="idEvento", referencedColumnName="id", nullable=true)
     */
    private $evento;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dataInicio", type="datetime")
     */
    private $dataInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dataFim", type="datetime")
     */
    private $dataFim;

    /**
     * @var string
     *
     * @ORM\Column(name="descricao", type="string", length=255)
     */
    private $descricao;

    /**
     * @var int
     *
     * @ORM\Column(name="quantidade", type="integer")
     */
    private $quantidade;

    /**
     * @OneToMany(targetEntity="Ingresso\FinanceiroBundle\Entity\Valor", mappedBy="lote")
     */
    protected $valores;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ativo", type="boolean", nullable=true, options={"default" : true})
     */
    private $ativo;

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
     * Set numero
     *
     * @param integer $numero
     *
     * @return Lote
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return int
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set dataInicio
     *
     * @param \DateTime $dataInicio
     *
     * @return Lote
     */
    public function setDataInicio($dataInicio)
    {
        $this->dataInicio = $dataInicio;

        return $this;
    }

    /**
     * Get dataInicio
     *
     * @return \DateTime
     */
    public function getDataInicio()
    {
        return $this->dataInicio;
    }

    /**
     * Set dataFim
     *
     * @param \DateTime $dataFim
     *
     * @return Lote
     */
    public function setDataFim($dataFim)
    {
        $this->dataFim = $dataFim;

        return $this;
    }

    /**
     * Get dataFim
     *
     * @return \DateTime
     */
    public function getDataFim()
    {
        return $this->dataFim;
    }

    /**
     * Set descricao
     *
     * @param string $descricao
     *
     * @return Lote
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * Get descricao
     *
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set quantidade
     *
     * @param integer $quantidade
     *
     * @return Lote
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;

        return $this;
    }

    /**
     * Get quantidade
     *
     * @return int
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * @param \Ingresso\EventoBundle\Entity\Evento $evento
     */
    public function setEvento($evento)
    {
        $this->evento = $evento;
    }

    /**
     * @return \Ingresso\EventoBundle\Entity\Evento
     */
    public function getEvento()
    {
        return $this->evento;
    }

    /**
     * @param mixed $valores
     */
    public function addValores($valores)
    {
        $this->valores[] = $valores;
    }

    /**
     * @return mixed
     */
    public function getValores()
    {
        return $this->valores;
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

}
