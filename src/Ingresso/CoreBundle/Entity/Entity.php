<?php
/**
 * Created by IntelliJ IDEA.
 * User: marioeugenio
 * Date: 7/20/16
 * Time: 9:07 PM
 */

namespace Ingresso\CoreBundle\Entity;

use Doctrine\Common\Util\Debug;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Validator\Constraints\Date;

abstract class Entity
{

    public function __construct(array $data = null)
    {
        $this->setData($data);
    }

    public  function setData($data)
    {
        foreach ((array) $data as $key => $value) {
            if (substr(ucfirst ($key), 0, 4) != 'List') {
                $set = "set" . ucfirst ($key);

                if (method_exists($this, $set)) {
                    if (strtolower(substr($key, 0,4)) == 'data'
                        || strtolower(substr($key, 0,2)) == 'dt') {
                        $value = new \DateTime($value);
                    }

                    $this->$set($value);
                }
            }
        }
    }

    /**
     * Converte entidade para json
     *
     * @return json
     */
    public function toJson()
    {
        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('message' => new
            JsonEncoder()));

        return $serializer->serialize($this, 'json');
    }

    /**
     * Converte entidade para array
     *
     * @return array
     */
    public function toArray()
    {
        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('message' => new
            JsonEncoder()));

        return json_decode($serializer->serialize($this, 'json'), true);
    }
} 