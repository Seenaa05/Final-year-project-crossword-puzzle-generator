<?php
// Initialize the session
session_start();
	if (isset($_SESSION['id'])) {
		  $id = $_SESSION["id"];
	}

?>
<!DOCTYPE html>	
<html>
  <head>
    <meta charset="UTF-8">
	  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	 <link rel="stylesheet" type="text/css" href="css/normalize.css">
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" type="text/css" href="css/main.css" media="all">
    <link rel="stylesheet" type="text/css" href="css/print.css" media="print">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/jquery.generator.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
	 <script type="text/javascript" src="js/modal.js"></script>
	
  </head>
  <body>
<div class="topnav">
	<button class ="btn" id="loginmodal" onclick="window.location='login.php';" ><i class="fa fa-sign-in" aria-hidden="true"></i></button>
	<button class ="btn" name="settings"><i class="fa fa-cog" aria-hidden="true"></i></button>
		<?php
		if (isset($_SESSION['id'])) {
    echo  '<button class ="btn" onclick="logOut()" name="logout"><i class=" fa fa-sign-out" aria-hidden="true"></i></button>';
	}
	?>

</div>
   
    <section class="inputData">
      <h3>Words</h3>
	  	    <div class="buttonBox" align="center" >
		<button class ="btn" name="add"><i class="fa fa-plus"></i>Add</button>
        <button class ="btn" name="generate"><i class="fa fa-check-square"></i>Generate</button>
        <button  class ="btn" name="clear"><i class="fa fa-trash-o"></i>Clear</button>
		<?php
		if (isset($_SESSION['username'])) {
    echo  '<button  class ="btn" onclick="saveWordList()" name="save"><i class="fa fa-floppy-o"></i>Save</button>';
	echo'<form action="retrieve.php" method="post">
  <input type="submit" value="Run Script">
</form>';
	echo  '<button  class ="btn" onclick="getWordList()" name="load"><i class="fa fa-floppy-o"></i>load</button>';
	}
	?>
      </div>
      <ul class="inputData inputWords">
        <li class="clear-fix"> 
          <div class="dragHandleWrapper">
            <div class="dragHandle">
              <span class="counter"></span>
            </div>
          </div>
          <div class="spacer"></div>
          <div class="column word">
            <input id="word1" name="word" type="text" value="Enter" placeholder="Enter a word...">
			  <button class="clickme btn" name="test" ><i class="fa fa-search"></i>WikiSearch</button>
			 
			
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
            <input id="word2" name="word" type="text" value="vsl" placeholder="Enter a word...">
				  	<button class="clickme btn" name="test" ><i class="fa fa-search"></i>WikiSearch</button>
          </div>
          <div class="column removeButton">
	
             <button  class ="btn"name="remove"><i class="fa fa-times-circle"></i></button>
          </div>
        </li>
      </ul>
    </section>

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
	
    <section class="inputData">
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
            <div id="clueSwitch1" class="clueSwitch">
			         <div id="clueSwitch1" class="clueSwitch">
              <label for="clueSwitch1"></label>

			  
            </div>
          </div>
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
            <div id="clueSwitch2" class="clueSwitch">
			  <label for="clueSwitch2">&nbsp;</label>
            </div>
          </div>
        </li>
      </ul>
	  	   <form id = "wikiform" class="search-form js-search-form">
      <input placeholder="Search here" type="search" class="search-input js-search-input" autofocus />
    </form>
	<section class="search-results js-search-results" id="searchresults"></section>
    </section>
	 
	    <section class="printButtons">
      <div class="buttonBox">
	  <button class ="btn" name="printTeacher"><i class="fa fa-print"></i>Print (Teacher Variant)</button>
     <button class ="btn" name="printStudent"><i class="fa fa-print"></i>Print (Student Variant)</button>
      </div>
    </section>
    <section class="clues"></section>


  </body>
