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
	 <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/jquery.generator.js"></script>
    <script type="text/javascript" src="js/main.js"></script>

	
  </head>
  <body>
<div class="topnav">
	<?php if (!isset($_SESSION['id'])) { ?>
	<button class="btn" id="loginmodal" onclick="window.location='login.php';"><i class="fa fa-sign-in" aria-hidden="true"></i></button>
	<?php } ?>
	<?php if (isset($_SESSION['id'])) { ?>
	<button class="btn" onclick="logOut()" name="logout"><i class=" fa fa-sign-out" aria-hidden="true"></i></button>
	<?php } ?>
</div>
<div id="notification" class="popup">
  <span class="close" onclick="closeNotification()">&times;</span>
  <p>Puzzle saved</p>
</div>
    <section class="inputData">
      <h3>Words</h3>
	  	    <div class="buttonBox" align="center" >
		<button class ="btn" id='add-textbox-btn' name="add"><i class="fa fa-plus"></i>Add</button>
        <button class ="btn" name="generate"><i class="fa fa-check-square"></i>Generate</button>
        <button  class ="btn" name="clear"><i class="fa fa-trash-o"></i>Clear</button>
		<?php
		if (isset($_SESSION['username'])) {
    echo  '<button  class ="btn" onclick="saveWordList()" name="save"><i class="fa fa-floppy-o"></i>Save</button>';
	echo  '<button  class ="btn" onclick="getWordList()" name="load"><i class="fa fa-floppy-o"></i>Load</button>';
	}
	?>
      </div>
      <ul class="inputData inputWords">
        <li class="clear-fix"> 
          <div class="dragHandleWrapper">
            
              <span class="counter"></span>
            
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
		<textarea id="puzzleName" name="puzzleName"  placeholder="Enter puzzle name"></textarea>
      <div class="buttonBox">
	  <button class ="btn" name="printTeacher"><i class="fa fa-print"></i>Print (Teacher Variant)</button>
     <button class ="btn" name="printStudent"><i class="fa fa-print"></i>Print (Student Variant)</button>
      </div>
    </section>
    <section class="clues"></section>
  </body>
</html>
<script>
function showNotification() {
  var notification = document.getElementById("notification");
  notification.style.display = "block";
}

function closeNotification() {
  var notification = document.getElementById("notification");
  notification.style.display = "none";
}
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
	var puzzlenametextbox = document.getElementById("puzzleName");
	var name = puzzlenametextbox.value;
    
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
		showNotification();
        console.log(response);
    }
});
}  

