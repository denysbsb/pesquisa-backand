<?php

namespace Ingresso\ApiBundle\Controller;

use Doctrine\Common\Util\Debug;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Util\Codes;
use Ingresso\CoreBundle\Exceptions\ValidException;
use Ingresso\UsuarioBundle\Entity\Usuario;
use Ingresso\UsuarioBundle\Services\UsuarioService;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

class UsuarioController extends FOSRestController implements ClassResourceInterface
{
    /**
     *
     * GET Route annotation.
     * @Get("/usuario")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Serviço responsável - Retorna todos usuarios",
     *
     *  parameters={}
     * )
     */
    public function cgetAction() {
        $entities = $this->getService()->getRepositoryUsuario()->findAll();

        return array(
            'status'   => 'ok',
            'messages' => null,
            'result'   => $entities,
        );
    }

    /**
     *
     * GET Route annotation.
     * @Get("/usuario/email/{email}")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Serviço responsável - Retorna todos usuarios por email",
     *
     *  parameters={}
     * )
     */
    public function getUsuarioPorEmailAction($email) {
        $entities = $this->getService()->pesquisarUsuarioPorEmail($email);

        return array(
            'status'   => 'ok',
            'messages' => null,
            'result'   => [$entities],
        );
    }

    /**
     *
     * GET Route annotation.
     * @Get("/usuario/cpf/{cpf}")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Serviço responsável - Retorna todos usuarios por cpf",
     *
     *  parameters={}
     * )
     */
    public function getUsuarioPorCPFAction($cpf) {
        $entities = $this->getService()->pesquisarUsuarioPorCPF($cpf);

        return array(
            'status'   => 'ok',
            'messages' => null,
            'result'   => $entities,
        );
    }

    /**
     *
     * GET Route annotation.
     * @Get("/usuario/{id}")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Serviço responsável - Retorna usuario por ID",
     *
     *  parameters={
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="identificador de usuario"}
     * }
     * )
     */
    public function getAction($id) {
        /** @var Usuario $entity */
        $entity = $this->getEntity($id);
        $entity->addCartoes(null);
        $entity->addPagamentos(null);

        return array(
            'status'   => 'ok',
            'messages' => null,
            'result'   => $entity,
        );
    }

    /**
     *
     * POST Route annotation.
     * @Post("/usuario/auth")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Serviço responsável - Retorna usuario por ID",
     *
     *  parameters={
     *      {"name"="login", "dataType"="string", "required"=true, "description"="identificador de usuario"},
     *      {"name"="senha", "dataType"="string", "required"=true, "description"="senha do usuario"}
     * }
     * )
     */
    public function postAutenticarAction(Request $request) {
        try {
            $login = $request->get('login');
            $senha = $request->get('senha');

            $usuario = $this->getService()->auth($login, $senha);

            return array(
                'status'   => 'ok',
                'messages' => null,
                'result'   => $usuario,
            );
        } catch (ValidException $ex) {
            return array(
                'status'   => 'error',
                'messages' => $ex->getMessage(),
            );
        }
    }

    /**
     *
     * POST Route annotation.
     * @Post("/usuario/codigo/checar")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Serviço responsável - Checar código identificador",
     *
     *  parameters={
     *      {"name"="idUsuario", "dataType"="integer", "required"=true, "description"="identificador de usuario"},
     *      {"name"="codigo", "dataType"="string", "required"=true, "description"="código de geração do cadastro"}
     * }
     * )
     */
    public function postCodigoChecarAction(Request $request) {
        try {
            $idUsuario = $request->get('idUsuario');
            $codigo = $request->get('codigo');

            $result = $this->getService()->checarCodigo($idUsuario, $codigo);

            if($result) {
                return array(
                    'status'   => 'ok',
                    'messages' => 'Código validado com sucesso'
                );
            }

            return array(
                'status'   => 'fail',
                'messages' => 'Código inválido'
            );
        } catch (ValidException $ex) {
            return array(
                'status'   => 'error',
                'messages' => 'Ocorreu um problema, tente mais tarde',
            );
        }
    }

