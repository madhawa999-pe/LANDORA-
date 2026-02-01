// typingeffect.
const texts = [
  "Smart. Simple. Trusted Real Estate"
];

let count = 0;// which text
let index = 0;// which letter
let currentText = "";// current text
let letter = "";// current letter

function type() {// typing function
  if (count === texts.length) {
    count = 0; // restart the loop
  }
  currentText = texts[count];// get current text
  letter = currentText.slice(0, ++index);// get substring

  document.querySelector(".typing").textContent = letter;// display text

  if (letter.length === currentText.length) { // word complete
    count++;
    index = 0;
    setTimeout(type, 1500); // wait before next word
  } else {
    setTimeout(type, 150); // typing speed
  }
}

type();