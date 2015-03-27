<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-22 20:34:41
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-27 16:30:47
 */

namespace User\Service;

use Zend\Authentication\Storage\Session;
use Zend\Session\Config\StandardConfig;

class AuthStorage extends Session
{
    public function setRememberMe($rememberMe = 0, $time = 1209600)
    {
        $this->session->getManager()->rememberMe($time);
        // if ($rememberMe == 1) {
            // $this->session->getManager()->rememberMe($time);
        // } else {
        //     $this->session->getManager()->rememberMe(900);
        // }
    }

    public function forgetMe()
    {
        $this->session->getManager()->forgetMe();
    }
}
