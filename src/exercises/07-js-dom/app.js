const myBtn = document.getElementById('myButton');
myBtn.addEventListener('click', addParagraph)

function addParagraph() {
  const input = document.getElementById('myInput');
  const p = document.createElement('p');

  p.innerHTML = input.value; 
  document.body.appendChild(p);

}