    /**
     *
     * POST Route annotation.
     * @Post("/usuario/codigo/reenviar")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Serviço responsável - Reenviar código identificador",
     *
     *  parameters={
     *      {"name"="idUsuario", "dataType"="integer", "required"=true, "description"="identificador de usuario"}
     * }
     * )
     */
    public function postCodigoReenviarAction(Request $request) {
        try {
            $idUsuario = $request->get('idUsuario');

            $result = $this->getService()->reenviarCodigo($idUsuario);

            if($result) {
                return array(
                    'status'   => 'ok',
                    'messages' => 'Código reenviado com sucesso'
                );
            }
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
     * @Post("/usuario/senha/alterar")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Serviço responsável - Alterar senha usuário",
     *
     *  parameters={
     *      {"name"="email", "dataType"="string", "required"=true, "description"="e-mail do usuário"}
     * }
     * )
     */
    public function postAlterarSenhaAction(Request $request) {
        try {
            $email = strtolower($request->get('email'));

            $result = $this->getService()->recuperarCodigo($email);

            if($result) {
                return array(
                    'status'   => 'ok',
                    'messages' => "Senha alterada com sucesso e enviada para {$email}"
                );
            }
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
     * @Post("/usuario/salvar")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Serviço responsável - Cadastrar Usuário",
     *
     *  parameters={
     *      {"name"="nome", "dataType"="string", "required"=true, "description"="Nome do usuario"},
     *      {"name"="email", "dataType"="string", "required"=true, "description"="e-mail do usuario (xxxxx@gmail.com)"},
     *      {"name"="senha", "dataType"="string", "required"=true, "description"="senha do usuario"},
     *      {"name"="cpf", "dataType"="string", "required"=false, "description"="cpf do usuario (11122233344)"},
     *      {"name"="dataNascimento", "dataType"="string", "required"=false, "description"="data nascimento do usuario (2016-01-01)"},
     *      {"name"="sexo", "dataType"="string", "required"=false, "description"="sexo do usuario (M=masculino, F=Feminino)"},
     *      {"name"="telefone", "dataType"="integer", "required"=false, "description"="telefone do usuario (61999998888)"},
     *      {"name"="uf", "dataType"="string", "required"=false, "description"="uf de endereço do usuario (DF)"},
     *      {"name"="municipio", "dataType"="string", "required"=false, "description"="municipio de endereço do usuario"},
     *      {"name"="cep", "dataType"="integer", "required"=false, "description"="cep de endereço do usuario (70000000)"},
     *      {"name"="endereco", "dataType"="string", "required"=false, "description"="endereço do usuario"},
     *      {"name"="numero", "dataType"="integer", "required"=false, "description"="número de endereço do usuario"},
     *      {"name"="complemento", "dataType"="string", "required"=false, "description"="complemento de endereço do usuario"},
     *      {"name"="bairro", "dataType"="string", "required"=false, "description"="bairro de endereço do usuario"},
     *      {"name"="facebook", "dataType"="integer", "required"=false, "description"="Identificador facebook"}
     * }
     * )
     */
    public function postUsuarioSalvarAction(Request $request) {
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
            $usuario = $service->salvarUsuario($body);

            return array(
                'status'   => 'ok',
                'messages' => 'Usuário salvo com sucesso',
                'result' => $usuario
            );
        } catch (ValidException $ex) {
            return array(
                'status'   => 'error',
                'messages' => $ex->getMessage(),
            );
        }
    }

    /**
     * @return UsuarioService
     */
    protected function getService() {
        return $this->get('ingresso_usuario.service');
    }

    protected function getEntity($id) {
        $entity = $this->getService()->getRepositoryUsuario()->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find organisation entity');
        }
        return $entity;
    }
}
