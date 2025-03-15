setInterval(() => {
    const timeElement = document.querySelector('#time_element');
(
    async () =>{
        const response = await fetch('/time/index/');
        const answer = await response.json();
        timeElement.textContent = answer.time;
    }
)();
}, 1000);

if (document.querySelector('.user-table')) {
    const tableElement = document.querySelector('.user-table');
    tableElement.addEventListener('click', function (e) {
        const idUser = e.target.id;
    
        const rowEl = e.target.parentElement.parentElement;
        (async () =>{
            const response = await fetch(`/user/delete/?id=${idUser}`);
            const answer = await response.json();
            if(answer){
                rowEl.remove();
            }
        })();
    
    });
}


