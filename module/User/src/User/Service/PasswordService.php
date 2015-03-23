<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-22 17:25:35
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-22 17:27:22
 */

namespace User\Service;

class PasswordService
{
    public function hash($password)
    {
        return hash('sha512', $password);
    }
}
