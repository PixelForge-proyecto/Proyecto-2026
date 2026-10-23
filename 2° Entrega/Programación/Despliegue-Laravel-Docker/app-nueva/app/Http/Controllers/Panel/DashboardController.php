<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Ficha;
use App\Models\Transportista;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $cantidadClientes = Cliente::query()
            ->where('estado', 'activo')
            ->count();

        $cantidadFichas = Ficha::query()
            ->count();

        $cantidadTransportistas = Transportista::query()
            ->where('estado', 'activo')
            ->count();

        $cantidadEntregasMes = Ficha::query()
            ->where('estado', 'entregada')
            ->whereHas(
                'seguimientos',
                function ($query) {
                    $query
                        ->where('etapa', 'entrega')
                        ->whereYear('fecha', now()->year)
                        ->whereMonth('fecha', now()->month);
                }
            )
            ->count();

        return view(
            'panel.dashboard',
            compact(
                'cantidadClientes',
                'cantidadFichas',
                'cantidadTransportistas',
                'cantidadEntregasMes'
            )
        );
    }
}
