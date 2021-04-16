<?php

namespace Ingresso\FinanceiroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ingresso\CoreBundle\Entity\Entity;

/**
 * Pagamento
 *
 * @ORM\Table(name="pagamento")
 * @ORM\Entity(repositoryClass="Ingresso\FinanceiroBundle\Repository\PagamentoRepository")
 */
class Pagamento extends Entity
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
     * @var \DateTime
     *
     * @ORM\Column(name="dataPagamento", type="datetime", nullable=true)
     */
    private $dataPagamento;

    /**
     * @var \Ingresso\UsuarioBundle\Entity\Usuario
     *
     * @ORM\ManyToOne(targetEntity="Ingresso\UsuarioBundle\Entity\Usuario", cascade={"persist"})
     * @ORM\JoinColumn(name="idUsuario", referencedColumnName="id", nullable=true)
     */
    private $usuario;

    /**
     * @var \Ingresso\FinanceiroBundle\Entity\Valor
     *
     * @ORM\ManyToOne(targetEntity="Ingresso\FinanceiroBundle\Entity\Valor", cascade={"persist"})
     * @ORM\JoinColumn(name="idValor", referencedColumnName="id", nullable=true)
     */
    private $valores;

    /**
     * @var string
     *
     * @ORM\Column(name="valor", type="decimal", precision=12, scale=2)
     */
    private $valor;

    /**
     * @var int
     *
     * @ORM\Column(name="quantidade", type="integer")
     */
    private $quantidade;

    /**
     * @var string
     *
     * @ORM\Column(name="tipoPagamento", type="string", length=2)
     */
    private $tipoPagamento;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dataUtilizacao", type="datetime", nullable=true)
     */
    private $dataUtilizacao;


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
     * @param \Ingresso\FinanceiroBundle\Entity\Valor $valores
     */
    public function setValores($valores)
    {
        $this->valores = $valores;
    }

    /**
     * @return \Ingresso\FinanceiroBundle\Entity\Valor
     */
    public function getValores()
    {
        return $this->valores;
    }

    /**
     * Set usuario
     *
     * @param \Ingresso\UsuarioBundle\Entity\Usuario $usuario
     *
     * @return \Ingresso\UsuarioBundle\Entity\Usuario
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
     * Set dataPagamento
     *
     * @param \DateTime $dataPagamento
     *
     * @return Pagamento
     */
    public function setDataPagamento($dataPagamento)
    {
        $this->dataPagamento = $dataPagamento;

        return $this;
    }

    /**
     * Get dataPagamento
     *
     * @return \DateTime
     */
    public function getDataPagamento()
    {
        return $this->dataPagamento;
    }

    /**
     * Set valor
     *
     * @param string $valor
     *
     * @return Pagamento
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
     * Set quantidade
     *
     * @param integer $quantidade
     *
     * @return Pagamento
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
     * Set tipoPagamento
     *
     * @param string $tipoPagamento
     *
     * @return Pagamento
     */
    public function setTipoPagamento($tipoPagamento)
    {
        $this->tipoPagamento = $tipoPagamento;

        return $this;
    }

    /**
     * Get tipoPagamento
     *
     * @return string
     */
    public function getTipoPagamento()
    {
        return $this->tipoPagamento;
    }

    /**
     * @param \DateTime $dataUtilizacao
     */
    public function setDataUtilizacao($dataUtilizacao)
    {
        $this->dataUtilizacao = $dataUtilizacao;
    }

    /**
     * @return \DateTime
     */
    public function getDataUtilizacao()
    {
        return $this->dataUtilizacao;
    }
}

