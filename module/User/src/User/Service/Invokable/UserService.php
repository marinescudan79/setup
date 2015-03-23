<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-21 14:22:23
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-22 17:28:56
 */

namespace User\Service\Invokable;

use Application\Utils\Service\AbstractService;

class UserService extends AbstractService
{
    public function listUsers($paginator = false, $showDeleted = false)
    {
        $join = array(
            array(
                'table_name' => 'ContactDetail',
                'join_condition' => 'User.ContactDetailId = ContactDetail.ContactDetailId',
                'columns' => array('*'),
            ),
            array(
                'table_name' => 'Role',
                'join_condition' => 'User.RoleId = Role.RoleId',
                'columns' => array('RoleName'),
            ),
        );

        $where = array(
            new \Zend\Db\Sql\Predicate\Expression("User.Status <> 'Deleted'")
        );


        return $this->getTable('User')->select()->where($where)->join($join)->fetchAll();
    }

    public function addUser($data)
    {
        // $password_clear          = $data['Password'];
        $data['Password']        = $this->getService('PasswordService')->hash($data['Password']);
        $data['ContactDetailId'] = $this->getTable('ContactDetail')->insert($data);
        $userId                  = $this->getTable('User')->insert($data);
        return $userId;
    }
}
