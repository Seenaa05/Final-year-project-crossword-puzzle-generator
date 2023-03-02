<?php
$puzzleId = $_POST['puzzleId'];
// Connect to database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "crossword_puzzle_data";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


// SQL query to delete from 'crosswordpuzzle' and 'crosswordcontents'
$sql = "DELETE FROM crosswordpuzzle WHERE puzzleId = $puzzleId;
      DELETE FROM word_clue WHERE puzzleWordId IN (SELECT puzzleWordId FROM crosswordcontents WHERE puzzleId = $puzzleId);
        DELETE FROM crosswordcontents WHERE puzzleId = $puzzleId";
 

// execute the query
if ($conn->multi_query($sql) === TRUE) {
    echo "Records deleted successfully";
} else {
    echo "Error deleting records: " . $conn->error;
}

// close the database connection
$conn->close();
?>
