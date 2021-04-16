<?php

namespace Ingresso\ApiBundle\Controller;

use Doctrine\Common\Util\Debug;
use FOS\RestBundle\Controller\Annotations\Get;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Util\Codes;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Ingresso\CoreBundle\Exceptions\ValidException;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FinanceiroController extends FOSRestController implements ClassResourceInterface {
	/**
	 *
	 * POST Route annotation.
	 * @Post("/financeiro/valor/inativar")
	 *
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Serviço responsável - Inativar valor evento",
	 *
	 *  parameters={
	 *      {"name"="idValor", "dataType"="integer", "required"=true, "description"="Identificador do valor"}
	 * }
	 *
	 * )
	 */
	public function postValorInativarAction(Request $request) {
		try {

			$body = $request->getContent();

			if (empty($body)) {
				throw new ValidException('Requisição inválida');
			}

			$body = json_decode($body, true);

			if (count($body) == 0) {
				throw new ValidException('Não há registros a serem sincronizados');
			}

			$service = $this->getService();
			$result  = $service->inativarValor($body['idValor']);

			return array(
				'status'   => 'ok',
				'messages' => 'Valor inativado com sucesso',
			);

		} catch (ValidException $ex) {
			return array(
				'status'   => 'error',
				'messages' => $ex->getMessage()
			);
		}
	}

    /**
     *
     * POST Route annotation.
     * @Post("/pagamento/paypal/callback/{params}")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Serviço responsável - Efetivar pagamento Paypal",
     *
     *
     * )
     */
    public function realizarPagamentoWebAction($params) {
        try {

            $idPagamento = $params;

            var_dump($idPagamento);

            if (!isset($idPagamento)) {
                throw new ValidException('Não foi informado o pagamento');
            }

            $service = $this->getService();

            $idPagamento = explode(",", $idPagamento);
            $service->realizarPagamento($idPagamento);

            return array(
                'status'   => 'ok',
                'messages' => 'Pagamento efetuado com sucesso'
            );

        } catch (ValidException $ex) {
            return array(
                'status'   => 'error',
                'messages' => $ex->getMessage()
            );
        }
    }

	/**
	 *
	 * POST Route annotation.
	 * @Post("/pagamento/paypal")
	 *
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Serviço responsável - Efetivar pagamento Paypal",
	 *
	 *  parameters={
	 *      {"name"="idPagamento", "dataType"="integer", "required"=true, "description"="Identificador do pagamento"}
	 * }
	 *
	 * )
	 */
	public function realizarPagamentoAction(Request $request) {
		try {

			$body = $request->getContent();

			if (empty($body)) {
				throw new ValidException('Requisição inválida');
			}

			$body = json_decode($body, true);

			if (count($body) == 0) {
				throw new ValidException('Não há registros a serem sincronizados');
			}

			if (!isset($body['idPagamento'])) {
				throw new ValidException('Não foi informado o pagamento');
			}

			$service = $this->getService();
			$result  = $service->realizarPagamento($body['idPagamento']);

            return array(
                'status'   => 'ok',
                'messages' => 'Pagamento efetuado com sucesso'
            );

		} catch (ValidException $ex) {
			return array(
				'status'   => 'error',
				'messages' => $ex->getMessage()
			);
		}
	}

	/**
	 *
	 * POST Route annotation.
	 * @Post("/voucher/transferir")
	 *
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Serviço responsável - Inativar valor evento",
	 *
	 *  parameters={
	 *      {"name"="idUsuario", "dataType"="integer", "required"=true, "description"="Identificador do usuário"},
	 *      {"name"="idPagamento", "dataType"="integer", "required"=true, "description"="Identificador do pagamento"}
	 * }
	 *
	 * )
	 */
	public function postTransferirVoucherAction(Request $request) {
		try {

			$body = $request->getContent();

			if (empty($body)) {
				throw new ValidException('Requisição inválida');
			}

			$body = json_decode($body, true);

			if (count($body) == 0) {
				throw new ValidException('Não há registros a serem sincronizados');
			}

			$service = $this->getService();
			$result  = $service->transferirVoucher($body['idUsuario'], $body['idPagamento']);

			return array(
				'status'   => 'ok',
				'messages' => 'Voucher transferido com sucesso',
			);

		} catch (ValidException $ex) {
			return array(
				'status'   => 'error',
				'messages' => $ex->getMessage()
			);
		}
	}

	/**
	 *
	 * GET Route annotation.
	 * @Get("/pagamento")
	 *
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Serviço responsável - Retorna todos pagamentos",
	 *
	 *  parameters={}
	 * )
	 */
	public function cgetAction() {
		$entities = $this->getService()->getRepositoryPagamento()->findAll();

		return array(
			'status'   => 'ok',
			'messages' => null,
			'result'   => $entities,
		);
	}

	/**
	 *
	 * GET Route annotation.
	 * @Get("/pagamento/{id}")
	 *
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Serviço responsável - Retorna pagamento por ID",
	 *
	 *  parameters={
	 *      {"name"="id", "dataType"="integer", "required"=true, "description"="identificador de pagamento"}
	 * }
	 * )
	 */
	public function getAction($id) {
		$entity = $this->getEntity($id);
		return array(
			'status'   => 'ok',
			'messages' => null,
			'result'   => $entity,
		);
	}

	/**
	 *
	 * GET Route annotation.
	 * @Get("/voucher/{pagamento}")
	 *
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Serviço responsável - Gerar QRCODE por idPagamento",
	 *
	 *  parameters={
	 *      {"name"="pagamento", "dataType"="integer", "required"=true, "description"="identificador de pagamento"}
	 * }
	 * )
	 */
	public function getGerarQRCodeAction($pagamento) {
		$pagamento = $this->getEntity($pagamento);

		ob_end_clean();

		$output = $pagamento->getId()
		.'|'.$pagamento->getUsuario()->getId()
		.'|'.$pagamento->getValor();

		\QRcode::png($output, false, QR_ECLEVEL_L, 5);
	}

	/**
	 *
	 * GET Route annotation.
	 * @Get("/pagamento/quantidade/ingressos/{lote}")
	 *
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Serviço responsável - Retorna ingressos disponíveis por lote",
	 *
	 * )
	 */
	public function getPagamentoQuantidadeIngressosAction($lote) {
		$service = $this->getService();
		$result  = $service->checarQuantidadePagamento($lote);

		return array(
			'status'   => 'ok',
			'messages' => null,
			'result'   => $result,
		);
	}

	/**
	 *
	 * GET Route annotation.
	 * @Get("/cartao/usuario/{usuario}")
	 *
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Serviço responsável - Retorna cartões por usuário",
	 *
	 * )
	 */
	public function getListarCartaoPorUsuarioAction($usuario) {
		$service = $this->getService();
		$result  = $service->pesquisarCartaoPorUsuario($usuario);

		return array(
			'status'   => 'ok',
			'messages' => null,
			'result'   => $result,
		);
	}

	/**
	 *
	 * GET Route annotation.
	 * @Get("/cartao/checar/{pagamento}/{usuario}")
	 *
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Serviço responsável - Checar Pagamento",
	 *
	 * )
	 */
	public function getChecarPagamentoAction($pagamento, $usuario) {
		try {
			$service = $this->getService();
			$result  = $service->checarPagamento($pagamento, $usuario);

			return array(
				'status'   => 'ok',
				'messages' => 'Autorizado',
				'result'   => $result,
			);

		} catch (ValidException $ex) {
			return array(
				'status'   => 'error',
				'messages' => $ex->getMessage()
			);
		}
	}

	/**
	 *
	 * POST Route annotation.
	 * @Post("/cartao/salvar")
	 *
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Serviço responsável - Cadastrar cartão",
	 *
	 *  parameters={
	 *      {"name"="idUsuario", "dataType"="integer", "required"=true, "description"="Identificador Usuário"},
	 *      {"name"="tipo", "dataType"="integer", "required"=true, "description"="Identificador do cartão"},
	 *      {"name"="numero", "dataType"="integer", "required"=true, "description"="Número do cartão"},
	 *      {"name"="imagem", "dataType"="integer", "required"=true, "description"="Imagem digitalizada do cartão"},
	 *      {"name"="mes", "dataType"="integer", "required"=true, "description"="Mês de vencimento do cartão"},
	 *      {"name"="ano", "dataType"="integer", "required"=true, "description"="Ano de vencimento do cartão"},
	 *      {"name"="codigo", "dataType"="integer", "required"=true, "description"="Código de segurança do cartão"},
	 * }
	 *
	 * )
	 */
	public function postCartaoSalvarAction(Request $request) {
		try {

			$body = $request->getContent();

			if (empty($body)) {
				throw new ValidException('Requisição inválida');
			}

			$body = json_decode($body, true);

			if (count($body) == 0) {
				throw new ValidException('Não há registros a serem sincronizados');
			}

			if (!isset($body['idUsuario'])) {
				throw new ValidException('Usuário é obrigatório');
			}

			$service = $this->getService();
			$result  = $service->salvarCartao($body['idUsuario'], $body);

			return array(
				'status'   => 'ok',
				'messages' => 'Cartão cadastrado com sucesso',
			);

		} catch (ValidException $ex) {
			return array(
				'status'   => 'error',
				'messages' => $ex->getMessage()
			);
		}
	}

    /**
     *
     * POST Route annotation.
     * @Post("/pagamento/criar")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Serviço responsável - Criar pagamento sem efetivaçao do pagamento",
     *
     *  parameters={ }
     *
     * )
     */
    public function postCriarPagamentoAction(Request $request) {
        try {

            $body = $request->getContent();

            if (empty($body)) {
                throw new ValidException('Requisição inválida');
            }

            $body = json_decode($body);

            if (count($body) == 0) {
                throw new ValidException('Não há registros a serem sincronizados');
            }

            $service = $this->getService();

            $entity  = $service->criarPagamento($body->usuario, $body);

            return array(
                'status'   => 'ok',
                'messages' => 'Pagamento iniciado com sucesso',
                "result"   => $entity,
            );

        } catch (ValidException $ex) {
            return array(
                'status'   => 'error',
                'messages' => $ex->getMessage()
            );
        }
    }

	/**
	 *
	 * POST Route annotation.
	 * @Post("/pagamento/salvar")
	 *
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Serviço responsável - Realizar pagamento",
	 *
	 *  parameters={
	 *      {"name"="idValor", "dataType"="integer", "required"=true, "description"="Identificador do valor"},
	 *      {"name"="idUsuario", "dataType"="integer", "required"=true, "description"="Identificador Usuário"},
	 *      {"name"="quantidade", "dataType"="integer", "required"=true, "description"="Quantidade de ingressos comprado"},
	 *      {"name"="valor", "dataType"="integer", "required"=true, "description"="Valor do pagamento"},
	 *      {"name"="tipoPagamento", "dataType"="string", "required"=true, "description"="Tipo de Pagamento (V, M, C, D)"}
	 * }
	 *
	 * )
	 */
	public function postRealizarPagamentoAction(Request $request) {
		try {

			$body = $request->getContent();

			if (empty($body)) {
				throw new ValidException('Requisição inválida');
			}

			$body = json_decode($body, true);

			if (count($body) == 0) {
				throw new ValidException('Não há registros a serem sincronizados');
			}

			$service = $this->getService();
			$entity  = $service->salvarPagamento($body['idUsuario'], $body['idValor'], $body);

			return array(
				'status'   => 'ok',
				'messages' => 'Pagamento iniciado com sucesso',
				"result"   => $entity,
			);

		} catch (ValidException $ex) {    
			return array(
				'status'   => 'error',
				'messages' => $ex->getMessage()
			);
		}
	}

	/**
	 * @return FinanceiroService
	 */
	protected function getService() {
		return $this->get('ingresso_financeiro.service');
	}

	/**
	 * @param $id
	 * @return null|Pagamento
	 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 */
	protected function getEntity($id) {
		$entity = $this->getService()->getRepositoryPagamento()->find($id);
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find organisation entity');
		}
		return $entity;
	}
}
