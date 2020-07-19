<?php

declare(strict_types=1);

namespace src\modules\api\models;

use yii\db\ActiveRecord;

/**
 * Class BaseModel
 */
class BaseModel extends ActiveRecord
{
    public const STATUS_NEW     = 'new';
    public const STATUS_DELETED = 'deleted';

    public const SCENARIO_CREATE     = 'SCENARIO_CREATE';
    public const SCENARIO_UPDATE     = 'SCENARIO_UPDATE';
    public const SQL_DATETIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @return array|string[]
     */
    public function fields()
    {
        return $this->attributes();
    }
}
