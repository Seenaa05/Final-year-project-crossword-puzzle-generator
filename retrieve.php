<?php
session_start();
$id = $_SESSION["id"];
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



// Retrieve puzzles
$sql = "SELECT `nameOfPuzzle`, `puzzleId` FROM `crosswordpuzzle` WHERE `id`='$id'";
$result = mysqli_query($conn, $sql);

if ($result) {
    $puzzles = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $puzzleId = $row['puzzleId'];
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

            $puzzles[] = array('nameOfPuzzle' => $nameOfPuzzle, 'words' => $words, 'clues' => $clues);
        }
    }

    echo json_encode($puzzles);

} else {
    echo json_encode(array('error' => 'Error retrieving puzzles from database'));
}
$conn->close();
?>