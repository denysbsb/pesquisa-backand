<?php

namespace QuizBundle\Controller;

use Doctrine\Common\Util\Debug;
use Ingresso\CoreBundle\Exceptions\ValidException;
use QuizBundle\Entity\OpcoesQuestao;
use QuizBundle\Entity\Participante;
use QuizBundle\Entity\Pesquisador;
use QuizBundle\Entity\Questao;
use QuizBundle\Entity\Quiz;
use QuizBundle\Services\QuizService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\Annotations\Get;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Util\Codes;


class QuizController extends  FOSRestController implements ClassResourceInterface
{
  /**
   *
   * Get Route annotation.
   * @Get("/quiz/{id}")
   *
   * @ApiDoc(
   *  resource=true,
   *  description="Serviço responsável - recuperar por identificador",
   *
   *  parameters={
   *      {"name"="id", "dataType"="integer", "required"=true, "description"="Identificador de domínio"}
   * }
   *
   * )
   */
  public function getAction($id)
  {
    try {
      $result = $this->getService()
        ->getRepository()
        ->find($id);
      
      return array(
        'status'   => 'success',
        'data'  => $result
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
   * Get Route annotation.
   * @Get("/quiz")
   *
   * @ApiDoc(
   *  resource=true,
   *  description="Serviço responsável - recuperar todos registros do domínio",
   *
   *  parameters={
   * }
   *
   * )
   */
  public function allAction()
  {
    try {
      $result = $this->getService()
        ->getRepository()
        ->findAll();
  
      return array(
        'status'   => 'success',
        'data'  => $result
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
   * Post Route annotation.
   * @Post("/quiz")
   *
   * @ApiDoc(
   *  resource=true,
   *  description="Serviço responsável - salvar pesquisa",
   *
   *  parameters={
   * }
   *
   * )
   */
  public function postAction(Request $request)
  {
    try {
      $pesquisador = $request->get('pesquisador');
      $participante = $request->get('participante');
      $selecoes = $request->get('selecoes');
      $inicio = $request->get('inicio');
      $latitude = $request->get('latitude');
      $longitude = $request->get('longitude');
      $endereco = $request->get('$endereco');
  
      if (empty($pesquisador) ||
        empty($participante) ||
        empty($selecoes) ||
        empty($inicio) ||
        empty($latitude) ||
        empty($longitude)) {
        throw new ValidException('Requisição inválida');
      }
  
      $pesquisador = json_decode($pesquisador, true);
      $participante = json_decode($participante, true);
      $selecoes = json_decode($selecoes, true);
      $inicio = json_decode($inicio, true);
      $latitude = json_decode($latitude, true);
      $longitude = json_decode($longitude, true);
      $endereco = json_decode($endereco, true);
      
      $this->getService()->save(
        new Participante($participante),
        new Pesquisador($pesquisador),
        $selecoes,
        $inicio,
        $latitude['__zone_symbol__value'],
        $longitude['__zone_symbol__value'],
        $endereco['__zone_symbol__value']
      );
      
      return array(
        'status'   => 'success'
      );
      
    } catch (ValidException $ex) {
      return array(
        'status'   => 'error',
        'messages' => $ex->getMessage()
      );
    }
  }
  
  /**
   * @return QuizService
   */
  protected function getService() {
    return $this->get('quiz.service');
  }
}
