<?php
// Initialize the session
	if (isset($_GET['puzzleId'])) {
    // Get the puzzleId from the query parameters
    $puzzleId = $_GET['puzzleId'];
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "crossword_puzzle_data";
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	}
// Retrieve puzzles
$sql = "SELECT `nameOfPuzzle` FROM `crosswordpuzzle` WHERE `puzzleId`='$puzzleId'";
$result = mysqli_query($conn, $sql);

if ($result) {
    $puzzles = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $nameOfPuzzle = $row['nameOfPuzzle'];

        // Retrieve puzzle contents
        $sql = "SELECT `word`, `clue` FROM `crosswordcontents` INNER JOIN `word_clue` ON `crosswordcontents`.`puzzlewordId` = `word_clue`.`puzzlewordId` WHERE `crosswordcontents`.`puzzleId`='$puzzleId'";
        $content_result = mysqli_query($conn, $sql);

        if ($content_result) {
            $words = array();
            $clues = array();

            while ($content_row = mysqli_fetch_assoc($content_result)) {
                $words[] = $content_row['word'];
                $clues[] = $content_row['clue'];
            }

            $puzzles[] = array('nameOfPuzzle' => $nameOfPuzzle, 'words' => $words, 'clues' => $clues );
				echo "<script>";
				echo "var words = " . json_encode($words) . ";";
				echo "var clues = " . json_encode($clues) . ";";
				
				echo "</script>";
			
        }
    }



} else {
    echo json_encode(array('error' => 'Error retrieving puzzles from database'));
}
	}

?>
<html>
  <head>
    <meta charset="UTF-8">
	  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	 <link rel="stylesheet" type="text/css" href="css/normalize.css">
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" type="text/css" href="css/main.css" media="all">
    <link rel="stylesheet" type="text/css" href="css/print.css" media="print">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	 <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/jquery.generator.js"></script>
    <script type="text/javascript" src="js/main.js"></script>

	
  </head>
  <h1><?php echo $nameOfPuzzle; ?></h1>
  <body>
  
      <ul id ="test" class="inputData inputWords">
        <li class="clear-fix"> 
          <div class="dragHandleWrapper">
            <div class="dragHandle">
              <span class="counter"></span>
            </div>
          </div>
          <div class="spacer"></div>
          <div class="column word">
            <input id="word1" name="word" type="text" value="Enter" placeholder="Enter a word...">
		
			 
			
          </div>
		    <div class="column removeButton">
            <button  class ="btn"name="remove"><i class="fa fa-times-circle"></i></button>
          </div>

		  
        </li>
        <li class="clear-fix">
          <div class="dragHandleWrapper">
            <div class="dragHandle">
              <span class="counter"></span>
            </div>
          </div>
          <div class="spacer"></div>
          <div class="column word">
            <input id="word2" name="word" type="text" value="Test" placeholder="Enter a word...">
			
          </div>
		    <div class="column removeButton">
            <button  class ="btn"name="remove"><i class="fa fa-times-circle"></i></button>
          </div>
   
        </li>
      </ul>
	  <style>
	  #test {
  display: none;
}
	  </style>
	      <section id ="test2" class="inputData">
      <h3>Clues</h3>
      <ul class="inputData inputClues">
        <li class="clear-fix">
          <div class="dragHandleWrapper">
            <div class="dragHandle">
              <span class="counter"></span>
            </div>
          </div>
          <div class="spacer"></div>
          <div class="column clue">
            <div class="wordCopy">"<span></span>"</div>
            <textarea id="clue1" name="clue" rows="2" placeholder="Enter a clue..."></textarea>

        </li>
        <li class="clear-fix">
          <div class="dragHandleWrapper">
            <div class="dragHandle">
              <span class="counter"></span>
            </div>
          </div>
          <div class="spacer"></div>
          <div class="column clue">
            <div class="wordCopy">"<span></span>"</div>
            <textarea id="clue2" name="clue" rows="2" placeholder="Enter a clue..."></textarea>

          </div>
        </li>
      </ul>
	  	   <form id = "wikiform" class="search-form js-search-form">
      <input placeholder="Search here" type="search" class="search-input js-search-input" autofocus />
    </form>
	<section class="search-results js-search-results" id="searchresults"></section>
    </section>
	<style>
		  #test2 {
  display: none;
}
	</style>
   <button id ="genButton" class ="btn" hidden="hidden" name="generate"><i class="fa fa-check-square"></i>Generate</button>
    <button id ="clueButton" class ="btn" hidden="hidden" name="renderClue"><i class="fa fa-plus"></i>Clue</button>
   <button class ="btn" id='add-textbox-btn' hidden="hidden" name="add"><i class="fa fa-plus"></i>Add</button>
   	<script type="text/javascript" src="js/buttons.js"></script>

   
    <section class="crossword" >
      <div id="placeholderMessage" class="infoMessage">
        <p>
          Your crossword will appear here after you hit the
          <button class ="btn" name="generate"><i class="fa fa-check-square"></i>Generate</button> button.
        </p>
      </div>
      <div id="outdatedMessage" class="infoMessage">
        <p>
          You've changed the set of input words. Hence, the crossword shown below
          is probably outdated. To generate a new crossword, hit the
          <button class ="btn" name="generate"><i class="fa fa-check-square"></i>Generate</button>button.
        </p>
      </div>
      <div id="errorMessage" class="infoMessage">
        <p>
          Cant create valid crossword
        </p>
      </div>
      <div id="crosswordWrapper" class="noSelect clear-fix"></div>
	
    </section>
	<section id ="clues" class="clues"></section>
