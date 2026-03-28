<?php

namespace App\Http\Controllers\Api\ServicesApp;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\Main\UserResource;
use App\Http\Resources\ServicesApp\EvaluationResource;
use App\Models\Evaluation;
use App\Models\User;

class ProfileController extends Controller
{
    use ApiResponse;

    public function worker($id)
    {
        $language = app()->getLocale();
        $worker = User::select(
            'users.id',
            'users.name',
            'users.email',
            'users.phone',
            'users.image',
            'users.rating',
            "cities.name_$language AS city_name",
            "categories.name_$language AS category_name",
            'user_details.description'
        )
            ->with([
                'subCategories' => function ($query) use ($language) {
                    $query->select('sub_categories.id', 'user_sub_categories.user_id', "sub_categories.name_$language AS name")
                        ->join('sub_categories', 'sub_categories.id', '=', 'user_sub_categories.sub_category_id');
                }
            ])
            ->join('orders', 'users.id', '=', 'orders.worker_id')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->join('cities', 'user_details.city_id', '=', 'cities.id')
            ->join('categories', 'user_details.category_id', '=', 'categories.id')
            ->where([['role_id', '3'], ['orders.status', '>', 0], ['orders.client_id', auth()->id()]])
            ->findOrFail($id);

        return $this->apiResponse(200, 'worker', null, new UserResource($worker));
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function evaluations()
    {
        $evaluations = Evaluation::select('evaluations.order_id', 'users.name as client_name', 'users.image as client_image', 'evaluations.rating', 'evaluations.message')
            ->join('users', 'evaluations.client_id', '=', 'users.id')
            ->where('worker_id', auth()->id())
            ->orderBy('evaluations.created_at', 'DESC')
            ->get();

        return $this->apiResponse(200, 'evaluations', null, EvaluationResource::collection($evaluations));
    }
}
