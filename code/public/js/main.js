// setInterval(() => {
//     const timeElement = document.querySelector('#time_element');
//     fetch('/time/index/')
//     .then(response => response.json())
//     .then(data => {
//         timeElement.textContent = data.time;
//     })
// }, 1000)

setInterval(() => {
    const timeElement = document.querySelector('#time_element');
(
    async () =>{
        const response = await fetch('/time/index/');
        const answer = await response.json();
        timeElement.textContent = answer.time;
    }
)();
}, 1000)