<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-21 14:22:23
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-26 13:29:35
 */

namespace User\Service\Invokable;

use Application\Utils\Service\AbstractService;
use Zend\Db\Sql\Predicate\Literal;

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

    public function getUserSettings($userId)
    {
        $join = array(
            array(
                'table_name' => 'Setting',
                'join_condition' => new Literal("UserSetting.SettingId = Setting.SettingId AND Setting.Status <> 'Deleted'"),
                'columns' => array('SettingName'),
            ),
        );

        $where = array(
            'UserId' => $userId,
            new Literal("UserSetting.Status <> 'Deleted'")
        );

        return $this->getTable('UserSetting')->select()->join($join)->where($where)->fetchAll()->toArray();
    }

    public function getUserResources()
    {

    }
}
