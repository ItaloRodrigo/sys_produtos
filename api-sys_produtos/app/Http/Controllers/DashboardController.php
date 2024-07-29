<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Produto;
use App\Models\Categoria;

class DashboardController extends Controller
{
    public function getDashboardData()
    {
        // Obter a contagem total de usuários
        $totalUsers = User::count();

        // Obter a contagem de produtos por nome
        $products = Produto::selectRaw('name, COUNT(*) as count')
                            ->groupBy('name')
                            ->pluck('count', 'name')
                            ->toArray();
        $productLabels = array_keys($products);
        $productCounts = array_values($products);

        // Obter a contagem de categorias por nome
        $categories = Categoria::selectRaw('name, COUNT(*) as count')
                                ->groupBy('name')
                                ->pluck('count', 'name')
                                ->toArray();
        $categoryLabels = array_keys($categories);
        $categoryCounts = array_values($categories);

        // Montar a resposta
        $data = [
            'userData' => [
                'labels' => ['Total Users'],
                'datasets' => [
                    [
                        'label' => 'Número de Usuários',
                        'data' => [$totalUsers],
                        'backgroundColor' => ['#FF6384'],
                        'borderColor' => '#fff',
                        'borderWidth' => 1
                    ]
                ]
            ],
            'productData' => [
                'labels' => $productLabels,
                'datasets' => [
                    [
                        'label' => 'Número de Produtos',
                        'data' => $productCounts,
                        'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56'],
                        'borderColor' => '#fff',
                        'borderWidth' => 1
                    ]
                ]
            ],
            'categoryData' => [
                'labels' => $categoryLabels,
                'datasets' => [
                    [
                        'label' => 'Número de Categorias',
                        'data' => $categoryCounts,
                        'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56'],
                        'borderColor' => '#fff',
                        'borderWidth' => 1
                    ]
                ]
            ],
        ];

        return response()->json($data);
    }
}

