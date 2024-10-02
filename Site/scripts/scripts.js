function adjustTextAreaSize(textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
}
function ajustRemainChar(textArea) {
    const maxChars = 255;
    const charCountElement = document.getElementById('char-count');

    function updateCharCount() {
        const currentLength = textArea.value.length;
        const remainingChars = maxChars - currentLength;
        charCountElement.textContent = `Poster un commentaire (${remainingChars}) caratères restants`;
    }
    updateCharCount();
    textArea.addEventListener('input', updateCharCount);
    window.addEventListener('message', function (e) {
        if (e.data.type === 'textareaUpdate') {
            updateCharCount();
        }
    });
}
function setDifficulty(button) {
    const difficultyValue = button.getAttribute('data-difficulty');
    document.getElementById('difficulty').value = difficultyValue;
    document.getElementById('difficulty-display').textContent = difficultyValue;
}

