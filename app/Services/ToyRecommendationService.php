<?php

namespace App\Services;

use App\Models\Criteria;
use App\Models\Product;
use Illuminate\Support\Collection;

class ToyRecommendationService
{
    /**
     * Kriteria key yang dipetakan dari input user (slider 1-5)
     * Urutan: harga, kualitas, keamanan, edukasi, popularitas
     */
    protected array $criteriaKeys = ['harga', 'kualitas', 'keamanan', 'edukasi', 'popularitas'];

    /**
     * Hitung rekomendasi SAW.
     *
     * @param  array  $input  ['age_min', 'age_max', 'budget_min', 'budget_max', 'priorities' => [harga=>1-5, ...]]
     * @return Collection<int, array{rank: int, product: Product, score: float, explanation: string}>
     */
    public function getRecommendations(array $input): Collection
    {
        $ageMin = (int) ($input['age_min'] ?? 0);
        $ageMax = (int) ($input['age_max'] ?? 99);
        $budgetMin = (float) ($input['budget_min'] ?? 0);
        $budgetMax = (float) ($input['budget_max'] ?? 999999999);
        $priorities = $input['priorities'] ?? [];

        $products = $this->getFilteredProducts($ageMin, $ageMax, $budgetMin, $budgetMax);
        if ($products->isEmpty()) {
            return collect();
        }

        $criterias = Criteria::orderBy('weight_order')->get();
        if ($criterias->isEmpty()) {
            return $products->take(5)->map(fn ($p, $i) => [
                'rank' => $i + 1,
                'product' => $p,
                'score' => 0.0,
                'explanation' => 'Produk ini tersedia dalam rentang usia dan budget Anda.',
            ]);
        }

        $weights = $this->buildWeightsFromPriorities($priorities, $criterias);
        $matrix = $this->buildMatrix($products, $criterias);
        $normalized = $this->normalizeMatrix($matrix, $criterias);
        $scores = $this->calculatePreferenceScores($normalized, $weights, $criterias);

        $ranked = $scores->sortByDesc('score')->take(5)->values();
        $criteriaNames = $criterias->keyBy('id')->map->name;

        return $ranked->map(function ($item, $index) use ($products, $criteriaNames, $priorities) {
            $product = $products->firstWhere('id', $item['product_id']);
            if (! $product) {
                return null;
            }
            $explanation = $this->buildExplanation($product, $item['score'], $priorities, $criteriaNames);
            return [
                'rank' => $index + 1,
                'product' => $product,
                'score' => round($item['score'], 4),
                'explanation' => $explanation,
            ];
        })->filter()->values();
    }

    /**
     * Hitung rekomendasi SAW beserta data matriks untuk tampilan (skripsi).
     *
     * @return array{recommendations: Collection, criterias: array, decision_matrix: array, normalized_matrix: array, weights: array, preference_scores: array}
     */
    public function getRecommendationsWithMatrix(array $input): array
    {
        $ageMin = (int) ($input['age_min'] ?? 0);
        $ageMax = (int) ($input['age_max'] ?? 99);
        $budgetMin = (float) ($input['budget_min'] ?? 0);
        $budgetMax = (float) ($input['budget_max'] ?? 999999999);
        $priorities = $input['priorities'] ?? [];

        $products = $this->getFilteredProducts($ageMin, $ageMax, $budgetMin, $budgetMax);
        if ($products->isEmpty()) {
            return [
                'recommendations' => collect(),
                'criterias' => [],
                'decision_matrix' => [],
                'normalized_matrix' => [],
                'weights' => [],
                'preference_scores' => [],
            ];
        }

        $criterias = Criteria::orderBy('weight_order')->get();
        if ($criterias->isEmpty()) {
            $recommendations = $products->take(5)->map(fn ($p, $i) => [
                'rank' => $i + 1,
                'product' => $p,
                'score' => 0.0,
                'explanation' => 'Produk ini tersedia dalam rentang usia dan budget Anda.',
            ]);
            return [
                'recommendations' => $recommendations,
                'criterias' => [],
                'decision_matrix' => [],
                'normalized_matrix' => [],
                'weights' => [],
                'preference_scores' => [],
            ];
        }

        $weights = $this->buildWeightsFromPriorities($priorities, $criterias);
        $matrix = $this->buildMatrix($products, $criterias);
        $normalized = $this->normalizeMatrix($matrix, $criterias);
        $scores = $this->calculatePreferenceScores($normalized, $weights, $criterias);
        $ranked = $scores->sortByDesc('score')->take(5)->values();

        $nameToKey = [
            'Harga' => 'harga', 'Kualitas' => 'kualitas', 'Keamanan' => 'keamanan',
            'Edukasi' => 'edukasi', 'Popularitas' => 'popularitas',
        ];
        $criteriasWithWeight = [];
        foreach ($criterias as $c) {
            $key = $nameToKey[$c->name] ?? strtolower($c->name);
            $criteriasWithWeight[] = [
                'name' => $c->name,
                'type' => $c->type,
                'weight' => $weights[$key] ?? 0,
            ];
        }

        $decisionMatrix = [];
        foreach ($matrix as $productId => $row) {
            $product = $products->firstWhere('id', $productId);
            $r = ['product_id' => $productId, 'product_name' => $product ? $product->name : '-'];
            foreach ($criterias as $c) {
                $r[$c->name] = $row[$c->name] ?? 0;
            }
            $decisionMatrix[] = $r;
        }

        $normalizedMatrix = [];
        foreach ($normalized as $productId => $row) {
            $product = $products->firstWhere('id', $productId);
            $r = ['product_id' => $productId, 'product_name' => $product ? $product->name : '-'];
            foreach ($criterias as $c) {
                $val = $row[$c->name] ?? 0;
                $r[$c->name] = round((float) $val, 4);
            }
            $normalizedMatrix[] = $r;
        }

        $preferenceScores = $scores->keyBy('product_id')->map(fn ($item) => round($item['score'], 4))->all();

        $criteriaNames = $criterias->keyBy('id')->map->name;
        $recommendations = $ranked->map(function ($item, $index) use ($products, $criteriaNames, $priorities) {
            $product = $products->firstWhere('id', $item['product_id']);
            if (! $product) {
                return null;
            }
            $explanation = $this->buildExplanation($product, $item['score'], $priorities, $criteriaNames);
            return [
                'rank' => $index + 1,
                'product' => $product,
                'score' => round($item['score'], 4),
                'explanation' => $explanation,
            ];
        })->filter()->values();

        return [
            'recommendations' => $recommendations,
            'criterias' => $criteriasWithWeight,
            'decision_matrix' => $decisionMatrix,
            'normalized_matrix' => $normalizedMatrix,
            'weights' => $weights,
            'preference_scores' => $preferenceScores,
        ];
    }

