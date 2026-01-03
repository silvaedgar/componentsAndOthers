// —— Helpers base64 <-> ArrayBuffer ——
function abToBase64(buf) {
  const bytes = new Uint8Array(buf);
  let binary = "";
  for (const element of bytes) binary += String.fromCharCode(element);
  return btoa(binary);
}
function base64ToAb(b64) {
  const binary = atob(b64);
  const bytes = new Uint8Array(binary.length);
  for (let i = 0; i < binary.length; i++) bytes[i] = binary.charCodeAt(i);
  return bytes.buffer;
}

// —— Importar clave pública ECC P-256 (SPKI/base64) ——
async function importServerEcPublicKeySpki(base64Spki) {
    const spki = base64ToAb(base64Spki);
    let importKey = await globalThis.crypto.subtle.importKey(
        "spki",
        spki,
        { name: "ECDH", namedCurve: "P-256" },
        false,
        []
    );
    return  importKey
}

// —— Exportar clave pública ECC (SPKI/base64) ——
async function exportEcPublicKeySpki(pubKey) {
  const spki = await crypto.subtle.exportKey("spki", pubKey);
  return abToBase64(spki);
}

// —— Generar par efímero ECDH (P-256) ——
async function generateClientEcdh() {
  return await crypto.subtle.generateKey(
    { name: "ECDH", namedCurve: "P-256" },
    true,
    ["deriveBits"]
  );
}

// —— Derivar clave de sesión (ECDH + HKDF-SHA256 → AES-GCM 256) ——
async function deriveSessionAesKey(serverPublicKey, clientPrivateKey, salt, info) {
  // ECDH → 256 bits
  const sharedBits = await crypto.subtle.deriveBits(
    { name: "ECDH", public: serverPublicKey },
    clientPrivateKey,
    256
  );

  // HKDF → 256 bits
  const hkdfBaseKey = await crypto.subtle.importKey("raw", sharedBits, "HKDF", false, ["deriveBits"]);
  const derivedBits = await crypto.subtle.deriveBits(
    { name: "HKDF", hash: "SHA-256", salt, info },
    hkdfBaseKey,
    256
  );

  // Importar como AES-GCM 256
  return await crypto.subtle.importKey(
    "raw",
    derivedBits,
    { name: "AES-GCM", length: 256 },
    false,
    ["encrypt", "decrypt"]
  );
}

// —— Generar clave AES-GCM para el payload ——
async function generatePayloadAesKey() {
  return await crypto.subtle.generateKey(
    { name: "AES-GCM", length: 256 },
    true,
    ["encrypt", "decrypt"]
  );
}

// —— Cifrar payload con AES-GCM ——
async function encryptPayloadWithAes(aesKey, data) {
  const iv = crypto.getRandomValues(new Uint8Array(12)); // 96-bit IV
  const encoded = new TextEncoder().encode(JSON.stringify(data));
  const ciphertext = await crypto.subtle.encrypt({ name: "AES-GCM", iv }, aesKey, encoded);
  return { ciphertext: new Uint8Array(ciphertext), iv };
}

// —— Exportar clave AES del payload (raw) ——
async function exportRawAesKey(aesKey) {
  return await crypto.subtle.exportKey("raw", aesKey); // 32 bytes
}

// —— Cifrar la clave AES del payload usando la clave de sesión (AES-GCM) ——
async function encryptAesKeyWithSessionKey(rawPayloadAesKey, sessionAesKey) {
  const wrapIv = crypto.getRandomValues(new Uint8Array(12));
  const wrapped = await crypto.subtle.encrypt(
    { name: "AES-GCM", iv: wrapIv },
    sessionAesKey,
    rawPayloadAesKey
  );
  return { wrappedKey: new Uint8Array(wrapped), wrapIv };
}

// —— Flujo híbrido completo ——
export async function encryptDataWithEccHybrid(data, serverPublicSpkiBase64) {
  // 1) Importar pública del servidor
  const serverPublicKey = await importServerEcPublicKeySpki(serverPublicSpkiBase64);

  // 2) Generar par efímero del cliente
  const clientKeyPair = await generateClientEcdh();

  const clientPublicSpkiBase64 = await exportEcPublicKeySpki(clientKeyPair.publicKey);
  // 3) Salt + info para HKDF (contexto identificable)
  const salt = crypto.getRandomValues(new Uint8Array(32)); // 256-bit
  const info = new TextEncoder().encode("ETIQUETA CLAVE DERIVADA HKDF");
  // 4) Derivar clave de sesión AES-GCM
  const sessionAesKey = await deriveSessionAesKey(
    serverPublicKey,
    clientKeyPair.privateKey,
    salt,
    info
  );
  // 5) Generar clave AES del payload y cifrar datos
  const payloadAesKey = await generatePayloadAesKey();
  const { ciphertext, iv } = await encryptPayloadWithAes(payloadAesKey, data);

  // 6) Exportar y cifrar la clave AES del payload con la clave de sesión
  const rawPayloadAesKey = await exportRawAesKey(payloadAesKey);
  const { wrappedKey, wrapIv } = await encryptAesKeyWithSessionKey(rawPayloadAesKey, sessionAesKey);

  // 7) Devolver todo en base64 para transporte
  return {
    ciphertext: abToBase64(ciphertext.buffer), // tag concatenado al final
    iv: abToBase64(iv.buffer),                 // IV del payload
    wrappedKey: abToBase64(wrappedKey.buffer), // clave AES del payload cifrada
    wrapIv: abToBase64(wrapIv.buffer),         // IV usado para cifrar la clave AES
    salt: abToBase64(salt.buffer),             // para HKDF
    clientPublicKeySpki: clientPublicSpkiBase64, // pública efímera del cliente
    meta: {
      curve: "P-256",
      kdf: "HKDF-SHA256",
      aead: "AES-GCM-256",
      tagLen: 16
    }
  };
}

// —— Ejemplo de uso ——
// const serverPublicSpkiBase64 = await getServerPublicKey();
// const payload = { foo: "bar" };
// const packet = await encryptDataWithEccHybrid(payload, serverPublicSpkiBase64);
// // Enviar 'packet' al backend
