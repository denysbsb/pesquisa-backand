<?php

namespace Ingresso\EventoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Solicitacao
 *
 * @ORM\Table(name="solicitacao")
 * @ORM\Entity(repositoryClass="Ingresso\EventoBundle\Repository\SolicitacaoRepository")
 */
class Solicitacao
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
     * @ORM\Column(name="dataSolicitacao", type="datetime")
     */
    private $dataSolicitacao;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dataAtendimento", type="datetime", nullable=true)
     */
    private $dataAtendimento;

    /**
     * @var \Ingresso\UsuarioBundle\Entity\Usuario
     *
     * @ORM\ManyToOne(targetEntity="Ingresso\UsuarioBundle\Entity\Usuario", cascade={"persist"})
     * @ORM\JoinColumn(name="idUsuarioSolicitante", referencedColumnName="id", nullable=false)
     */
    private $usuarioSolicitante;

    /**
     * @var \Ingresso\UsuarioBundle\Entity\Usuario
     *
     * @ORM\ManyToOne(targetEntity="Ingresso\UsuarioBundle\Entity\Usuario", cascade={"persist"})
     * @ORM\JoinColumn(name="idUsuarioAtendimento", referencedColumnName="id", nullable=true)
     */
    private $usuarioAtendimento;

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
     * Set dataSolicitacao
     *
     * @param \DateTime $dataSolicitacao
     *
     * @return Solicitacao
     */
    public function setDataSolicitacao($dataSolicitacao)
    {
        $this->dataSolicitacao = $dataSolicitacao;

        return $this;
    }

    /**
     * Get dataSolicitacao
     *
     * @return \DateTime
     */
    public function getDataSolicitacao()
    {
        return $this->dataSolicitacao;
    }

    /**
     * Set dataAtendimento
     *
     * @param \DateTime $dataAtendimento
     *
     * @return Solicitacao
     */
    public function setDataAtendimento($dataAtendimento)
    {
        $this->dataAtendimento = $dataAtendimento;

        return $this;
    }

    /**
     * Get dataAtendimento
     *
     * @return \DateTime
     */
    public function getDataAtendimento()
    {
        return $this->dataAtendimento;
    }

    /**
     * @param \Ingresso\UsuarioBundle\Entity\Usuario $usuarioAtendimento
     */
    public function setUsuarioAtendimento($usuarioAtendimento)
    {
        $this->usuarioAtendimento = $usuarioAtendimento;
    }

    /**
     * @return \Ingresso\UsuarioBundle\Entity\Usuario
     */
    public function getUsuarioAtendimento()
    {
        return $this->usuarioAtendimento;
    }

    /**
     * @param \Ingresso\UsuarioBundle\Entity\Usuario $usuarioSolicitante
     */
    public function setUsuarioSolicitante($usuarioSolicitante)
    {
        $this->usuarioSolicitante = $usuarioSolicitante;
    }

    /**
     * @return \Ingresso\UsuarioBundle\Entity\Usuario
     */
    public function getUsuarioSolicitante()
    {
        return $this->usuarioSolicitante;
    }
}

