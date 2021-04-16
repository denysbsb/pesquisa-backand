<?php
/**
 * Created by IntelliJ IDEA.
 * User: marioeugenio
 * Date: 7/19/16
 * Time: 6:31 PM
 */

namespace Ingresso\FinanceiroBundle\Services;


use Circle\RestClientBundle\Services\RestClient;
use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityManager;
use Ingresso\CoreBundle\Exceptions\ValidException;
use Ingresso\EventoBundle\Entity\Lote;
use Ingresso\FinanceiroBundle\Entity\Cartao;
use Ingresso\FinanceiroBundle\Entity\Entrada;
use Ingresso\FinanceiroBundle\Entity\Pagamento;
use Ingresso\FinanceiroBundle\Entity\Valor;
use Ingresso\UsuarioBundle\Entity\Usuario;
use PayPal\Exception\PayPalConnectionException;
use Symfony\Component\Validator\Exception\ValidatorException;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;


class FinanceiroService {

    const URL_PAYPAL_PAYMENT = "https://api.paypal.com/v1/payments/payment";
    const URL_PAYPAL_AUTORIZATION = "https://api.paypal.com/v1/payments/authorization";
    const URL_PAYPAL_EXECUTE = "https://api.paypal.com/v1/payments/payment/";


    /**
     * @var EntityManager
     */
    private $entityManager;


