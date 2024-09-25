function nextTask() {
    var currentPage = document.location.search.split('page=')[1];
    currentPage = parseInt(currentPage) || 1;
    var nextPage = currentPage + 1;
    var url = 'planning_poker.php?page=' + nextPage;
    window.location.href = url;
}
document.querySelector('.next-button').addEventListener('click', nextTask);

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
    window.addEventListener('message', function(e) {
        if (e.data.type === 'textareaUpdate') {
            updateCharCount();
        }
    });
}
function setDifficulty(button) {
    const difficultyValue = button.getAttribute('data-difficulty');
    difficulty = difficultyValue;
    document.getElementById('difficulty-display').textContent = difficulty;
}
function foo() {
    alert("Submit button clicked!");
    return true;
 }