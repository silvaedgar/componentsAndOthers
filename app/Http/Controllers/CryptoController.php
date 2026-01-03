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


    public static function dataDecrypt($dataInput)
    {
        try {
            //$namesExclude = ['fileSession'];
            $encrypt_method = 'AES-256-CBC';
            $dataInputArray = json_decode($dataInput, true);
            $encrypted = $dataInputArray['encrypted'];


            $token = session('tokenSecret');
            $salt = base64_decode($dataInputArray['salt']);
            $key = hash_pbkdf2('sha256', $token, $salt, 1000, 32, true);
            $iv = base64_decode($dataInputArray['iv']);

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

    public function generateKeyDerivedToFront()
    {

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

    // Funcion que retorna la clave pública para el frontend
    public function getPublicKey()
    {
        try {
            //code...
            $frontendPublicKeySpki = file_get_contents(base_path(env('ECC_PUBLIC_SPKI_DER_PATH')));
            return response()->json([
                'publicKeySpki' =>  base64_encode($frontendPublicKeySpki)
            ]);
        } catch (\Throwable $th) {
            info($th->getMessage());
            return response()->json([
                'publicKey' => "ERROR"
            ]);
        }
        // Clave pública del frontend (puede venir de config o base de datos)
    }


    public static function b64d(string $b64)
    {
        return base64_decode($b64, true);
    }

    public static function hkdf_sha256(string $ikm, int $length, string $info = '', string $salt = '')
    {
        // Usa hash_hmac internamente: implementación estándar de HKDF
        $length = (int) $length;
        $salt = $salt !== '' ? $salt : str_repeat("\0", 32); // si no hay salt, RFC sugiere zeros del tamaño del hash
        $prk = hash_hmac('sha256', $ikm, $salt, true);
        $t = '';
        $okm = '';
        for ($block = 1; strlen($okm) < $length; $block++) {
            $t = hash_hmac('sha256', $t . $info . chr($block), $prk, true);
            $okm .= $t;
        }
        return substr($okm, 0, $length);
    }

    public static function spkiDerToPem(string $der): string {
        $b64 = chunk_split(base64_encode($der), 64, "\n");
        return "-----BEGIN PUBLIC KEY-----\n" . $b64 . "-----END PUBLIC KEY-----\n";
    }


    public static function receiveEncrypted(Request $request)
    {
        try {
            // 1) Derivación ECDH
            $data = json_decode($request->data);
            INFO('Datos recibidos: ' . json_encode($data));
            $serverPrivatePem = file_get_contents(base_path(env('ECC_PRIVATE_KEY_PATH')));
            $serverPrivate = openssl_pkey_get_private($serverPrivatePem);
            if (!$serverPrivate) throw new \Exception('Clave privada del servidor inválida');

            $clientSpkiDer = self::b64d($data->clientPublicKeySpki);
            $clientSpkiPem = self::spkiDerToPem($clientSpkiDer);

            $clientPublic = openssl_pkey_get_public($clientSpkiPem);
            if (!$clientPublic) throw new \Exception('Clave pública del cliente inválida');
            $sharedSecret = openssl_pkey_derive($clientPublic, $serverPrivate, 32);
            if ($sharedSecret === false) throw new \Exception('Derivación ECDH fallida');

            // 2) HKDF
            $salt = self::b64d($data->salt);
            $info = 'ETIQUETA CLAVE DERIVADA HKDF';
            $sessionKey = self::hkdf_sha256($sharedSecret, 32, $info, $salt);

            // 3) Desencriptar clave de contenido
            $wrapCt  = self::b64d($data->wrappedKey);
            $wrapIv  = self::b64d($data->wrapIv);

            // Separar ciphertext y tag (últimos 16 bytes)
            $wrapBytes = $wrapCt;
            $wrapTag   = substr($wrapBytes, -16);
            $wrapCiphertext = substr($wrapBytes, 0, -16);

            $payloadKey = openssl_decrypt($wrapCiphertext, 'aes-256-gcm', $sessionKey, OPENSSL_RAW_DATA, $wrapIv, $wrapTag);
            if ($payloadKey === false || strlen($payloadKey) !== 32) {
                throw new RuntimeException('Fallo al desencriptar la clave de contenido');
            }

            // 4) Desencriptar payload
            $ciphertext = self::b64d($data->ciphertext);
            $iv         = self::b64d($data->iv);
            $tag = substr($ciphertext, -16);
            $ciphertext = substr($ciphertext, 0, -16);
            $plaintext  = openssl_decrypt($ciphertext,
                'aes-256-gcm',
                $payloadKey,
                OPENSSL_RAW_DATA,
                $iv,
                $tag
            );
            if ($plaintext === false) {
                throw new RuntimeException('Fallo al desencriptar payload');
            }

            // 5) Procesa el plaintext (JSON u otro formato)
            $data = json_decode($plaintext);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new RuntimeException('Plaintext no es JSON válido');
            }
            info('Datos desencriptados: ' . json_encode($data));
            $objectArray = [];
            foreach ($data as $item) {
                $objectArray[$item->name] = $item->value;
            }
            return (object) $objectArray;
            //return response()->json(['ok' => true, 'data' => $data]);

        } catch (\Throwable $e) {
            info('Error al recibir datos cifrados: ' . $e->getMessage() . ' Linea: ' . $e->getLine());
            return response()->json(['ok' => false, 'error' => $e->getMessage()], 400);
        }
    }
}
