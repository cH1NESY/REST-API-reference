<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\Activity\GetRootActivitiesAction;
use App\Actions\Activity\GetActivityByIdAction;
use App\Actions\Activity\GetActivityTreeAction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ActivityController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/activities",
     *     summary="Получить список корневых видов деятельности",
     *     tags={"Activities"},
     *     security={{"apiKey":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
    public function index(GetRootActivitiesAction $getRootActivitiesAction): JsonResponse
    {
        $activities = $getRootActivitiesAction->execute();
        return response()->json($activities);
    }

    /**
     * @OA\Get(
     *     path="/api/activities/{id}",
     *     summary="Получить информацию о виде деятельности",
     *     tags={"Activities"},
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID вида деятельности",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="parent_id", type="integer", nullable=true),
     *             @OA\Property(property="level", type="integer"),
     *             @OA\Property(property="children", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="organizations", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Вид деятельности не найден"
     *     )
     * )
     */
    public function show(string $id, GetActivityByIdAction $getActivityByIdAction): JsonResponse
    {
        $activity = $getActivityByIdAction->execute((int) $id);

        if (!$activity) {
            return response()->json(['error' => 'Activity not found'], 404);
        }

        return response()->json($activity);
    }

    /**
     * @OA\Get(
     *     path="/api/activities/tree",
     *     summary="Получить дерево видов деятельности",
     *     tags={"Activities"},
     *     security={{"apiKey":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
    public function tree(GetActivityTreeAction $getActivityTreeAction): JsonResponse
    {
        $activities = $getActivityTreeAction->execute();
        return response()->json($activities);
    }
}
