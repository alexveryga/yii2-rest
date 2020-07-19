<?php

declare(strict_types=1);

namespace src\modules\api\controllers;

use src\modules\api\models\Article;
use src\modules\api\service\crud\CrudArticleService;
use yii\base\Module;
use yii\web\Request;
use yii\web\Response;

/**
 * Class ArticleController
 */
class ArticleController extends BaseController
{
    /**
     * @var Article
     */
    public $modelClass = Article::class;

    /**
     * ArticleController constructor.
     *
     * @param string             $id
     * @param Module             $module
     * @param CrudArticleService $crudService
     * @param Request            $request
     * @param Response           $response
     * @param array              $config
     */
    public function __construct(
        string $id,
        Module $module,
        CrudArticleService $crudService,
        Request $request,
        Response $response,
        array $config = []
    ) {
        $this->createScenario = Article::SCENARIO_CREATE;
        $this->updateScenario = Article::SCENARIO_UPDATE;

        parent::__construct($id, $module, $request, $response, $crudService, $config);
    }
}
