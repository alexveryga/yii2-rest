<?php

declare(strict_types=1);

namespace src\modules\api\controllers\traits;

use src\modules\api\models\BaseModel;

/**
 * Trait ValidationTrait.
 */
trait ValidationTrait
{
    /**
     * Validate request parameters by model attributes.
     *
     * @param BaseModel   $modelObject
     * @param string|null $scenario
     *
     * @return bool
     */
    public function validateRequest(BaseModel $modelObject, string $scenario = null)
    {
        if ($scenario) {
            $modelObject->scenario = $scenario;
        }

        $modelObject->setAttributes($this->request->post());

        return $modelObject->validate();
    }
}
