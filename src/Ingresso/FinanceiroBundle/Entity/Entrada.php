<?php

namespace Ingresso\FinanceiroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entrada
 *
 * @ORM\Table(name="entrada")
 * @ORM\Entity(repositoryClass="Ingresso\FinanceiroBundle\Repository\EntradaRepository")
 */
class Entrada
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
     * @ORM\JoinColumn(name="idUsuarioBaixa", referencedColumnName="id")
     */
    private $usuarioBaixa;

    /**
     * @var \Ingresso\FinanceiroBundle\Entity\Pagamento
     *
     * @ORM\ManyToOne(targetEntity="Ingresso\FinanceiroBundle\Entity\Pagamento", cascade={"persist"})
     * @ORM\JoinColumn(name="idPagamento", referencedColumnName="id")
     */
    private $pagamento;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dataEntrada", type="datetime")
     */
    private $dataEntrada;


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
     * Set dataEntrada
     *
     * @param \DateTime $dataEntrada
     *
     * @return Entrada
     */
    public function setDataEntrada($dataEntrada)
    {
        $this->dataEntrada = $dataEntrada;

        return $this;
    }

    /**
     * Get dataEntrada
     *
     * @return \DateTime
     */
    public function getDataEntrada()
    {
        return $this->dataEntrada;
    }

    /**
     * @param \Ingresso\FinanceiroBundle\Entity\Pagamento $pagamento
     */
    public function setPagamento($pagamento)
    {
        $this->pagamento = $pagamento;
    }

    /**
     * @return \Ingresso\FinanceiroBundle\Entity\Pagamento
     */
    public function getPagamento()
    {
        return $this->pagamento;
    }

    /**
     * @param \Ingresso\UsuarioBundle\Entity\Usuario $usuarioBaixa
     */
    public function setUsuarioBaixa($usuarioBaixa)
    {
        $this->usuarioBaixa = $usuarioBaixa;
    }

    /**
     * @return \Ingresso\UsuarioBundle\Entity\Usuario
     */
    public function getUsuarioBaixa()
    {
        return $this->usuarioBaixa;
    }

}

