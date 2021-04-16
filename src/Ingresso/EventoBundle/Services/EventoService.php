<?php

namespace Ingresso\EventoBundle\Services;

use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityManager;
use Ingresso\CoreBundle\Exceptions\ValidException;
use Ingresso\EventoBundle\Entity\Beneficio;
use Ingresso\EventoBundle\Entity\Evento;
use Ingresso\EventoBundle\Entity\Lote;
use Ingresso\EventoBundle\Entity\Solicitacao;
use Ingresso\FinanceiroBundle\Entity\Pagamento;
use Ingresso\FinanceiroBundle\Entity\Valor;

class EventoService
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->entityManager = $em;
    }

    public function inativarEvento($id) {
        /** @var Evento $entity */
        $entity = $this->getRepositoryEvento()->find($id);
        $entity->setAtivo(false);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    public function inativarLote($id) {
        /** @var Lote $entity */
        $entity = $this->getRepositoryLote()->find($id);
        $entity->setAtivo(false);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    private function _checarSolicitacao($idUsuario) {
        $entity = $this->getRepositorySolicitacao()->findOneBy(
            array('usuarioSolicitante' => $idUsuario)
        );

        if($entity instanceof Solicitacao) {
            if($entity->getDataSolicitacao()) {
                throw new ValidException('Solicitação já atendida');
            }

            throw new ValidException('Solicitação já enviada');
        }
    }

    public function enviarSolicitacao($idUsuario) {
        $this->_checarSolicitacao($idUsuario);

        $entity = new Solicitacao();
        $entity->setDataSolicitacao(new \DateTime());
        $entity->setUsuarioSolicitante(
            $this->getRepositoryUsuario()->find($idUsuario)
        );

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    public function pesquisarEventoBanner($uf) {
        return $this->_prepararResultado(
            $this->getRepositoryEvento()->getEventoPorUFBanner($uf)
        );
    }

    public function pesquisarEventoPorUF($uf) {
        return $this->_prepararResultado(
            $this->getRepositoryEvento()->getEventoPorUF($uf)
        );
    }

    private function _prepararResultado($result) {
        $arrResult = array();
        $now = new \DateTime();

        /** @var Evento $value */
        foreach ($result as $value) {
            $arrResult[] = array(
                'id' => $value->getId(),
                'nome' => $value->getNome(),
                'imagem' => $value->getImagem(),
                'dataEvento' => $value->getDataEvento(),
                'local' => $value->getLocal(),
                'uf' => $value->getUf(),
                'informacao' => $value->getInformacao(),
                'ativo' => $value->getAtivo(),
                'lotes' => $this->getRepositoryLote()->getLotePorEvento($value),
                'visivel' => ($value->getDataEvento()->getTimestamp() >= $now->getTimestamp())
            );
        }

        return $arrResult;
    }

    public function pesquisarEvento($uf, $date) {
        if($date  && ($date != 0) && ($date != 'false')) {
            $date1 = new \DateTime($date);
            $date2 = new \DateTime();

            if($date1->getTimestamp() < $date2->getTimestamp()) {
                throw new ValidException('Data não pode ser menor que data atual');
            }
        }

        return $this->_prepararResultado( $this->getRepositoryEvento()->getEvento($uf, $date) );
    }

    public function pesquisarEventoPorUsuario($id) {
        $now = new \DateTime();
        $result = $this->getRepositoryPagamento()->getPagamentoPorUsuario($id);

        $arrResult = array();

        /** @var Pagamento $pagamento */
        foreach($result as $pagamento) {
            $arrResult[] = array(
                'id' => $pagamento->getId(),
                'nome' => $pagamento->getValores()->getLote()->getEvento()->getNome(),
                'dataEvento' => $pagamento->getValores()->getLote()->getEvento()->getDataEvento()->format('d/m'),
                'hora' => $pagamento->getValores()->getLote()->getEvento()->getDataEvento()->format('h:i'),
                'local' => $pagamento->getValores()->getLote()->getEvento()->getLocal(),
                'ingresso' => $pagamento->getValores()->getDescricao(),
                'cliente' => $pagamento->getUsuario()->getNome(),
                'dataUtilizacao' => ($pagamento->getDataUtilizacao() || null),
                'dataPagamento' => ($pagamento->getDataPagamento() || null),
                'check' => ($pagamento->getValores()->getLote()->getEvento()->getDataEvento()->getTimestamp() >= $now->getTimestamp())
            );
        }

        return $arrResult;
    }

    private function _validaEvento(Evento $evento) {
        if(!$evento->getNome()) {
            throw new ValidException('Nome é obrigatório');
        }

        if(!$evento->getLocal()) {
            throw new ValidException('Local é obrigatório');
        }

        if(!$evento->getUf()) {
            throw new ValidException('UF é obrigatório');
        }

        if(!$evento->getDataEvento()) {
            throw new ValidException('Data é obrigatório');
        }
    }

    public function salvarEvento($arrEvento) {
        $entity = new Evento($arrEvento);

        if($entity->getId()) {
            $entity = $this->getRepositoryEvento()->find($entity->getId());
            $entity->setData($arrEvento);
        }

        $this->_validaEvento($entity);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    public function removerBeneficio($id) {
        $entity = $this->getRepositoryBeneficio()->find($id);
        $entity->setAtivo(false);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    public function salvarBeneficio($arrBeneficio) {
        $entity = new Beneficio($arrBeneficio);

        if($entity->getId()) {
            $entity = $this->getRepositoryBeneficio()->find($entity->getId());
            $entity->setData($arrBeneficio);
        }

        //$this->_validaEvento($entity);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    public function getBeneficio() {
        $result = $this->getRepositoryBeneficio()->findBy(
            array('ativo' => true)
        );
        $arrResult = array();

        /** @var Beneficio $beneficio */
        foreach($result as $beneficio) {
            $arrResult[] = array(
                'id' => $beneficio->getId(),
                'titulo' => $beneficio->getTitulo(),
                'descricao' => $beneficio->getDescricao(),
                'imagem' => $beneficio->getImagem(),
                'ativo' => $beneficio->getAtivo()
            );
        }

        return $arrResult;
    }

    private function _validaLote(Lote $lote) {
        if(!($lote->getEvento() instanceof Evento)) {
            throw new ValidException('Evento é obrigatório');
        }

        if(!$lote->getNumero()) {
            throw new ValidException('Número é obrigatório');
        }

        if(!$lote->getDataInicio()) {
            throw new ValidException('Data início é obrigatório');
        }

        if(!$lote->getDataFim()) {
            throw new ValidException('Data fim é obrigatório');
        }

        if(!$lote->getDescricao()) {
            throw new ValidException('Descrição é obrigatório');
        }

        if(!$lote->getQuantidade()) {
            throw new ValidException('Quantidade é obrigatório');
        }
    }

    public function criarLote($idEvento, $arrLote) {
        $entity = new Lote($arrLote);
        $entity->setEvento(
            $this->getRepositoryEvento()->find($idEvento)
        );

        $this->_validaLote($entity);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    private function _validaValor(Valor $valor) {
        if(!($valor->getLote() instanceof Lote)) {
            throw new ValidException('Lote é obrigatório');
        }

        if(!$valor->getDescricao()) {
            throw new ValidException('Descrição é obrigatório');
        }

        if(!$valor->getValor()) {
            throw new ValidException('Valor é obrigatório');
        }

        if(!$valor->getTaxa()) {
            throw new ValidException('Taxa é obrigatório');
        }
    }

    public function criarValorLote($idLote, $arrValor) {
        $entity = new Valor($arrValor);
        $entity->setLote(
            $this->getRepositoryLote()->find($idLote)
        );

        $this->_validaValor($entity);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    public function getRepositoryUsuario() {
        return $this->entityManager->getRepository('IngressoUsuarioBundle:Usuario');
    }

    public function getRepositoryEvento() {
        return $this->entityManager->getRepository('IngressoEventoBundle:Evento');
    }

    public function getRepositoryLote() {
        return $this->entityManager->getRepository('IngressoEventoBundle:Lote');
    }

    public function getRepositoryBeneficio() {
        return $this->entityManager->getRepository('IngressoEventoBundle:Beneficio');
    }

    public function getRepositoryPagamento() {
        return $this->entityManager->getRepository('IngressoFinanceiroBundle:Pagamento');
    }

    public function getRepositorySolicitacao() {
        return $this->entityManager->getRepository('IngressoEventoBundle:Solicitacao');
    }
}
