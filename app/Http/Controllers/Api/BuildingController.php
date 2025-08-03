<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\Building\GetAllBuildingsAction;
use App\Actions\Building\GetBuildingByIdAction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BuildingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/buildings",
     *     summary="Получить список всех зданий",
     *     tags={"Buildings"},
     *     security={{"apiKey":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
    public function index(GetAllBuildingsAction $getAllBuildingsAction): JsonResponse
    {
        $buildings = $getAllBuildingsAction->execute();
        return response()->json($buildings);
    }

    /**
     * @OA\Get(
     *     path="/api/buildings/{id}",
     *     summary="Получить информацию о здании",
     *     tags={"Buildings"},
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID здания",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="address", type="string"),
     *             @OA\Property(property="latitude", type="number"),
     *             @OA\Property(property="longitude", type="number"),
     *             @OA\Property(property="organizations", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Здание не найдено"
     *     )
     * )
     */
    public function show(string $id, GetBuildingByIdAction $getBuildingByIdAction): JsonResponse
    {
        $building = $getBuildingByIdAction->execute((int) $id);

        if (!$building) {
            return response()->json(['error' => 'Building not found'], 404);
        }

        return response()->json($building);
    }

}
