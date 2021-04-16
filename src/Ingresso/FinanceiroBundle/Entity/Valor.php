<?php

namespace Ingresso\FinanceiroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ingresso\CoreBundle\Entity\Entity;

/**
 * Valor
 *
 * @ORM\Table(name="valor")
 * @ORM\Entity(repositoryClass="Ingresso\FinanceiroBundle\Repository\ValorRepository")
 */
class Valor extends Entity
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
     * @var \Ingresso\EventoBundle\Entity\Lote
     *
     * @ORM\ManyToOne(targetEntity="Ingresso\EventoBundle\Entity\Lote", cascade={"persist"})
     * @ORM\JoinColumn(name="idLote", referencedColumnName="id", nullable=true)
     */
    private $lote;

    /**
     * @var string
     *
     * @ORM\Column(name="descricao", type="string", length=255)
     */
    private $descricao;

    /**
     * @var string
     *
     * @ORM\Column(name="valor", type="decimal", precision=12, scale=2)
     */
    private $valor;

    /**
     * @var string
     *
     * @ORM\Column(name="taxa", type="decimal", precision=12, scale=2)
     */
    private $taxa;

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
     * @param \Ingresso\EventoBundle\Entity\Lote $lote
     */
    public function setLote($lote)
    {
        $this->lote = $lote;
    }

    /**
     * @return \Ingresso\EventoBundle\Entity\Lote
     */
    public function getLote()
    {
        return $this->lote;
    }

    /**
     * Set descricao
     *
     * @param string $descricao
     *
     * @return Valor
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
     * Set valor
     *
     * @param string $valor
     *
     * @return Valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return string
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set taxa
     *
     * @param string $taxa
     *
     * @return Valor
     */
    public function setTaxa($taxa)
    {
        $this->taxa = $taxa;

        return $this;
    }

    /**
     * Get taxa
     *
     * @return string
     */
    public function getTaxa()
    {
        return $this->taxa;
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

