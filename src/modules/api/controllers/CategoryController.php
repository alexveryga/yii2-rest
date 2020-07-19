<?php

declare(strict_types=1);

namespace src\modules\api\controllers;

use src\modules\api\models\Article;
use src\modules\api\models\Category;
use src\modules\api\service\crud\CrudCategoryService;
use yii\base\Module;
use yii\web\Request;
use yii\web\Response;

/**
 * Class CategoryController
 */
class CategoryController extends BaseController
{
    /**
     * @var Article
     */
    public $modelClass = Category::class;

    /**
     * ArticleController constructor.
     *
     * @param string              $id
     * @param Module              $module
     * @param CrudCategoryService $crudService
     * @param Request             $request
     * @param Response            $response
     * @param array               $config
     */
    public function __construct(
        string $id, Module $module,
        CrudCategoryService $crudService,
        Request $request,
        Response $response,
        array $config = []
    ) {
        $this->createScenario = Category::SCENARIO_CREATE;
        $this->updateScenario = Category::SCENARIO_UPDATE;

        parent::__construct($id, $module, $request, $response, $crudService, $config);
    }
}