<div id="notification" class="popup">
  <span class="close" onclick="closeNotification()">&times;</span>
  <p>Congrats</p>
</div>
	</body>
</html>
<script>
window.onload = function() {


  addWords();
  const genbutton = document.getElementById('genButton');
  genbutton.click();
  makeAnswerable();
  addClues();
};
function showNotification() {
  var notification = document.getElementById("notification");
  notification.style.display = "block";
}

function closeNotification() {
  var notification = document.getElementById("notification");
  notification.style.display = "none";
}

function addWords(){
let numWords = words.length;
console.log(numWords);
 // Check if the number of words is greater than the number of existing textboxes
  const numTextboxes = $('input[id^="word"]').length;
  console.log(numTextboxes);
  if (numWords > numTextboxes) {
    // Trigger a click event on the button that adds additional textboxes
    const diff = numWords - numTextboxes;
	const button = document.getElementById('add-textbox-btn');
	for (let i = 0; i < diff; i++) {
    button.click();
	}
  }
 
   for (let j = 0; j < words.length; j++) {
             const $wordInput = $('#word' + (j + 1));
             $wordInput.val(words[j]);
           }
	   for (let j = 0; j < clues.length; j++) {
              const $clueInput = $('#clue' + (j + 1));
              $clueInput.val(clues[j]);
            }
		   
}
		   
function makeAnswerable(){
// Get all the cells with the class "letter"
var letterCells = document.querySelectorAll('.letter');

// Initialize an empty array to store the answers
var answers = [];

// Loop through each letter cell and get its row and cell index
for (var i = 0; i < letterCells.length; i++) {
  var cell = letterCells[i];
  
  // Get the row and cell index of the letter cell
  var rowIndex = cell.parentElement.rowIndex;
  var cellIndex = cell.cellIndex;
  
  // Add the letter, row index, and cell index to the answers array
  answers.push({
    letter: cell.textContent,
    row: rowIndex,
    cell: cellIndex
  });
}

// Do whatever you want with the answers array (e.g. log it to the console)
console.log(answers);



// Get all the <td class="letter"> elements
const letterCellsEmpty = document.querySelectorAll('.letter');

// Loop through each <td class="letter"> element and remove the content of its <div> element
letterCellsEmpty.forEach(cell => {
  cell.querySelector('div').textContent = '';
});


// Get all the <td class="letter"> elements
const letterCellsAnswer = document.querySelectorAll('.letter');

// Initialize the number of correct letters to 0
let numCorrectLetters = 0;

// Loop through each <td class="letter"> element and listen to the input event
letterCellsAnswer.forEach(cell => {
  cell.addEventListener('input', event => {
    const inputtedLetter = event.target.textContent.trim().toUpperCase();
    const row = cell.parentElement.rowIndex;
    const col = cell.cellIndex;
    
    // Find the answer for the current row and column
    const answer = answers.find(a => a.row === row && a.cell === col);
    
    // If there is no answer for the current row and column, exit the function
    if (!answer) return;
    
    if (inputtedLetter === answer.letter.toUpperCase()) {
      // The inputted letter is correct
      cell.style.color = 'green';
	   cell.removeAttribute('contenteditable');
      numCorrectLetters++;
      
      // Check if all letters are correct
      if (numCorrectLetters === letterCellsAnswer.length) {
        
		showNotification();
      }
    } else {
      // The inputted letter is incorrect
      cell.style.color = 'red';
    }
  });
});


var letterCells = document.querySelectorAll('.letter');

// Loop through each letter cell and make it editable
for (var i = 0; i < letterCells.length; i++) {
  var cell = letterCells[i];
  
  // Set the contentEditable property of the cell to true
  cell.contentEditable = true;
}
}
function addClues(){
	// Get a reference to the clues section in the HTML document
	     const cluesSection = document.getElementById('clues');
		 cluesSection.style.display = 'block';
// Loop through the clues array and create HTML elements for each clue
      
      clues.forEach((clue, index) => {
        const clueItem = document.createElement('div');
        clueItem.textContent = `Clue ${index + 1}: ${clue}`;
        cluesSection.appendChild(clueItem);
      });
}
</script>