<?php

namespace Lottery\Model;

use Think\Model;

/**
 * 
 * @
 */
class SchemeModel extends Model
{
    /**
     * 数据库表名
     * @author j
     */
    protected $tableName = 'lottery_scheme';

    /**
     * 自动验证规则
     * @author 
     */
    protected $_validate = array(

    );

    /**
     * 自动完成规则
     * @author 
     */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        // array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('status', '0', self::MODEL_INSERT),
    );
}