</html>
<script>
 function logOut() {
    fetch('logout.php', {
      method: 'post'
    })
      .then(response => {
        // Redirect the user to the login page
        window.location.replace('index.php');
      })
      .catch(error => {
        console.error('Error logging out:', error);
      });
  }
  function saveWordList() {
    var inputs = document.getElementsByTagName('input');
    var words = [];
	var textareas = document.getElementsByTagName('textarea');
	var clues = [];
	var name = 'Anees'
    
    for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].id.startsWith("word")) {
            words.push(inputs[i].value);
        }
    }
	   
    for (var i = 0; i < textareas.length; i++) {
        if (textareas[i].id.startsWith("clue")) {
            clues.push(textareas[i].value);
        }
    }
	console.log(words);
	console.log(clues);
	$.ajax({
    type: "POST",
    url: "insert.php",
    data: {
        words: JSON.stringify(words),
        clues: JSON.stringify(clues),
        name: name
    },
    success: function(response) {
        console.log(response);
    }
});
}  

function getWordList(){
	var userId = 1;
$.ajax({
    type: "POST",
	url: "retrieve.php",
	data: { id: userId },
    dataType: "json",
    success: function(data) {
       console.log(data);
    },
 
});

}
$(document).on('click', '.clickme', function() {
  var word = ($(this).prev().val())
  WikiSearch(word);
});
 function WikiSearch(value) {
var id = value
 console.log(id)
document.querySelector('.js-search-input').value = id
handleSubmit(event);
}
function save(){
console.log(getSelectionText())
document.getElementById("clue1").value = getSelectionText();
}
function getSelectionText() {
    var text = "";
    var activeEl = document.activeElement;
    var activeElTagName = activeEl ? activeEl.tagName.toLowerCase() : null;
    if (
      (activeElTagName == "textarea") || (activeElTagName == "input" &&
      /^(?:text|search|password|tel|url)$/i.test(activeEl.type)) &&
      (typeof activeEl.selectionStart == "number")
    ) {
        text = activeEl.value.slice(activeEl.selectionStart, activeEl.selectionEnd);
    } else if (window.getSelection) {
        text = window.getSelection().toString();
    }
    return text;
}

function addText(elementID) {
	var perm = getSelectionText();
  document.getElementById(elementID).value = perm
};
async function handleSubmit(event) {
  event.preventDefault();
  const inputValue = document.querySelector('.js-search-input').value;
  const searchQuery = inputValue.trim();

  try {
    const results = await searchWikipedia(searchQuery);
	clearWiki();
    displayResults(results);
  } catch (err) {
    console.log(err);
    alert('Failed to search wikipedia');
  }
}
function clearBox(elementID)
{
    document.getElementById(elementID).innerHTML = "";
}
async function searchWikipedia(searchQuery) {
  const endpoint = `https://en.wikipedia.org/w/api.php?action=query&list=search&prop=info&inprop=url&utf8=&format=json&origin=*&srlimit=3&srsearch=${searchQuery}`;
  const response = await fetch(endpoint);
  if (!response.ok) {
    throw Error(response.statusText);
  }
  const json = await response.json();
  return json;
}
function clearWiki(){
var section = document.getElementById("searchresults");
if (section.innerHTML !== "") {
  section.innerHTML = "";
}

}

function displayResults(results) {
  const searchResults = document.querySelector('.js-search-results');

  results.query.search.forEach(result => {
    const url = `https://en.wikipedia.org/?curid=${result.pageid}`;

    searchResults.insertAdjacentHTML(
      'beforeend',
      `<div class="result-item">
        <h3 class="result-title">
          <a href="${url}" target="_blank" rel="noopener">${result.title}</a>
        </h3>
        <a href="${url}" class="result-link" target="_blank" rel="noopener">${url}</a>
        <span class="result-snippet">${result.snippet}</span><br>
      </div>`
    );
  });
}


const form = document.querySelector('.js-search-form');
form.addEventListener('submit', handleSubmit);

</script>
