<?php

namespace app\common\model\mysql\v2;

use think\Model;

class AdminUser extends Model
{
    protected $table = 'z_admin_user';

    public function updateLoginTimeAndIp($data, $key)
    {
        return $this->allowField(['last_login_ip', 'last_login_time'])->where('username', $key)->save($data);
    }


    public function findByUserName($data)
    {
        return $this->where('username', $data)->find();
    }

    public function add($data)
    {
        if (!is_array($data)) {
            return $this->show(
                config("status.error"),
                config("message.key_fault"),
                NULL,
            );
        }
        $this->save($data);
    }
}
