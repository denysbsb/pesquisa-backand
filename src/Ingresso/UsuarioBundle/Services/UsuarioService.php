<?php
/**
 * Created by IntelliJ IDEA.
 * User: marioeugenio
 * Date: 7/19/16
 * Time: 6:33 PM
 */

namespace Ingresso\UsuarioBundle\Services;

use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityManager;
use Ingresso\CoreBundle\Exceptions\ValidException;
use Ingresso\UsuarioBundle\Entity\Usuario;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class UsuarioService {
    /**
     * @var EntityManager
     */
    private $entityManager;

    private $mailer;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, $mailer)
    {
        $this->entityManager = $em;
        $this->mailer = $mailer;
    }

    private function _validaUsuario(Usuario $usuario) {
        if(!$usuario->getNome()) {
            throw new ValidException('Nome é obrigatório');
        }

        if(!$usuario->getEmail()) {
            throw new ValidException('E-mail é obrigatório');
        }

        if(!$usuario->getId()) {
            if($usuario->getFacebook()) {
                $usuario->setSenha(
                    $usuario->getFacebook()
                );
            }

            if(!$usuario->getSenha()) {
                throw new ValidException('Senha é obrigatório');
            }

        }
    }

    public function pesquisarUsuarioPorEmail($email) {
        return $this->getRepositoryUsuario()->findOneBy(
            array('email' => $email)
        );
    }

    private function _validaCPF($cpf = null) {

        // Verifica se um número foi informado
        if(empty($cpf)) {
            return false;
        }

        // Elimina possivel mascara
        $cpf = preg_replace('[^0-9]', '', $cpf);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);

        // Verifica se o numero de digitos informados é igual a 11
        if (strlen($cpf) != 11) {
            return false;
        }
        // Verifica se nenhuma das sequências invalidas abaixo
        // foi digitada. Caso afirmativo, retorna falso
        else if ($cpf == '00000000000' ||
            $cpf == '11111111111' ||
            $cpf == '22222222222' ||
            $cpf == '33333333333' ||
            $cpf == '44444444444' ||
            $cpf == '55555555555' ||
            $cpf == '66666666666' ||
            $cpf == '77777777777' ||
            $cpf == '88888888888' ||
            $cpf == '99999999999') {
            return false;
            // Calcula os digitos verificadores para verificar se o
            // CPF é válido
        } else {

            for ($t = 9; $t < 11; $t++) {

                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf{$c} != $d) {
                    return false;
                }
            }

            return true;
        }
    }

    private function _prepareString($str) {
        $arr = array('.','-','_',',',' ','*','#','/','\\','{','}','[',']','|','!','^','&','(',')');
        return str_replace($arr, '', trim(strtolower($this->_removerAcentos($str))));
    }

    private function _removerAcentos($string){
        return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
    }

    private function _checarCadastroUsuario(Usuario $usuario) {

        if($usuario->getCpf()) {
            // Aplicando regras para CPF
            $usuario->setCpf($this->_prepareString($usuario->getCpf()));
            if(!$this->_validaCPF($usuario->getCpf())) {
                throw new ValidException('CPF inválido');
            }
        }


        // Aplicando regras para e-mail
        $usuario->setEmail(strtolower(trim($this->_removerAcentos($usuario->getEmail()))));
        if (!filter_var($usuario->getEmail(), FILTER_VALIDATE_EMAIL)) {
            throw new ValidException('E-mail inválido');
        }

        // Aplicando regras para telefone
        $usuario->setTelefone($this->_prepareString($usuario->getTelefone()));

        // Aplicando regras para cep
        $usuario->setCep($this->_prepareString($usuario->getCep()));

        $usuario->setNome(trim(strtoupper($this->_removerAcentos($usuario->getNome()))));

        $usuario->setUf(strtoupper($this->_prepareString($usuario->getUf())));
        $usuario->setMunicipio(strtoupper($this->_removerAcentos($usuario->getMunicipio())));
        $usuario->setBairro(strtoupper($this->_removerAcentos($usuario->getBairro())));
        $usuario->setEndereco(strtoupper($this->_removerAcentos($usuario->getEndereco())));
        $usuario->setComplemento(strtoupper($this->_removerAcentos($usuario->getComplemento())));

        if(!$usuario->getId()) {
            // Aplicando regra para cpf existente
            if ($usuario->getCpf()) {
                $usuarioCheck1 = $this->getRepositoryUsuario()->findOneBy(array('cpf'=>$usuario->getCpf()));
                if($usuarioCheck1 instanceof Usuario) {
                    throw new ValidException('CPF já cadastrado');
                }
            }

            if ($usuario->getEmail()) {
                // Aplicando regra para cpf existente
                $usuarioCheck2 = $this->getRepositoryUsuario()->findOneBy(array('email'=>$usuario->getEmail()));
                if($usuarioCheck2 instanceof Usuario) {
                    throw new ValidException('E-mail já cadastrado');
                }
            }

        }

    }

    private function _gerarCodigo()
    {
        $uniqid = uniqid();
        $rand_start = rand(1,5);

        return substr($uniqid,$rand_start,8);
    }

    public function pesquisarUsuarioPorCPF($cpf) {
        /** @var Usuario $entity */
        $entity = $this->getRepositoryUsuario()->findOneBy(array('cpf' => $cpf));

        if(!$entity)
            return;

        return array(
            'id' => $entity->getId(),
            'nome' => $entity->getNome(),
            'email' => $entity->getEmail(),
            'cpf' => $entity->getCpf()
        );
    }

    public function checarCodigo($idUsuario, $codigo)
    {
        /** @var Usuario $entity */
        $entity = $this->getRepositoryUsuario()->find($idUsuario);

        if($entity) {
            if($entity->getCodigo() == $codigo) {
                $entity->setAceite(true);

                $this->entityManager->persist($entity);
                $this->entityManager->flush();

                return true;
            }

            return false;
        }

        throw new ValidException('Usuário não encontrado');
    }

    public function salvarUsuario($arrUsuario) {
        $code = $this->_gerarCodigo();
        $entity = new Usuario($arrUsuario);

        if($entity->getId()) {
            $entity = $this->getRepositoryUsuario()->find($entity->getId());
            $entity->setData($arrUsuario);
        }

        if(!$entity->getId()) {
            $entity->setCodigo($code);
            $entity->setSenha(
                md5($entity->getSenha())
            );

            $entity->setDataCadastro(new \DateTime());
        }

        $entity->setAceite(false);

        $this->_validaUsuario($entity);
        $this->_checarCadastroUsuario($entity);

        if(!$entity->getId()) {
            $this->_enviarEmail($entity);
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    public function reenviarCodigo($idUsuario) {
        $entity = $this->getRepositoryUsuario()->find($idUsuario);

        if($entity instanceof Usuario) {
            $this->_enviarEmail($entity);
            return true;
        }

        throw new ValidException('Usuário não encontrado');
    }

    public function recuperarCodigo($email) {
        $entity = $this->getRepositoryUsuario()->findOneBy(array('email' => $email));

        if($entity instanceof Usuario) {
            $this->_enviarRecuperarSenha($entity);
            return true;
        }

        throw new ValidException('Usuário não encontrado');
    }

    private function _enviarRecuperarSenha(Usuario $usuario) {
        $nome = $usuario->getNome();
        $codigo = $this->_gerarCodigo();
        $usuario->setSenha(md5($codigo));

        $this->entityManager->persist($usuario);
        $this->entityManager->flush();

        $message = \Swift_Message::newInstance()
            ->setSubject('Alterar Senha - Priority Pass')
            ->setFrom('mario.eugenio@gmail.com')
            ->setTo($usuario->getEmail())
            ->setBody(
                '<h3>Alterar Senha -  Priority Pass!</h3>' .
                "<p>Sr(a) {$nome}, sua senha foi atualizada com sucesso. Nova senha <strong>{$codigo}</strong></p>".
                '<br><br>'.
                '<img src="https://scontent.cdninstagram.com/t51.2885-19/s150x150/12530982_517255478435835_662932413_a.jpg">',
                'text/html'
            );

        $this->mailer->send($message);
    }

    private function _enviarEmail(Usuario $usuario) {
        $nome = $usuario->getNome();
        $codigo = $usuario->getCodigo();

        $message = \Swift_Message::newInstance()
            ->setSubject('Bem vindo ao Priority Pass')
            ->setFrom('mario.eugenio@gmail.com')
            ->setTo($usuario->getEmail())
            ->setBody(
                '<h3>Bem vindo ao Priority Pass!</h3>' .
                "<p>Sr(a) {$nome}, seu registro foi realizado com sucesso. Código de identificação <strong>{$codigo}</strong></p>".
                '<br><br>'.
                '<img src="https://scontent.cdninstagram.com/t51.2885-19/s150x150/12530982_517255478435835_662932413_a.jpg">',
                'text/html'
            );

        $this->mailer->send($message);
    }

    public function auth($login, $senha)
    {
        if((!$login) || (!$senha)) {
            throw new ValidException('Usuário e senha são obrigatórios');
        }

        // Aplicando regras para e-mail
        $login = (strtolower(trim($this->_removerAcentos($login))));
        if (!filter_var($login, FILTER_VALIDATE_EMAIL)) {
            throw new ValidException('E-mail inválido');
        }

        /** @var Usuario $usuario */
        $usuario = $this->getRepositoryUsuario()->findOneBy(
            array('email' => $login, 'senha' => md5($senha))
        );

        if ($usuario) {
            $usuario->addPagamentos(null);
            return $usuario;
        }

        throw new ValidException('Usuário ou senha inválidos');
    }

    public function getRepositoryUsuario() {
        return $this->entityManager->getRepository('IngressoUsuarioBundle:Usuario');
    }
} 