<?php

namespace App\Http\Controllers\Api;

use App\Actions\Organization\GetAllOrganizationsAction;
use App\DTO\AreaParamsDTO;
use App\DTO\GeoParamsDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrganizationFilterRequest;
use App\Actions\Organization\GetOrganizationsByBuildingAction;
use App\Actions\Organization\GetOrganizationsByActivityAction;
use App\Actions\Organization\GetOrganizationsByActivityWithDescendantsAction;
use App\Actions\Organization\GetOrganizationsByRadiusAction;
use App\Actions\Organization\GetOrganizationsByAreaAction;
use App\Actions\Organization\SearchOrganizationsByNameAction;
use App\Http\Resources\OrganizationResource;
use App\Models\Activity;
use App\Models\Organization;
use App\Queries\OrganizationQuery;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(
 *     title="REST API Reference",
 *     version="1.0.0",
 *     description="API для справочника организаций, зданий и деятельности"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="apiKey",
 *     type="apiKey",
 *     in="header",
 *     name="X-API-Key"
 * )
 */
class OrganizationController extends Controller
{
    public function __construct(
        private GetAllOrganizationsAction $getAllOrganizationsAction,
        private GetOrganizationsByBuildingAction $getOrganizationsByBuildingAction,
        private GetOrganizationsByActivityAction $getOrganizationsByActivityAction,
        private GetOrganizationsByActivityWithDescendantsAction $getOrganizationsByActivityWithDescendantsAction,
        private GetOrganizationsByRadiusAction $getOrganizationsByRadiusAction,
        private GetOrganizationsByAreaAction $getOrganizationsByAreaAction,
        private SearchOrganizationsByNameAction $searchOrganizationsByNameAction
    ) {}
    /**
     * @OA\Get(
     *     path="/api/organizations/{organization}",
     *     summary="Получить информацию об организации",
     *     tags={"Organizations"},
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *
     *         description="ID организации",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="building_id", type="integer"),
     *             @OA\Property(property="building", type="object"),
     *             @OA\Property(property="phones", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="activities", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Организация не найдена"
     *     )
     * )
     */

    public function show(Organization $organization): JsonResponse
    {
        return response()->json(
            $organization->load(['building', 'phones', 'activities'])
        );
    }

    /**
     * @OA\Get(
     *     path="/api/organizations",
     *     summary="Фильтрация организаций с различными критериями",
     *     description="Возвращает список организаций с возможностью фильтрации по различным параметрам. Можно использовать только один тип фильтра за раз (по имени, зданию, виду деятельности, радиусу или области).",
     *     tags={"Organizations"},
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Фильтр по названию организации (поиск по частичному совпадению)",
     *         required=false,
     *         @OA\Schema(type="string", example="ООО")
     *     ),
     *     @OA\Parameter(
     *         name="building_id",
     *         in="query",
     *         description="Фильтр по ID здания",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="activity_id",
     *         in="query",
     *         description="Фильтр по ID вида деятельности",
     *         required=false,
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Parameter(
     *         name="include_descendants",
     *         in="query",
     *         description="Включать дочерние виды деятельности (используется вместе с activity_id)",
     *         required=false,
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *     @OA\Parameter(
     *         name="latitude",
     *         in="query",
     *         description="Широта центра для поиска в радиусе (в градусах)",
     *         required=false,
     *         @OA\Schema(type="number", format="float", example=55.751244)
     *     ),
     *     @OA\Parameter(
     *         name="longitude",
     *         in="query",
     *         description="Долгота центра для поиска в радиусе (в градусах)",
     *         required=false,
     *         @OA\Schema(type="number", format="float", example=37.618423)
     *     ),
     *     @OA\Parameter(
     *         name="radius",
     *         in="query",
     *         description="Радиус поиска в километрах",
     *         required=false,
     *         @OA\Schema(type="number", format="float", example=1.5)
     *     ),
     *     @OA\Parameter(
     *         name="min_lat",
     *         in="query",
     *         description="Минимальная широта для прямоугольной области",
     *         required=false,
     *         @OA\Schema(type="number", format="float", example=55.74)
     *     ),
     *     @OA\Parameter(
     *         name="max_lat",
     *         in="query",
     *         description="Максимальная широта для прямоугольной области",
     *         required=false,
     *         @OA\Schema(type="number", format="float", example=55.76)
     *     ),
     *     @OA\Parameter(
     *         name="min_lng",
     *         in="query",
     *         description="Минимальная долгота для прямоугольной области",
     *         required=false,
     *         @OA\Schema(type="number", format="float", example=37.60)
     *     ),
     *     @OA\Parameter(
     *         name="max_lng",
     *         in="query",
     *         description="Максимальная долгота для прямоугольной области",
     *         required=false,
     *         @OA\Schema(type="number", format="float", example=37.62)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Organization")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Неверные параметры запроса",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid parameters"),
     *             @OA\Property(property="errors", type="object", example={"latitude": {"The latitude field is required when longitude is present."}})
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Вид деятельности не найден",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Activity not found")
     *         )
     *     )
     * )
     */
    public function index(OrganizationQuery $query)
    {
        $organizations = $query->with(['building', 'phones', 'activities'])->paginate(15);
        return OrganizationResource::collection($organizations);
    }

}
