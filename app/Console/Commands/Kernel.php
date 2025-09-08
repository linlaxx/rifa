<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define la programación de comandos de la aplicación.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Aquí agregas tus comandos programados
        // Ejemplo: ejecutar cada minuto
        // $schedule->command('inspire')->everyMinute();

        // 👇 tu comando de liberar reservas cada hora (puedes cambiarlo a cada minuto para probar)
        $schedule->command('reservas:liberar')->hourly();
    }

    /**
     * Registra los comandos para la aplicación.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
