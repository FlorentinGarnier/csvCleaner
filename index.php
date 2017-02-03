<?php
/**
 * Model
 */
$csv = null;
$output = null;
if (isset($_FILES['csv'])) {
    //Errors
    if ($_FILES['csv']['error'] > 0) {
        switch ($_FILES['csv']['error']) {
            case 1:
                echo 'La taille du fichier téléchargé est supérieur à ' . ini_get('upload_max_filesize');
                break;
            case 2:
                echo 'La taille du fichier téléchargé est supérieur à ' . $_POST['MAX_FILE_SIZE'];
                break;
            case 3:
                echo 'Le fichier n\'a été que partiellement téléchargé.';
                break;
            case 4:
                echo 'Aucun fichier n\'a été téléchargé.';
                break;
            case 6:
                echo 'Un dossier temporaire est manquant.';
                break;
            case 7:
                echo 'Échec de l\'écriture du fichier sur le disque';
                break;
            case 8:
                echo 'Une extension PHP a arrêté l\'envoi de fichier';
        }

    }



    $csvHandle = fopen($_FILES['csv']['tmp_name'], 'r+');
    while ($buffer = fgets($csvHandle)) {
        if (substr($buffer, -2) != '"'.chr(10)) {
            $buffer = str_replace([chr(13), chr(10)], '', $buffer);
            $output .= $buffer;

        } else {
            $output .= $buffer;
        }



    }
    fseek($csvHandle, 0);
    fwrite($csvHandle, $output);
    fclose($csvHandle);

    rename($_FILES['csv']['tmp_name'], 'output/'. $_FILES['csv']['name']);


}
/**
 * Vue
 */
?>

<!doctype html>
<html lang="en">
<head>

    <title>CSV File Newline Cleaner</title>
    <meta charset="utf-8">
</head>
<body>
<h1>Welcome to CSV File Newline Cleaner</h1>
<h2>This is a tools to remove newline and carriage return in csv file</h2>
<form action="" method="post" enctype="multipart/form-data">
    <label for="csv">CSV File</label>
    <input type="hidden" name="MAX_FILE_SIZE" value="10485760"/>
    <input type="file" name="csv"><br>
    <input type="submit" value="Send">
</form>
<div>
    <?php
    if ($output) {
        echo '<a href="output/'.$_FILES['csv']['name'].'">Download corrected file</a>';
    }
    ?>
</div>
<footer>
    <p>
        Florentin Garnier
        - <a href="mailto:garnier.florentin@gmail.com">garnier.florentin@gmail.com</a>
        - <a href="https://twitter.com/garnierfl">Twitter</a>
        - <a href="https://github.com/florentingarnier">Github</a>
    </p>
</footer>
</body>
</html>