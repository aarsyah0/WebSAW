<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecommendationRequest;
use App\Models\Criteria;
use App\Services\ToyRecommendationService;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function __construct(
        protected ToyRecommendationService $sawService
    ) {}

    public function index()
    {
        $criterias = Criteria::orderBy('weight_order')->get();
        return view('user.recommendation', compact('criterias'));
    }

    public function result(RecommendationRequest $request)
    {
        $input = [
            'age_min' => $request->age_min,
            'age_max' => $request->age_max,
            'budget_min' => $request->budget_min,
            'budget_max' => $request->budget_max,
            'priorities' => $request->input('priorities', []),
        ];

        $data = $this->sawService->getRecommendationsWithMatrix($input);

        return view('user.recommendation-result', [
            'recommendations' => $data['recommendations'],
            'input' => $input,
            'criterias' => $data['criterias'],
            'decision_matrix' => $data['decision_matrix'],
            'normalized_matrix' => $data['normalized_matrix'],
            'weights' => $data['weights'],
            'preference_scores' => $data['preference_scores'],
        ]);
    }
}
