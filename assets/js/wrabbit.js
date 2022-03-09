console.log("Follow the white rabbit");

function getRandomInt(min, max) {
  min = Math.ceil(min);
  max = Math.floor(max);
  return Math.floor(Math.random() * (max - min) + min); //The maximum is exclusive and the minimum is inclusive
}


// Easter Rabbit
let rabbit = document.querySelector('#rabbit');
let maxLeft = window.innerWidth/2;
let increment = getRandomInt(1, maxLeft/100);
let idleInDelay = getRandomInt(3, 120);
let moveDelay = idleInDelay + 4;
let idleOutDelay = moveDelay + (2.4 * increment);
rabbit.style.setProperty('--top', getRandomInt(0, window.innerHeight) + 'px');
rabbit.style.setProperty('--right', getRandomInt(0, maxLeft) + 'px');
rabbit.style.setProperty('--increment', increment);
rabbit.style.setProperty('--idle-in-delay', idleInDelay + 's');
rabbit.style.setProperty('--move-delay', moveDelay + 's');
rabbit.style.setProperty('--idle-out-delay', idleOutDelay + 's');
console.log(rabbit);
