<?php

namespace App\Extensions;

use App\Models\Logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use stdClass;

class Utils
{
    public function makeLog($modulo="",$message = "", $jsonInput = null, $jsonOutput = null,$error=null) {

        $userId = 0;
        if (auth()->check()) {
            $user = auth()->user();
            $userId = $user ? $user->id : null;
        }
        $controller = $this->getModulo();
        $remote['ip'] =  $_SERVER['REMOTE_ADDR'];
        $remote['port'] = $_SERVER['REMOTE_PORT'];
        $remote['url'] = $_SERVER['REQUEST_URI'];
        $remote['method'] = $_SERVER['REQUEST_METHOD'];
        $remote['userAgent'] = $_SERVER['HTTP_USER_AGENT'];

        Logs::create([
            'user_id' => $userId,
            'module' => $modulo,
            'action' => $remote['method'],
            'description' => $message,
            'json_input' => json_encode($jsonInput),
            'json_output' => json_encode($jsonOutput),
            'changes' => null,
            'controller' => $controller['controller'][count($controller['controller']) - 1],
            'method' => $controller['metodo'],
            'remote_ip' => $remote['ip'],
            'remote_port' => $remote['port'],
            'browser_agent' => $remote['userAgent'],
            'url' => $remote['url']
        ]);
        if (isset($error))
            Log::error("Metodo: '{$controller['metodo']}' - Controlador: '{$controller['controller'][count($controller['controller']) - 1]}' - Error: $error");

    }

    private function getModulo() {
        $action = Route::currentRouteAction();
        $controller = explode('@', $action)[0];
        $metodo = explode('@', $action)[1];
        $controller = explode('\\', $controller);
        //info($controller);
        return [
            'controller' => $controller,
            'metodo' => $metodo,
        ];
    }

    public function hideFieldLog(Request $request, $fields = []) {
        $jsonInput = new stdClass();
        foreach ($request->all() as $key => $value)
            $jsonInput->$key = (!in_array($key, $fields) ? $value : "********");
        return $jsonInput;
    }
}
