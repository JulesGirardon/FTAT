function nextTask() {
    var currentPage = document.location.search.split('page=')[1];
    
    currentPage = parseInt(currentPage) || 1;
    
    var nextPage = currentPage + 1;
    
    var url = 'planning_poker.php?page=' + nextPage;
    
    window.location.href = url;
}
document.querySelector('.next-button').addEventListener('click', nextTask);