function getWordList() {
  $.ajax({
    type: "POST",
    url: "retrieve.php",
    dataType: "json",
    success: function(data) {
      // Create a table element and append it to a div
      const table = $('<table>').css('padding', '5px');
      const headerRow = $('<tr>');
      const puzzlenameHeader = $('<th>').html('<b>Puzzle Name</b>').css('padding', '5px');
      const wordsHeader = $('<th>').html('<b>Words</b>').css('padding', '5px');
      const cluesHeader = $('<th>').html('<b>Clues</b>').css('padding', '5px');
	const selectHeader = $('<th>').html('<b>Select</b>').css('padding', '5px');
	  const deleteHeader = $('<th>').html('<b>Delete</b>').css('padding', '5px');
      headerRow.append(puzzlenameHeader, wordsHeader, cluesHeader, selectHeader,deleteHeader);
      table.append(headerRow);
      for (let i = 0; i < data.length; i++) {
        const item = data[i];
        const row = $('<tr>');
        const puzzlenameCell = $('<td>').html(item.nameOfPuzzle).css('padding', '5px');
        const wordsCell = $('<td>').html(item.words.join(', ')).css('padding', '5px');
        const cluesCell = $('<td>').html(item.clues.join(', ')).css('padding', '5px');
        const selectCell = $('<td>').css('padding', '5px');
        const selectBox = $('<input>').attr('type', 'checkbox').attr('id', 'select_' + i);
        selectCell.append(selectBox);
		 const idCell = $('<td>').html(item.puzzleId).css('display', 'none');
		 const deleteCell = $('<td>').css('padding', '5px');
        const deleteButton = $('<button>').text('Delete').button();
		     deleteButton.on('click', function() {
          const puzzleId = idCell.html();
          $.ajax({
            type: "POST",
            url: "delete.php",
            data: { puzzleId: puzzleId },
            success: function(data) {
              console.log("Puzzle deleted:", puzzleId);
            },
            error: function(jqXHR, textStatus, errorThrown) {
              console.error("Error deleting puzzle:", textStatus, errorThrown);
            }
          });
          row.remove();
        });
		deleteCell.append(deleteButton);
		
        row.append(puzzlenameCell, wordsCell, cluesCell, selectCell,idCell, deleteCell);
        table.append(row);
      }
      const tableDiv = $('<div>').append(table);

      // Create the dialog box with the table inside
      const dialogContent = $('<div>').append(tableDiv);
      const dialog = dialogContent.dialog({
        title: 'Puzzle Data',
        modal: true,
        width: 'auto',
        height: 'auto',
        close: function() {
          $(this).dialog('destroy').remove();
        }
      });

      // Create a button to copy selected rows
      const copyButton = $('<button>').text('Copy Selected Rows').button();
      copyButton.on('click', function() {
	
		let numWords = 0;
  for (let i = 0; i < data.length; i++) {
    const item = data[i];
    const selectBox = $('#select_' + i);
    if (selectBox.is(':checked')) {
      numWords += item.words.length;
    }
  }

  // Check if the number of words is greater than the number of existing textboxes
  const numTextboxes = $('input[id^="word"]').length;
  if (numWords > numTextboxes) {
    // Trigger a click event on the button that adds additional textboxes
    const diff = numWords - numTextboxes;
for (let i = 0; i < diff; i++) {
    $('#add-textbox-btn').trigger('click');
}
  }
        // Loop through all the rows and copy data for selected ones
        for (let i = 0; i < data.length; i++) {
          const item = data[i];
          const selectBox = $('#select_' + i);
          if (selectBox.is(':checked')) {
            const words = item.words;
            const clues = item.clues;
            for (let j = 0; j < words.length; j++) {
              const $wordInput = $('#word' + (j + 1));
              $wordInput.val(words[j]);
            }
            for (let j = 0; j < clues.length; j++) {
              const $clueInput = $('#clue' + (j + 1));
              $clueInput.val(clues[j]);
            }
          }
        }
		
        dialog.dialog('close');
      });
       
		  
      // Append the copy button to the dialog
      dialogContent.append(copyButton);
	  
	  
	      // Create a share link
      const shareButton = $('<button>').text('Share selected puzzle').button();
      shareButton.on('click', function() {
		    // Loop through all the rows and copy data for selected ones
        for (let i = 0; i < data.length; i++) {
          const item = data[i];
          const selectBox = $('#select_' + i);
          if (selectBox.is(':checked')) {
            const puzzleId = item.puzzleId;
			console.log(puzzleId);
			var url = "http://localhost/individual-project/test.php?puzzleId=" + puzzleId;
			window.open(url);
          }
        }
		  
	  });
	  
	  
	  
	  
		dialogContent.append(shareButton);
      // Open the dialog
      dialog.dialog('open');
    }
	,
    error: function(jqXHR, textStatus, errorThrown) {
      console.error("Error retrieving word list:", textStatus, errorThrown);
    }
  });
}

function deleteRecord(){
		        $.ajax({
            type: "POST",
            url: "test.php",
            data: { puzzleId: puzzleId },
            success: function(data) {
              console.log("Puzzle deleted:", puzzleId);
            },
            error: function(jqXHR, textStatus, errorThrown) {
              console.error("Error deleting puzzle:", textStatus, errorThrown);
            }
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
