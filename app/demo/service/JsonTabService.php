<?php
/**
 * @link https://gitee.com/lcfcode/linker
 * @link https://github.com/lcfcode/linker
 */
    
namespace app\demo\service;

use app\demo\dao\JsonTabDao;
use swap\core\Service;

class JsonTabService extends Service
{
    private $jsonTabDao = null;

    /**
     * @return JsonTabDao
     */
    public function dao()
    {
        if ($this->jsonTabDao === null) {
            $this->jsonTabDao = new JsonTabDao();
        }
        return $this->jsonTabDao;
    }
}