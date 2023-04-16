function addWordClickHandler(/*event*/) {
  const wordLi = document.querySelector('ul.inputWords li:last-child').cloneNode(true);
  wordLi.querySelector('button[name=remove]').addEventListener('click', removeWordClickHandler);
  
  const input = wordLi.querySelector('input');
  input.value = '';
  input.addEventListener('change', wordChangeHandler);
  
  
  const clueLi = document.querySelector('ul.inputClues li:last-child').cloneNode(true);
  
  const textarea = clueLi.querySelector('textarea');
  textarea.innerHTML = '';
  
  const updateId = function(element) {
    const id = element.getAttribute('id');
    
    const numberMatch = id.match(/\d+/);
    
    const prefix = id.slice(0, numberMatch.index);
    const number = parseInt(id.slice(numberMatch.index));
    
    element.setAttribute('id', prefix + (number + 1));
  };
  
  updateId(input);
  updateId(textarea);
  
  document.querySelector('ul.inputWords').appendChild(wordLi);
  document.querySelector('ul.inputClues').appendChild(clueLi);
}

function removeWordClickHandler(event) {
  console.log("Removed word");
  let parent = event.currentTarget.parentNode;
  
  while (!parent.parentNode.classList.contains('inputWords')) {
    parent = parent.parentNode;
  }
  
  const index = Array.from(document.querySelectorAll('ul.inputWords li')).indexOf(parent);
  
  parent.remove();
  
  document.querySelector(`ul.inputClues li:nth-child(${index + 1})`).remove();
}
function wordChangeHandler(event) {
  document.getElementById('outdatedMessage').style.display = 'block';

  var parent = this;
  while (parent && !parent.matches('li')) {
    parent = parent.parentNode;
  }

  var index = Array.prototype.indexOf.call(
    document.querySelectorAll('ul.inputWords li'),
    parent
  );

  document.querySelector(
    'ul.inputClues li:nth-child(' + (index + 1) + ') .wordCopy span'
  ).textContent = this.value;
}

var inputWords = document.querySelectorAll('ul.inputWords input[type=text]');
for (var i = 0; i < inputWords.length; i++) {
  inputWords[i].addEventListener('input', wordChangeHandler);
}

const addButton = document.querySelector('#add-textbox-btn');
addButton.addEventListener('click', addWordClickHandler);



function printCrossword(forStudent) {
	var puzzleName = document.querySelector('#puzzleName').value.trim();
	document.querySelector('#printHeader').textContent = puzzleName;
  var crosswordTable = document.querySelector('.crossword');
  var marginLeft = window.getComputedStyle(crosswordTable).getPropertyValue('margin-left');
  crosswordTable.style.marginLeft = '-200px';

  if (forStudent) { // Hide crossword letters
    var letters = crosswordTable.querySelectorAll('.letter div');
    for (var i = 0; i < letters.length; i++) {
      letters[i].style.display = 'none';
    }
  }

  // Render clues
  var clues = [];
  var numClues = document.querySelectorAll('textarea[name^=clue]').length;
  for (var i = 1; i <= numClues; i++) {
    var clueTextArea = document.querySelector('#clue' + i);
    var clueText = clueTextArea.value.trim();
    clues.push(clueText);
  }

  // Add clues section to the crossword wrapper
  var crosswordWrapper = crosswordTable.parentNode;
  var cluesHtml = '<ul class="clues"><li>' + clues.join('</li><li>') + '</li></ul>';
  crosswordWrapper.insertAdjacentHTML('beforeend', cluesHtml);

  // Print the crossword and clues
  window.print();
  crosswordTable.style.marginLeft = marginLeft;

  if (forStudent) { // Show crossword letters
    for (var i = 0; i < letters.length; i++) {
      letters[i].style.display = 'block';
    }
  }

  // Remove the clues section from the crossword wrapper
  crosswordWrapper.removeChild(crosswordWrapper.lastChild);
}
// Get the print buttons
var printTeacherBtn = document.querySelector('button[name=printTeacher]');
var printStudentBtn = document.querySelector('button[name=printStudent]');

// Add event listeners to the print buttons
printTeacherBtn.addEventListener('click', function() {
  // Call the printCrossword function with the forStudent parameter set to false
  printCrossword(false);
});

printStudentBtn.addEventListener('click', function() {
  // Call the printCrossword function with the forStudent parameter set to true
  printCrossword(true);
});