    protected function getFilteredProducts(int $ageMin, int $ageMax, float $budgetMin, float $budgetMax): Collection
    {
        return Product::with('criterias')
            ->where('stock', '>', 0)
            ->whereBetween('price', [$budgetMin, $budgetMax])
            ->get()
            ->filter(function ($product) use ($ageMin, $ageMax) {
                if (empty($product->age_range)) {
                    return true;
                }
                return $this->ageRangeOverlaps($product->age_range, $ageMin, $ageMax);
            })
            ->values();
    }

    protected function ageRangeOverlaps(string $ageRange, int $childMin, int $childMax): bool
    {
        if (preg_match('/^(\d+)\s*-\s*(\d+)$/', trim($ageRange), $m)) {
            $pMin = (int) $m[1];
            $pMax = (int) $m[2];
            return $childMax >= $pMin && $childMin <= $pMax;
        }
        return true;
    }

    protected function buildWeightsFromPriorities(array $priorities, Collection $criterias): array
    {
        $names = [
            'harga' => 'Harga',
            'kualitas' => 'Kualitas',
            'keamanan' => 'Keamanan',
            'edukasi' => 'Edukasi',
            'popularitas' => 'Popularitas',
        ];
        $byName = $criterias->keyBy('name');
        $weights = [];
        $sum = 0;
        foreach ($this->criteriaKeys as $key) {
            $w = (float) ($priorities[$key] ?? 1);
            if ($w < 1) {
                $w = 1;
            }
            $weights[$key] = $w;
            $sum += $w;
        }
        foreach ($weights as $k => $v) {
            $weights[$k] = $sum > 0 ? $v / $sum : 1 / count($this->criteriaKeys);
        }
        return $weights;
    }

    protected function buildMatrix(Collection $products, Collection $criterias): array
    {
        $matrix = [];

        foreach ($products as $product) {
            $row = ['product_id' => $product->id];
            foreach ($product->criterias as $pc) {
                $criteria = $criterias->firstWhere('id', $pc->id);
                if ($criteria) {
                    $row[$criteria->name] = (float) $pc->pivot->value;
                }
            }
            foreach ($criterias as $c) {
                if (! isset($row[$c->name])) {
                    $row[$c->name] = $c->name === 'Harga' ? (float) $product->price : 0;
                }
            }
            $matrix[$product->id] = $row;
        }

        return $matrix;
    }

    protected function normalizeMatrix(array $matrix, Collection $criterias): array
    {
        if (empty($matrix)) {
            return [];
        }

        $normalized = [];
        foreach (array_keys($matrix) as $pid) {
            $normalized[$pid] = ['product_id' => $pid];
        }

        foreach ($criterias as $criteria) {
            $key = $criteria->name;
            $values = array_column($matrix, $key);
            $max = max($values);
            $min = min($values);
            $isCost = $criteria->type === 'cost';

            if ($isCost) {
                foreach ($matrix as $pid => $row) {
                    $val = (float) ($row[$key] ?? 0);
                    $normalized[$pid][$key] = $min > 0 && $val > 0 ? $min / $val : 0;
                }
            } else {
                foreach ($matrix as $pid => $row) {
                    $val = (float) ($row[$key] ?? 0);
                    $normalized[$pid][$key] = $max > 0 ? $val / $max : 0;
                }
            }
        }

        return $normalized;
    }

    protected function calculatePreferenceScores(array $normalized, array $weights, Collection $criterias): Collection
    {
        $result = collect();
        $nameToKey = [
            'Harga' => 'harga', 'Kualitas' => 'kualitas', 'Keamanan' => 'keamanan',
            'Edukasi' => 'edukasi', 'Popularitas' => 'popularitas',
        ];
        foreach ($normalized as $productId => $row) {
            $vi = 0;
            foreach ($criterias as $c) {
                $key = $nameToKey[$c->name] ?? strtolower($c->name);
                $w = $weights[$key] ?? (1 / count($this->criteriaKeys));
                $r = (float) ($row[$c->name] ?? 0);
                $vi += $w * $r;
            }
            $result->push(['product_id' => $productId, 'score' => $vi]);
        }
        return $result;
    }

    protected function buildExplanation($product, float $score, array $priorities, $criteriaNames): string
    {
        return 'Produk ini direkomendasikan karena memiliki nilai tinggi pada kriteria yang Anda prioritaskan.';
    }
}
