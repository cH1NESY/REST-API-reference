<?php

namespace App\Http\Controllers\Api;

use App\DTO\AreaParamsDTO;
use App\DTO\GeoParamsDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetByAreaRequest;
use App\Http\Requests\GetByRadiusRequest;
use App\Http\Requests\SearchByNameRequest;
use App\Actions\Organization\GetOrganizationByIdAction;
use App\Actions\Organization\GetOrganizationsByBuildingAction;
use App\Actions\Organization\GetOrganizationsByActivityAction;
use App\Actions\Organization\GetOrganizationsByActivityWithDescendantsAction;
use App\Actions\Organization\GetOrganizationsByRadiusAction;
use App\Actions\Organization\GetOrganizationsByAreaAction;
use App\Actions\Organization\SearchOrganizationsByNameAction;
use App\Models\Activity;
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
    /**
     * @OA\Get(
     *     path="/api/organizations/{id}",
     *     summary="Получить информацию об организации",
     *     tags={"Organizations"},
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
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
    public function show(
        string $id,
        GetOrganizationByIdAction $getOrganizationByIdAction
    ): JsonResponse {
        $organization = $getOrganizationByIdAction->execute((int) $id);

        if (!$organization) {
            return response()->json(['error' => 'Organization not found'], 404);
        }

        return response()->json($organization);
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/building/{buildingId}",
     *     summary="Получить список организаций в здании",
     *     tags={"Organizations"},
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(
     *         name="buildingId",
     *         in="path",
     *         required=true,
     *         description="ID здания",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
    public function getByBuilding(
        int $buildingId,
        GetOrganizationsByBuildingAction $getOrganizationsByBuildingAction
    ): JsonResponse {
        $organizations = $getOrganizationsByBuildingAction->execute($buildingId);
        return response()->json($organizations);
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/activity/{activityId}",
     *     summary="Получить список организаций по виду деятельности",
     *     tags={"Organizations"},
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(
     *         name="activityId",
     *         in="path",
     *         required=true,
     *         description="ID вида деятельности",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
    public function getByActivity(
        int $activityId,
        GetOrganizationsByActivityAction $getOrganizationsByActivityAction
    ): JsonResponse {
        $organizations = $getOrganizationsByActivityAction->execute($activityId);
        return response()->json($organizations);
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/activity/{activityId}/descendants",
     *     summary="Получить список организаций по виду деятельности с потомками",
     *     tags={"Organizations"},
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(
     *         name="activityId",
     *         in="path",
     *         required=true,
     *         description="ID вида деятельности",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Вид деятельности не найден"
     *     )
     * )
     */
    public function getByActivityWithDescendants(
        int $activityId,
        GetOrganizationsByActivityWithDescendantsAction $getOrganizationsByActivityWithDescendantsAction
    ): JsonResponse {
        $activity = Activity::find($activityId);
        if (!$activity) {
            return response()->json(['error' => 'Activity not found'], 404);
        }

        $organizations = $getOrganizationsByActivityWithDescendantsAction->execute($activityId);
        return response()->json($organizations);
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/radius",
     *     summary="Получить организации в радиусе от точки",
     *     tags={"Organizations"},
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(
     *         name="latitude",
     *         in="query",
     *         required=true,
     *         description="Широта",
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="longitude",
     *         in="query",
     *         required=true,
     *         description="Долгота",
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="radius",
     *         in="query",
     *         required=true,
     *         description="Радиус в километрах",
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
    public function getByRadius(
        GetByRadiusRequest $request,
        GetOrganizationsByRadiusAction $getOrganizationsByRadiusAction
    ): JsonResponse {
        $params = new GeoParamsDTO(
            $request->validated('latitude'),
            $request->validated('longitude'),
            $request->validated('radius')
        );

        return response()->json($getOrganizationsByRadiusAction->execute($params));
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/area",
     *     summary="Получить организации в прямоугольной области",
     *     tags={"Organizations"},
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(
     *         name="min_lat",
     *         in="query",
     *         required=true,
     *         description="Минимальная широта",
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="max_lat",
     *         in="query",
     *         required=true,
     *         description="Максимальная широта",
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="min_lng",
     *         in="query",
     *         required=true,
     *         description="Минимальная долгота",
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="max_lng",
     *         in="query",
     *         required=true,
     *         description="Максимальная долгота",
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
    public function getByArea(
        GetByAreaRequest $request,
        GetOrganizationsByAreaAction $getOrganizationsByAreaAction
    ): JsonResponse {
        $params = new AreaParamsDTO(
            $request->validated('min_lat'),
            $request->validated('max_lat'),
            $request->validated('min_lng'),
            $request->validated('max_lng')
        );

        return response()->json($getOrganizationsByAreaAction->execute($params));
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/search",
     *     summary="Поиск организаций по названию",
     *     tags={"Organizations"},
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         description="Название организации",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
    public function searchByName(
        SearchByNameRequest $request,
        SearchOrganizationsByNameAction $searchOrganizationsByNameAction
    ): JsonResponse {
        $organizations = $searchOrganizationsByNameAction->execute(
            $request->validated('name')
        );
        return response()->json($organizations);
    }
}
