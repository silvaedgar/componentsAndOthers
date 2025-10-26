<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CryptoController extends Controller
{
    //
    public function decrypt(Request $request)
    {
        $request->validate([
            'encrypted' => 'required|string',
            'ivEncrypted' => 'required|string',
            'keyEncrypted' => 'required|string',
        ]);

        try {
            $key = hex2bin($request->keyEncrypted);
            $iv = hex2bin($request->ivEncrypted);
            $ciphertext = base64_decode($request->encrypted);

            $plaintext = openssl_decrypt($ciphertext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

            return response()->json([
                'decrypted' => json_decode($plaintext) ?: 'Error al desencriptar'
            ]);
        } catch (\Exception $e) {
            info('Error en desencriptado: ' . $e->getMessage());
            return response()->json(['error' => 'Excepción en el servidor'], 500);
        }
    }


   public static function dataDecrypt($dataInput) {
        try {
            //$namesExclude = ['fileSession'];
            $encrypt_method = 'AES-256-CBC';
            $dataInputArray = json_decode($dataInput, true);
            $encrypted = $dataInputArray['encrypted'];


            $token = session('tokenSecret');
            $salt = base64_decode($dataInputArray['salt']);
            $key = hash_pbkdf2('sha256', $token, $salt, 1000, 32, true);
            $iv = base64_decode($dataInputArray['iv']);
            // info("Token: $token");
            // info("Salt: $salt " . bin2hex($salt));
            // info("Key: $key");
            // info("IV: $iv");

            $value = base64_decode($encrypted);
            $decrypted = openssl_decrypt($value, $encrypt_method, $key, OPENSSL_RAW_DATA, $iv);
            $data =  json_decode($decrypted);
            $objectArray = [];
            foreach ($data as $item) {
                //if (!in_array($item->name, $namesExclude)) $item->value = Desencriptar::sanitize_input($item->value);  // excluye de la sanitizacion las claves encryptadas ej: passwords
                $objectArray[$item->name] = $item->value;
            }
            return (object) $objectArray;
        } catch (\Throwable $th) {
            info("Metodo: 'dataDecrypt' Extension: 'Utils' Error: {$th->getMessage()} Linea: {$th->getLine()} Datos: " . json_encode($dataInput));
            return null;
        }
    }

    public function generateKeyDerivedToFront() {

        $token  = Str::uuid()->toString();
        $salt = random_bytes(16); // 128 bits
        $key = hash_pbkdf2('sha256', $token, $salt, 1000, 32, true); // 32 bytes

        $iv = random_bytes(16);
        $payload = json_encode(['message' => 'Datos Cifrados en el backend']);
        $encrypted = openssl_encrypt($payload, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

        session(['tokenSecret' => $token]);

        $keys['token'] = $token;
        $keys['salt'] = base64_encode($salt);
        $keys['iv'] = base64_encode($iv);
        $keys['encrypted'] = base64_encode($encrypted);

        info('Token: ' . $token);
        info('Salt: ' . bin2hex($salt) . " length: " . strlen($salt));
        info('Key: ' . bin2hex($key) . " Length: " . strlen($key)); // debe ser 32
        info('IV: ' .  bin2hex($iv) .  "  Length: "  . strlen($iv));   // debe ser 16
        info('Encrypted: ' . bin2hex($iv) .  ' length: ' . strlen($encrypted));
        return response()->json(['keys' => $keys]);
    }
}
