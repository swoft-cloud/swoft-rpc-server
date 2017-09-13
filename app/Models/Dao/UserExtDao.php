<?php

namespace App\Models\Dao;

use Swoft\Bean\Annotation\Bean;

/**
 *
 * @Bean()
 * @uses      UserExtDao
 * @version   2017年04月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class UserExtDao
{
    public function getExtInfo()
    {
        return [
            'age' => 18,
            'desc' => 'hello',
            'address' => 'chengdu'
        ];
    }
}
