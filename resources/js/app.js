import "./bootstrap";

import CryptoJS from "crypto-js";
import JSEncrypt from "jsencrypt";

function dataEncrypt(data) {
    const token = sessionStorage.getItem("tokenKey");
    const salt = CryptoJS.enc.Base64.parse(derivedKey.salt); // misma clave que Laravel
    const ivKey = CryptoJS.enc.Base64.parse(derivedKey.iv);
    const encryptedKey = CryptoJS.enc.Base64.parse(derivedKey.encrypted);
    // Derivar clave con PBKDF2
    const key = CryptoJS.PBKDF2(token, salt, {
        keySize: 256 / 32,
        iterations: 1000,
    });

    //  console.log('Token hex:', token);
    //  console.log('Salt hex:', salt.toString(CryptoJS.enc.Hex), " Length: ", salt.sigBytes);
    //  console.log('Key hex:', key.toString(CryptoJS.enc.Hex), " length ", key.sigBytes);
    //  console.log('IV hex:', ivKey.toString(CryptoJS.enc.Hex), " length ", ivKey.sigBytes);
    //  console.log('Encrypted hex:', encryptedKey.toString(CryptoJS.enc.Hex), " length ", encryptedKey.sigBytes);

    // Desencriptar mensaje del backend
    const decrypted = CryptoJS.AES.decrypt({ ciphertext: encryptedKey }, key, {
        iv: ivKey,
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.Pkcs7,
    });
    // console.log('Decrypted (hex):', decrypted.toString(CryptoJS.enc.Hex));

    try {
        const decoded = decrypted.toString(CryptoJS.enc.Utf8);
        // console.log('Decoded:', JSON.parse(decoded));
    } catch (e) {
        console.error("Error al convertir o parsear:", e);
    }
    const ivData = CryptoJS.lib.WordArray.random(16);

    const encrypted = CryptoJS.AES.encrypt(JSON.stringify(data), key, {
        iv: ivData,
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.Pkcs7,
    });
    data = {
        iv: ivData.toString(CryptoJS.enc.Base64),
        encrypted: encrypted.ciphertext.toString(CryptoJS.enc.Base64),
        salt: derivedKey.salt, // para trazabilidad
    };

    return data;
}

function encryptDataSalt(data) {

    const aesKey = CryptoJS.lib.WordArray.random(32);
    const aesKeyBase64 = CryptoJS.enc.Base64.stringify(aesKey);

    const encryptor = new JSEncrypt();
    encryptor.setPublicKey(publicKey);
    const encryptedKey = encryptor.encrypt(aesKeyBase64); // resultado en base64

    const iv = CryptoJS.lib.WordArray.random(16);
    const encrypted = CryptoJS.AES.encrypt(JSON.stringify(data), aesKey, {
        iv: iv,
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.Pkcs7,
    });

    //  console.log('IV hex:', iv.toString(CryptoJS.enc.Hex), " length ", iv.sigBytes);
    //  console.log('Encrypted hex:', encrypted.toString(), " length ", encrypted.ciphertext.sigBytes);
    //  console.log('EncryptedKey hex:', encryptedKey.toString(CryptoJS.enc.Hex), " length ", encryptedKey.sigBytes);
    data = {
        encryptedKey: encryptedKey,
        iv: iv.toString(CryptoJS.enc.Base64),
        encrypted: encrypted.toString(),
    };

    return data;
}

globalThis.dataEncrypt = dataEncrypt;
globalThis.encryptDataSalt = encryptDataSalt;
