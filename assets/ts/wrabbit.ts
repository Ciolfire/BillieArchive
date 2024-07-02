console.log("Follow the white rabbit");

function getRandomInt(min, max) {
  min = Math.ceil(min);
  max = Math.floor(max);
  return Math.floor(Math.random() * (max - min) + min); //The maximum is exclusive and the minimum is inclusive
}


// Easter Rabbit
let rabbit = document.querySelector('#rabbit') as HTMLElement;
let maxLeft = window.innerWidth/2;
let increment = getRandomInt(1, maxLeft/100);
let idleInDelay = getRandomInt(3, 120);
let moveDelay = idleInDelay + 4;
let idleOutDelay = moveDelay + (2.4 * increment);
let rand =  getRandomInt(0, 99);
let max =  getRandomInt(1, getRandomInt(2, 50));
let count = 0;
let ratio = 5;

rabbit.style.setProperty('--top', getRandomInt(0, window.innerHeight) + 'px');
rabbit.style.setProperty('--right', getRandomInt(0, maxLeft) + 'px');
rabbit.style.setProperty('--increment', String(increment));
rabbit.style.setProperty('--idle-in-delay', idleInDelay + 's');
rabbit.style.setProperty('--move-delay', moveDelay + 's');
rabbit.style.setProperty('--idle-out-delay', idleOutDelay + 's');

while (count < max) {
  let delay = count/ratio;
  let babyRabbit = rabbit.cloneNode(true) as HTMLElement;
  babyRabbit.style.setProperty('--top', getRandomInt(0, window.innerHeight) + 'px');
  babyRabbit.style.setProperty('--right', getRandomInt(0, maxLeft) + 'px');
  babyRabbit.style.setProperty('--increment', String(increment));
  babyRabbit.style.setProperty('--idle-in-delay', idleInDelay + delay + 's');
  babyRabbit.style.setProperty('--move-delay', moveDelay + delay + 's');
  babyRabbit.style.setProperty('--idle-out-delay', idleOutDelay + delay + 's');
  rabbit.after(babyRabbit);
  count++;
}

rabbit.style.setProperty('filter', 'invert(1)');
rabbit.style.setProperty('pointer-events', 'all');
rabbit.style.setProperty('cursor', 'grab');

rabbit.addEventListener("click", (event) => {
  let burrow = document.querySelector('#rabbitBurrow') as HTMLElement;
  burrow.style.visibility = 'hidden';
});
