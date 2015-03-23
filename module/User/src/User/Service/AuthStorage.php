<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-22 20:34:41
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-22 22:40:30
 */

namespace User\Service;

use Zend\Authentication\Storage\Session;

class AuthStorage extends Session
{
    public function setRememberMe($rememberMe = 0, $time = 120960)
    {
        if ($rememberMe == 1) {
            $this->session->getManager()->rememberMe($time);
        }
    }

    public function forgetMe()
    {
        $this->session->getManager()->forgetMe();
    }
}
