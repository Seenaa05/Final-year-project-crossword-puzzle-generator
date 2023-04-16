<?php
session_start();
 $wordarray = json_decode($_POST['words']);
 $cluearray = json_decode($_POST['clues']);

$id = $_SESSION["id"];
$puzzlename = $_POST['name'];

// connect to the database
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


$sql = "INSERT INTO `crosswordpuzzle` (`id`,`nameOfPuzzle`) VALUES ('$id','$puzzlename')";

if (mysqli_query($conn, $sql)) {
    $puzzleId = mysqli_insert_id($conn);
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

for ($i = 0; $i < count($wordarray); $i++) {
    $word = mysqli_real_escape_string($conn, $wordarray[$i]);
    $clue = mysqli_real_escape_string($conn, $cluearray[$i]);
	var_dump($clue);

    $sql = "INSERT INTO `crosswordcontents` (`puzzleId`, `puzzlewordId`) VALUES ('$puzzleId', NULL)";

    if (mysqli_query($conn, $sql)) {
        $puzzlewordId = mysqli_insert_id($conn);

        $sql = "INSERT INTO `word_clue` (`puzzlewordId`, `word`, `clue`) VALUES ('$puzzlewordId', '$word', '$clue')";

        if (!mysqli_query($conn, $sql)) {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

$conn->close();

?>



