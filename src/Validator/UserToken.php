<?php
/**
 * Created by PhpStorm.
 * User: qucaixian
 * Date: 2019/9/20
 * Time: 10:47
 */

namespace App\Validator;


use App\Rule\Mobile;
use App\Rule\Password;
use Symfony\Component\Validator\Constraints as Assert;

class UserToken extends Base
{
    protected function setFields ()
    {
        $this->fields = [
            'mobile'   => new Assert\Required([
                new Mobile()
            ]),
            'password' => new Assert\Required([
                new Password()
            ])
        ];
    }
}