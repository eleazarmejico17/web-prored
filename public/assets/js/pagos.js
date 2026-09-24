function copiarAlPortapapeles(texto) {
    const temporal = document.createElement('textarea');
    temporal.value = texto;
    document.body.appendChild(temporal);

    temporal.select();
    document.execCommand('copy');

    document.body.removeChild(temporal);

    alert('¡Número copiado al portapapeles!');
}
