<?php

namespace App\Http\Controllers;

use App\Services\SendGridService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function logError($e)
    {
        $error = static::class . ' -' . ' all - line:' . $e->getLine() . ' - ' . $e->getMessage();
        Log::error($error);
        
        $sendGridServicio = new SendGridService();
        $template = "";
        $hour = date('H');
        if ($hour >= 0 && $hour < 12) {
            $template = 'Buenos días, ';
        } else if ($hour >= 12 && $hour < 19) {
            $template = 'Buenas tardes, ';
        } else {
            $template = 'Buenas noches, ';
        }
        $template .= 'se ha presentado un error en el sistema a las ' . date('H:i:s') . ' del ' . date('d/m/Y') . '. ';
        $template .= 'Por favor, revise el log de errores para más información. ';
        $template .= 'Aquí está la información del error: ' . $error . '.';
        $sendGridServicio->sendEmail('mtz0mau2002@gmail.com', 'Error en el sistema', $template);
    }
}
