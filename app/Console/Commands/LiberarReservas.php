<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reserva;
use Carbon\Carbon;

class LiberarReservas extends Command
{
    protected $signature = 'reservas:liberar';
    protected $description = 'Libera boletos reservados que no fueron confirmados en 24h';

    public function handle()
    {
        $ahora = Carbon::now();

        // Buscar reservas vencidas
        $reservas = Reserva::where('expira_en', '<', $ahora)->get();

        foreach ($reservas as $reserva) {
            $boleto = $reserva->boleto;

            // Solo liberar si el boleto NO fue vendido
            if ($boleto && $boleto->vendido == 0) {
                $boleto->disponible = 1; // Lo hacemos disponible otra vez
                $boleto->save();

                $reserva->delete(); // Borrar datos del usuario
                $this->info("Boleto {$boleto->numero} liberado.");
            }
        }

        $this->info("Reservas vencidas revisadas y liberadas.");
        return Command::SUCCESS;
    }
}
