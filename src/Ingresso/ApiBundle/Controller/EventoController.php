<?php

namespace Ingresso\ApiBundle\Controller;

use Doctrine\Common\Util\Debug;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Util\Codes;
use Ingresso\UsuarioBundle\Services\UsuarioService;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Ingresso\CoreBundle\Exceptions\ValidException;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

class EventoController extends FOSRestController implements ClassResourceInterface {
	/**
	 *
	 * GET Route annotation.
	 * @Get("/evento")
	 *
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Serviço responsável - Retorna todos eventos",
	 *
	 *  parameters={}
	 * )
	 */
	public function cgetAction() {
		$entities = $this->getService()->getRepositoryEvento()->findAll();

		return array(
			'status'   => 'ok',
			'messages' => null,
			'result'   => $entities,
		);
	}

	/**
	 *
	 * GET Route annotation.
	 * @Get("/evento/{id}")
	 *
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Serviço responsável - Retorna evento por ID",
	 *
	 *  parameters={
	 *      {"name"="id", "dataType"="integer", "required"=true, "description"="identificador de evento"}
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
	 * POST Route annotation.
	 * @Post("/evento/solicitar/cartao")
	 *
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Serviço responsável - Gera solicitação de cartão",
	 *
	 *  parameters={
	 *      {"name"="idUsuario", "dataType"="integer", "required"=true, "description"="Identificador de usuário"}
	 * }
	 * )
	 */
	public function postSolicitarCartaoAction(Request $request) {
		try {
			$body = $request->getContent();

			if (empty($body)) {
				throw new ValidException('Requisição inválida');
			}

			$body = json_decode($body, true);

			if (count($body) == 0) {
				throw new ValidException('Não há registros a serem sincronizados');
			}

			$evento = $this->getService()->enviarSolicitacao($body['idUsuario']);

			return array(
				'status'   => 'ok',
				'messages' => 'Solicitação gerada com sucesso',
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
	 * @Post("/evento/salvar")
	 *
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Serviço responsável - Cria e altera usuario ingresso",
	 *
	 *  parameters={
	 *      {"name"="nome", "dataType"="string", "required"=true, "description"="nome do evento"},
	 *      {"name"="imagem", "dataType"="string", "required"=true, "description"="imagem do evento"},
	 *      {"name"="dataEvento", "dataType"="string", "required"=true, "description"="data do evento"},
	 *      {"name"="local", "dataType"="string", "required"=true, "description"="local do evento"},
	 *      {"name"="informacao", "dataType"="string", "required"=false, "description"="Informação do evento"},
	 *      {"name"="uf", "dataType"="string", "required"=true, "description"="identificador de unidade federativa"},
     *      {"name"="stBanner", "dataType"="boolean", "required"=true, "description"="Setar visualizacao Banner"}
     * }
	 * )
	 */
	public function postSalvarEventoAction(Request $request) {
		try {
			$body = $request->getContent();

			if (empty($body)) {
				throw new ValidException('Requisição inválida');
			}

			$body = json_decode($body, true);

			if (count($body) == 0) {
				throw new ValidException('Não há registros a serem sincronizados');
			}

			$evento = $this->getService()->salvarEvento($body);

			return array(
				'status'   => 'ok',
				'messages' => 'Evento salvo com sucesso',
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
     * @Post("/beneficio/salvar")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Serviço responsável - Cria e altera beneficio ingresso",
     *
     *  parameters={
     *      {"name"="titulo", "dataType"="string", "required"=true, "description"="titulo do beneficio"},
     *      {"name"="descricao", "dataType"="string", "required"=true, "description"="descricao do beneficio"},
     *      {"name"="imagem", "dataType"="string", "required"=true, "description"="imagem do beneficio"},
     *      {"name"="ativo", "dataType"="boolean", "required"=true, "description"="ativar beneficio"}
     * }
     * )
     */
    public function postSalvarBeneficioAction(Request $request) {
        try {
            $body = $request->getContent();

            if (empty($body)) {
                throw new ValidException('Requisição inválida');
            }

            $body = json_decode($body, true);

            if (count($body) == 0) {
                throw new ValidException('Não há registros a serem sincronizados');
            }

            $evento = $this->getService()->salvarBeneficio($body);

            return array(
                'status'   => 'ok',
                'messages' => 'Beneficio salvo com sucesso',
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
     * @Get("/beneficio")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Serviço responsável - Retorna todos benefícios ativos",
     *
     * )
     */
    public function getBeneficioAction() {
        $service = $this->getService();
        $result  = $service->getBeneficio();

        return array(
            'status'   => 'ok',
            'messages' => null,
            'result'   => $result,
        );
    }

	/**
	 *
	 * GET Route annotation.
	 * @Get("/evento/estado/{uf}")
	 *
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Serviço responsável - Retorna todos eventos da UF selecionada",
	 *
	 *  parameters={
	 *      {"name"="uf", "dataType"="string", "required"=true, "description"="identificador de Unidade Federativa"}
	 * }
	 *
	 * )
	 */
	public function getEventoPorUFAction($uf) {
		$service = $this->getService();
		$result  = $service->pesquisarEventoPorUF($uf);

		return array(
			'status'   => 'ok',
			'messages' => null,
			'result'   => $result,
		);
	}

    /**
     *
     * GET Route annotation.
     * @Get("/evento/banner/estado/{uf}")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Serviço responsável - Retorna todos eventos com banner ativo da UF selecionada",
     *
     *  parameters={
     *      {"name"="uf", "dataType"="string", "required"=true, "description"="identificador de Unidade Federativa"}
     * }
     *
     * )
     */
    public function getEventoBannerPorUFAction($uf) {
        $service = $this->getService();
        $result  = $service->pesquisarEventoBanner($uf);

        return array(
            'status'   => 'ok',
            'messages' => null,
            'result'   => $result,
        );
    }

	/**
	 *
	 * GET Route annotation.
	 * @Get("/evento/filtrar/{uf}/{data}")
	 *
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Serviço responsável - Retorna todos eventos da UF e data",
	 *
	 *  parameters={
	 *      {"name"="uf", "dataType"="string", "required"=true, "description"="identificador de Unidade Federativa"},
	 *      {"name"="data", "dataType"="string", "required"=true, "description"="Data do evento"}
	 * }
	 *
	 * )
	 */
	public function getEventoAction($uf, $data) {
		try {
			$service = $this->getService();
			$result  = $service->pesquisarEvento($uf, $data);

			return array(
				'status'   => 'ok',
				'messages' => null,
				'result'   => $result,
			);

		} catch (ValidException $ex) {
			return array(
				'status'   => 'fail',
				'messages' => $ex->getMessage()
			);
		}
	}

	/**
	 *
	 * GET Route annotation.
	 * @Get("/evento/usuario/{id}")
	 *
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Serviço responsável - Retorna todos eventos da UF selecionada",
	 *
	 *  parameters={
	 *      {"name"="uf", "dataType"="string", "required"=true, "description"="identificador de Unidade Federativa"}
	 * }
	 *
	 * )
	 */
	public function getEventoPorUsuarioAction($id) {
		$service = $this->getService();
		$result  = $service->pesquisarEventoPorUsuario($id);

		return array(
			'status'   => 'ok',
			'messages' => null,
			'result'   => $result,
		);
	}

    /**
     *
     * POST Route annotation.
     * @Post("/beneficio/inativar")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Serviço responsável - Inativar beneficio",
     *
     *  parameters={
     *      {"name"="idBeneficio", "dataType"="integer", "required"=true, "description"="Identificador de beneficio"}
     * }
     *
     * )
     */
    public function postBeneficioInativarAction(Request $request) {
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
            $result  = $service->removerBeneficio($body['idBeneficio']);

            return array(
                'status'   => 'ok',
                'messages' => 'Benefício inativado com sucesso',
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
	 * @Post("/evento/inativar")
	 *
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Serviço responsável - Inativar evento",
	 *
	 *  parameters={
	 *      {"name"="idEvento", "dataType"="integer", "required"=true, "description"="Identificador do lote"}
	 * }
	 *
	 * )
	 */
	public function postEventoInativarAction(Request $request) {
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
			$result  = $service->inativarEvento($body['idEvento']);

			return array(
				'status'   => 'ok',
				'messages' => 'Lote inativado com sucesso',
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
	 * @Post("/evento/lote/inativar")
	 *
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Serviço responsável - Inativar lote evento",
	 *
	 *  parameters={
	 *      {"name"="idLote", "dataType"="integer", "required"=true, "description"="Identificador do lote"}
	 * }
	 *
	 * )
	 */
	public function postEventoLoteInativarAction(Request $request) {
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
			$result  = $service->inativarLote($body['idLote']);

			return array(
				'status'   => 'ok',
				'messages' => 'Lote inativado com sucesso',
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
	 * @Post("/evento/lote/salvar")
	 *
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Serviço responsável - Criar lote para evento",
	 *
	 *  parameters={
	 *      {"name"="numero", "dataType"="string", "required"=true, "description"="Número do lote"},
	 *      {"name"="idEvento", "dataType"="integer", "required"=true, "description"="Identificador do lote"},
	 *      {"name"="dataInicio", "dataType"="string", "required"=true, "description"="Data início do lote"},
	 *      {"name"="dataFim", "dataType"="string", "required"=true, "description"="Data fim do lote"},
	 *      {"name"="descricao", "dataType"="string", "required"=true, "description"="Descrição do lote"},
	 *      {"name"="quantidade", "dataType"="integer", "required"=true, "description"="Quantidade de ingressos do lote"}
	 * }
	 *
	 * )
	 */
	public function postEventoLoteSalvarAction(Request $request) {
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
			$result  = $service->criarLote($body['idEvento'], $body);

			return array(
				'status'   => 'ok',
				'messages' => 'Lote salvo com sucesso',
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
	 * @Post("/evento/lote/valor/salvar")
	 *
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Serviço responsável - Criar valores para lote",
	 *
	 *  parameters={
	 *      {"name"="idLote", "dataType"="integer", "required"=true, "description"="Identificador do lote"},
	 *      {"name"="valor", "dataType"="string", "required"=true, "description"="valor do item"},
	 *      {"name"="taxa", "dataType"="string", "required"=true, "description"="taxa do item"},
	 *      {"name"="descricao", "dataType"="string", "required"=true, "description"="Descrição do item"}
	 * }
	 *
	 * )
	 */
	public function postEventoLoteValoresSalvarAction(Request $request) {
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
			$result  = $service->criarValorLote($body['idLote'], $body);

			return array(
				'status'   => 'ok',
				'messages' => 'Valor salvo com sucesso',
			);

		} catch (ValidException $ex) {
			return array(
				'status'   => 'error',
				'messages' => $ex->getMessage()
			);
		}
	}
	/**
	 * @return EventoService
	 */
	protected function getService() {
		return $this->get('ingresso_evento.service');
	}

	protected function getEntity($id) {
		$entity = $this->getService()->getRepositoryEvento()->find($id);
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find organisation entity');
		}
		return $entity;
	}
}
