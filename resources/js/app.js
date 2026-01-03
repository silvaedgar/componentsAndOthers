import "./bootstrap";

import { encryptDataWithEccHybrid  } from './crypto';

// Ejemplo de uso en tu frontend
async function sendSecureData(payload) {
  const serverPublicSpkiBase64 = sessionStorage.getItem("keySpkiBase64");
  const packet = await encryptDataWithEccHybrid(payload, serverPublicSpkiBase64);
  return packet

}

// ——— Exponer globalmente para uso en formularios ———
globalThis.encryptData = sendSecureData;
