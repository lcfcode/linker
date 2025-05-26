<?php

namespace app\api\service;

use Swap\Core\Service;
use app\api\dao\JsonTabDao;

class JsonTabService extends Service
{
    private $jsonTabDao = null;

    /**
     * @return JsonTabDao
     */
    public function dao()
    {
        if ($this->jsonTabDao === null) {
            $this->jsonTabDao = new JsonTabDao($this->app);
        }
        return $this->jsonTabDao;
    }
}