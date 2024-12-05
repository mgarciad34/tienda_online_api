<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Cesta;

class RecomendacionesController extends Controller
{
    public function crearRecomendaciones(int $usuarioId)
    {
        $categoriasUsuario = Cesta::where('usuario_id', $usuarioId)
            ->where('estado', 'cerrada')
            ->join('cesta_detalles', 'cestas.id', '=', 'cesta_detalles.cesta_id')
            ->join('productos', 'cesta_detalles.producto_id', '=', 'productos.id')
            ->distinct()
            ->pluck('productos.categoria_id');

        if ($categoriasUsuario->isNotEmpty()) {
            $productosRecomendados = Producto::whereIn('categoria_id', $categoriasUsuario)
                ->inRandomOrder()
                ->limit(5)
                ->get();
        } else {
            $categoriasPopulares = Cesta::where('estado', 'cerrada')
                ->join('cesta_detalles', 'cestas.id', '=', 'cesta_detalles.cesta_id')
                ->join('productos', 'cesta_detalles.producto_id', '=', 'productos.id')
                ->groupBy('productos.categoria_id')
                ->orderByRaw('COUNT(*) DESC')
                ->limit(5)
                ->pluck('productos.categoria_id');

            $productosRecomendados = Producto::whereIn('categoria_id', $categoriasPopulares)
                ->inRandomOrder()
                ->limit(5)
                ->get();

            }

        return response()->json(['productos' => $productosRecomendados]);
    }
}
