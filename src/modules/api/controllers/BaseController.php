<?php

declare(strict_types=1);

namespace src\modules\api\controllers;

use src\modules\api\controllers\traits\ValidationTrait;
use src\modules\api\models\BaseModel;
use src\modules\api\service\crud\CrudableInterface;
use yii\base\Module;
use yii\data\DataProviderInterface;
use yii\filters\Cors;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\rest\Serializer;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Response;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class BaseController.
 */
class BaseController extends ActiveController
{
    use ValidationTrait;

    public const CODE_OK = 200;
    public const CODE_CREATED = 201;
    public const CODE_NO_CONTENT = 204;
    public const CODE_UNPROCESSABLE_ENTITY = 422;
    public const CODE_NOT_FOUND = 404;

    /**
     * @var string
     */
    public $createScenario;

    /**
     * @var string
     */
    public $updateScenario;

    /**
     * @var Request
     */
    public $request;

    /**
     * @var Response
     */
    public $response;

    /**
     * @var CrudableInterface
     */
    public CrudableInterface $crudService;

    /**
     * @var string[]
     */
    public $serializer = [
        'class' => Serializer::class,
        'collectionEnvelope' => 'items',
    ];

    /**
     * BaseController constructor.
     *
     * @param string   $id
     * @param Module   $module
     * @param Request  $request
     * @param Response $response
     * @param CrudableInterface $crudService
     * @param array    $config
     */
    public function __construct(
        string $id,
        Module $module,
        Request $request,
        Response $response,
        CrudableInterface $crudService,
        array $config = []
    ) {
        $this->request     = $request;
        $this->response    = $response;
        $this->crudService = $crudService;

        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritDoc}
     */
    public function actions(): array
    {
        $actions = parent::actions();

        return ['options' => $actions['options']];
    }

    /**
     * {@inheritDoc}
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => $this->verbs(),
            ],
            'corsFilter' => [
                'class' => Cors::class,
                'cors'  => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => [
                        'GET',
                        'POST',
                        'PUT',
                        'PATCH',
                        'DELETE',
                        'HEAD',
                        'OPTIONS',
                    ],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Allow-Credentials' => false,
                    'Access-Control-Max-Age' => 3600,
                ]
            ],
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    /**
     * @return DataProviderInterface
     */
    public function actionIndex(): DataProviderInterface
    {
        return $this->crudService->findAllByParameters(
            $this->request->getBodyParams()
        );
    }

    /**
     * Fetch single instance.
     *
     * @param $id
     *
     * @return BaseModel
     *
     * @throws NotFoundHttpException
     */
    public function actionView($id): ?BaseModel
    {
        try {
            return $this->crudService->find((int)$id);
        } catch (\Exception $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
    }

    /**
     * Create instance action.
     *
     * @return BaseModel
     *
     * @throws BadRequestHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function actionCreate(): BaseModel
    {
        if ($this->validateRequest(new $this->modelClass, $this->createScenario)) {
            $transaction = \Yii::$app->db->beginTransaction();

            try {
                $this->response->setStatusCode(self::CODE_CREATED);
                $model = $this->crudService->create(
                    $this->request->getBodyParams(),
                    $this->createScenario
                );
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                $this->response->setStatusCode(self::CODE_UNPROCESSABLE_ENTITY);
                throw new UnprocessableEntityHttpException($e->getMessage());
            }
        } else {
            throw new BadRequestHttpException('Parameters missing');
        }

        return $model;
    }

    /**
     * Update instance action.
     *
     * @param $id
     *
     * @return BaseModel
     *
     * @throws BadRequestHttpException|NotFoundHttpException
     */
    public function actionUpdate($id): BaseModel
    {
        if ($this->validateRequest(new $this->modelClass, $this->updateScenario)) {
            $transaction = \Yii::$app->db->beginTransaction();

            try {
                $this->response->setStatusCode(self::CODE_OK);
                $model = $this->crudService->update((int)$id, $this->request->getBodyParams(), $this->updateScenario);
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                $this->response->setStatusCode(self::CODE_NOT_FOUND);
                throw new NotFoundHttpException($e->getMessage());
            }
        } else {
            throw new BadRequestHttpException('Parameters missing');
        }

        return $model;
    }

    /**
     * Delete instance action.
     *
     * @param $id
     *
     * @throws BadRequestHttpException
     */
    public function actionDelete($id): void
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $this->crudService->delete((int)$id, $this->updateScenario);
            $this->response->setStatusCode(self::CODE_NO_CONTENT);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new BadRequestHttpException('Parameters missing');
        }
    }
}
