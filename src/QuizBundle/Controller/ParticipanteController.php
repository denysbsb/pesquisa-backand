<?php

namespace QuizBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Ingresso\CoreBundle\Exceptions\ValidException;
use QuizBundle\Services\ParticipanteService;
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



class ParticipanteController extends FOSRestController implements ClassResourceInterface
{
  
  /**
   *
   * Get Route annotation.
   * @Get("/participante/{id}")
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
   * @Get("/participante")
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
   * @return ParticipanteService
   */
  protected function getService() {
    return $this->get('participante.service');
  }
}
