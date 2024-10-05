function showHelpText(element, message) {
    var helpText = document.getElementById('help-text');
    helpText.innerText = message;
    helpText.style.display = 'block';
    helpText.style.top = element.offsetTop + 'px';
    helpText.style.left = (element.offsetLeft + element.offsetWidth + 10) + 'px';
}

function hideHelpText() {
    var helpText = document.getElementById('help-text');
    helpText.style.display = 'none';
}