    /**
     * @var \Circle\RestClientBundle\Services\RestClient
     */
    private $restClient;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, RestClient $restClient)
    {
        $this->entityManager = $em;
        $this->restClient = $restClient;
    }

    public function inativarValor($id) {
        /** @var Valor $entity */
        $entity = $this->getRepositoryValor()->find($id);
        $entity->setAtivo(false);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    private function _validaCartao(Cartao $cartao) {
        if(!($cartao->getUsuario() instanceof Usuario)) {
            throw new ValidException('Usuário é obrigatório');
        }

        if(!$cartao->getTipo()) {
            throw new ValidException('Tipo é obrigatório');
        }

        if(!$cartao->getNumero()) {
            throw new ValidException('Número é obrigatório');
        }

        if(!$cartao->getMes()) {
            throw new ValidException('Mês é obrigatório');
        }

        if(!$cartao->getAno()) {
            throw new ValidException('Ano é obrigatório');
        }

        if(!$cartao->getCodigo()) {
            throw new ValidException('Código é obrigatório');
        }
    }

    public function salvarCartao($idUsuario, $arrCartao) {
        $entity = new Cartao($arrCartao);
        $entity->setUsuario(
            $this->getRepositoryUsuario()->find($idUsuario)
        );

        if(!$entity->getId()) {
            $entity->setDtAceiteTermo(new \DateTime());
        }

        $this->_validaCartao($entity);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    public function pesquisarCartaoPorUsuario($idUsuario) {
        $result = $this->getRepositoryCartao()->findBy(
            array('usuario' => $idUsuario)
        );

        $arrResult = array();

        /** @var Cartao $cartao */
        foreach($result as $cartao) {
            $arrResult[] = array(
                'id' => $cartao->getId(),
                'tipo' => $cartao->getTipo(),
                'numero' => $cartao->getNumero(),
                'imagem' => $cartao->getImagem(),
                'vencimento' => $cartao->getMes() .'/'. $cartao->getAno(),
                'codigo' => $cartao->getCodigo()
            );
        }

        return $arrResult;
    }

    public function transferirVoucher($idUsuario, $idPagamento) {
        /** @var Pagamento $entity */
        $entity = $this->getRepositoryPagamento()->find($idPagamento);

        if(!$entity instanceof Pagamento) {
            throw new ValidException('Voucher não encontrado');
        }

        $usuario = $this->getRepositoryUsuario()->find($idUsuario);
        if(!$usuario instanceof Usuario) {
            throw new ValidException('Usuário não encontrado');
        }

        $entity->setUsuario($usuario);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    private function _validaPagamento(Pagamento $pagamento) {

        if(!($pagamento->getUsuario() instanceof Usuario)) {
            throw new ValidException('Usuário é obrigatório');
        }

        if(!$pagamento->getValor()) {
            throw new ValidException('Valor é obrigatório');
        }

        if(!$pagamento->getQuantidade()) {
            throw new ValidException('Quantidade é obrigatório');
        }

        if(!$pagamento->getTipoPagamento()) {
            throw new ValidException('Tipo Pagamento é obrigatório');
        }
    }

    public function checarQuantidadePagamento($idLote) {
        $pagamentos = $this->getRepositoryPagamento()->getQuantidadePagamento($idLote);

        if(!$pagamentos) {
            /** @var Lote $lote */
            $lote = $this->getRepositoryLote()->find($idLote);
            return array('comprado' => 0, 'disponivel' => $lote->getQuantidade(), 'total' => $lote->getQuantidade());
        }

        $quantidade = 0;
        $total = 0;

        /** @var Pagamento $pag */
        foreach($pagamentos as $pag) {
            if ($pag->getDataPagamento()) {
                $quantidade = ($quantidade + $pag->getQuantidade());
            }

            $total = $pag->getValores()->getLote()->getQuantidade();
        }

        return array('comprado' => $quantidade, 'disponivel' => ($total - $quantidade), 'total' => $total);
    }

    private function _checarQuantidadeIngressoPagamento(Pagamento $pagamento) {
        $quantidade = $this->checarQuantidadePagamento($pagamento->getValores()->getLote()->getId());
        $solicitado = $pagamento->getQuantidade();

        if($quantidade['disponivel'] < $solicitado) {
            $disponivel = $quantidade['disponivel'];

            throw new ValidException("Quantidade insuficiente de ingressos no lote, disponível $disponivel ingresso(s)");
        }
    }

    public function criarPagamento($idUsuario, $arrPagamento) {
        $arrRetorno = array();

        if(count($arrPagamento)) {
            foreach($arrPagamento->itens as $item) {
                for($i=0; $i < $item->qnt; $i++) {
                    $pagamento = array(
                        'quantidade' => 1,
                        'tipoPagamento' => 'V',
                        'valor' => ($item->item->valor + $item->item->taxa)
                    );

                    $entity = $this->salvarPagamento($idUsuario, $item->item->id, $pagamento);
                    $arrRetorno[] = $entity->getId();
                }
            }
        }

        return $arrRetorno;
    }

    public function salvarPagamento($idUsuario, $idValor, $arrPagamento) {
        $entity = new Pagamento($arrPagamento);
        $entity->setUsuario(
            $this->getRepositoryUsuario()->find($idUsuario)
        );
        $entity->setValores(
            $this->getRepositoryValor()->find($idValor)
        );

        $this->_validaPagamento($entity);
        $this->_checarQuantidadeIngressoPagamento($entity);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        $entity->setUsuario(null);

        return $entity;
    }

    private function _tipoCartao($tipo) {
        switch($tipo) {
            case 'M': return 'mastercard'; break;
            case 'V': return 'visa'; break;
            case 'E': return 'elo'; break;
            default: return 'mastercard';
        }
    }

    public function realizarPagamento($pagamento) {
        $arrPagamentos = $pagamento;

       // $arrTransactions = array();
        if(count($arrPagamentos)) {
           // $total = 0;
            foreach($arrPagamentos as $pag) {
                /** @var Pagamento $pagamento */
                $pagamento = $this->getRepositoryPagamento()->find($pag);

                if($pagamento) {
                    $pagamento->setDataPagamento(new \DateTime());

                    $this->entityManager->persist($pagamento);

                } else {
                    throw new ValidException('Identificador de pagamento inválido');
                }
            }

            $this->entityManager->flush();


            /* $arrTransactions[] =  array(
                 'amount' => array(
                     'total' => sprintf("%0.2f",str_replace('.', ',', $total)),
                     'currency' => 'BRL'
                 ),
                 'description' => 'Compra evento Priority Pass'
             );*/

            return true;

        } else {
            throw new ValidException('Não foi informado pagamentos');
        }

       /* $apiContext = new ApiContext(
            new OAuthTokenCredential(
                'EGRy3TfXhasyamnJPHpeHVgm2TdojfR35sTUUDicyklIAIg-Ws4Cmmboo4fYaQt1a0NrpVEJp3eOBnTi',
                'EHS0szRnteFeI-AZUFJFyQeJ_DjsMC0sIMfolqzspEH_qrzmKpXsHGp7nFVUd7iuR9nI6Mz53DUevoPowa7ZNdnXnkeEFH'
            )
        );

        $key = ($apiContext->getCredential()->getAccessToken(array(
            "mode" => "sandbox"
        )));

        $arrSale = array(
            'intent' => 'sale',
            'payer' => array(
                'payment_method' => 'credit_card',
                'funding_instruments' => array(
                    0 => array(
                        'credit_card' => array(
                            'number' => $cartao->getNumero(),
                            'type' => $this->_tipoCartao($cartao->getTipo()),
                            'expire_month' => $cartao->getMes(),
                            'expire_year' => $cartao->getAno(),
                            'cvv2' => $cartao->getCodigo(),
                            'first_name' => $nome[0],
                            'last_name' => $nome[1]
                        )
                    )
                )
            ),

            'transactions' => $arrTransactions
        );

        $data = json_encode($arrSale);

        $client = $this->restClient->post(
            FinanceiroService::URL_PAYPAL_PAYMENT,
            $data,
            array(
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer {$key}",
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data)
                ),
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $data

            )
        );

        if($client->isOk()) {
            return $this->_executarPagamento($client->getContent(), $key);
        }

        return $client->getContent();*/
    }

    private function _executarPagamento($data, $keycode) {
        $data = \GuzzleHttp\json_decode($data);

        $post =  json_encode(array('payer_id' => $data->id));
        $client = $this->restClient->post(
            FinanceiroService::URL_PAYPAL_EXECUTE  . $data->id . '/execute',
            $post,
            array(
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer {$keycode}",
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($post)
                ),
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $data

            )
        );

        return ($client->getContent());
    }

    public function checarPagamento($pagamento, $usuario) {
        $service = $this->getRepositoryEntrada();
        /** @var Pagamento $pagamento */
        $pagamento = $this->getRepositoryPagamento()->find($pagamento);
        /** @var Usuario $usuario */
        $usuario = $this->getRepositoryUsuario()->find($usuario);

        $entradas = $service->findBy(array('pagamento' => $pagamento));

        if(!($pagamento instanceof Pagamento)) {
            throw new ValidException('Pagamento não encontrado');
        }

        if(!($usuario instanceof Usuario)) {
            throw new ValidException('Usuário não encontrado');
        }

        if(count($entradas) < $pagamento->getQuantidade()) {
            $entrada = new Entrada();
            $entrada->setUsuarioBaixa($usuario);
            $entrada->setDataEntrada(new \DateTime());
            $entrada->setPagamento($pagamento);

            $this->entityManager->persist($entrada);
            $this->entityManager->flush();

            return $entrada;
        }

        throw new ValidException('Este ingresso já foi utilizado');
    }

    public function getRepositoryEntrada() {
        return $this->entityManager->getRepository('IngressoFinanceiroBundle:Entrada');
    }

    public function getRepositoryCartao() {
        return $this->entityManager->getRepository('IngressoFinanceiroBundle:Cartao');
    }

    public function getRepositoryPagamento() {
        return $this->entityManager->getRepository('IngressoFinanceiroBundle:Pagamento');
    }

    public function getRepositoryUsuario() {
        return $this->entityManager->getRepository('IngressoUsuarioBundle:Usuario');
    }

    public function getRepositoryValor() {
        return $this->entityManager->getRepository('IngressoFinanceiroBundle:Valor');
    }

    public function getRepositoryLote() {
        return $this->entityManager->getRepository('IngressoEventoBundle:Lote');
    }
}
