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
