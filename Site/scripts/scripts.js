function handleButtonClick(button) {
    const taskId = button.dataset.taskId;
    const dropdownId = `myDropdown-${taskId}`;
    const dropdown = document.getElementById(dropdownId);

    if (dropdown) {
        dropdown.classList.toggle("show");
    }
}
window.onclick = function(event) {
    if (!event.target.matches('.dropbtn') && !event.target.closest('.dropdown-content')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

let currentTask = 1;
function nextTask() {
    const taskRows = document.querySelectorAll('.task-row');
    console.log("Nombre de tâches : ", taskRows.length);
    console.log("Tâche actuelle : ", currentTask);
    
    if (currentTask >= taskRows.length) {
        alert('Fin des tâches');
        return;
    }

    const currentRow = taskRows[currentTask - 1];
    const nextRow = taskRows[currentTask];

    console.log("Affichant la ligne suivante : ", nextRow);

    currentRow.style.display = 'none';
    
    if (nextRow) {
        nextRow.style.display = 'block';
    }

    currentTask++;
}
