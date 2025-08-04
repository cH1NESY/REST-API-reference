<?php

namespace App\Queries;

use App\Models\Activity;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\QueryBuilder;

class OrganizationQuery extends QueryBuilder
{
    public function __construct()
    {
        parent::__construct(Organization::query());

        $this->allowedFilters([
            AllowedFilter::partial('name'),

            AllowedFilter::exact('building_id'),

            AllowedFilter::callback('activity_id', function (Builder $query, $value) {
                $includeDescendants = request()->boolean('include_descendants', false);

                if ($includeDescendants) {
                    // Здесь реализуй логику выбора активности с потомками
                    // Например, получить все id активностей включая потомков
                    $activityIds = Activity::where('id', $value)
                        ->orWhere('parent_id', $value) // или более сложная логика потомков
                        ->pluck('id');

                    $query->whereHas('activities', function (Builder $q) use ($activityIds) {
                        $q->whereIn('activities.id', $activityIds);
                    });

                } else {
                    $query->whereHas('activities', function (Builder $q) use ($value) {
                        $q->where('activities.id', $value);
                    });
                }
            }),

            AllowedFilter::callback('radius', function (Builder $query, $value) {
                // ожидаем, что в request есть latitude, longitude, radius
                $lat = request()->input('latitude');
                $lng = request()->input('longitude');
                $radius = request()->input('radius');

                if ($lat && $lng && $radius) {
                    $query->whereRaw(
                        '(6371 * acos(cos(radians(?)) * cos(radians(buildings.latitude)) * cos(radians(buildings.longitude) - radians(?)) + sin(radians(?)) * sin(radians(buildings.latitude)))) < ?',
                        [$lat, $lng, $lat, $radius]
                    )->join('buildings', 'organizations.building_id', '=', 'buildings.id');
                }
            }),

            AllowedFilter::callback('area', function (Builder $query, $value) {
                $minLat = request()->input('min_lat');
                $maxLat = request()->input('max_lat');
                $minLng = request()->input('min_lng');
                $maxLng = request()->input('max_lng');

                if ($minLat !== null && $maxLat !== null && $minLng !== null && $maxLng !== null) {
                    $query->join('buildings', 'organizations.building_id', '=', 'buildings.id')
                        ->whereBetween('buildings.latitude', [$minLat, $maxLat])
                        ->whereBetween('buildings.longitude', [$minLng, $maxLng]);
                }
            }),
        ]);

        $this->allowedIncludes([
            AllowedInclude::relationship('building'),
            AllowedInclude::relationship('phones'),
            AllowedInclude::relationship('activities'),
        ]);
    }
}
