


export function serializeFormToArray(formId) {
    const formElement = document.getElementById(formId);
    if (!formElement) return [];

    // 1. Crear el objeto FormData
    const formData = new FormData(formElement);

    const serializedArray = [];

    // 2. Iterar sobre las entradas y construir el array de objetos
    // 'entry' es un array [name, value]
    for (const entry of formData.entries()) {
        serializedArray.push({
            name: entry[0],
            value: entry[1]
        });
    }
    return serializedArray;
}


export function generateFormEncrypt(data, url, token) {
                // Encriptar datos usando CryptoJS
    let dataEncrypted = window.encryptDataSalt(data)
                // Crear un nuevo formulario con los datos encriptados
    let encryptedForm = document.createElement('form');
    encryptedForm.method = 'POST';
    encryptedForm.action = url;
    // CSRF Token
    let csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = token;
    encryptedForm.appendChild(csrfToken);
                // Campo de contraseña encriptada
    let encryptedData = document.createElement('input');
    encryptedData.name = 'data';
    encryptedData.type = 'hidden';
    encryptedData.value = JSON.stringify(dataEncrypted);
    encryptedForm.appendChild(encryptedData);
    document.body.appendChild(encryptedForm);
    return encryptedForm
}

window.serializeFormToArray = serializeFormToArray;
window.generateFormEncrypt = generateFormEncrypt
