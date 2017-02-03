<?php
/**
 * Model
 */
$output = null;
if (isset($_FILES['csv']) && isset($_POST['character'])) {
    $EOLCharacter = $_POST['character'];
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
        if (substr($buffer, -2) != $EOLCharacter . chr(10)) {
            $buffer = str_replace([chr(13), chr(10)], '', $buffer);
            $output .= $buffer;

        } else {
            $output .= $buffer;
        }


    }
    fseek($csvHandle, 0);
    fwrite($csvHandle, $output);
    fclose($csvHandle);

    rename($_FILES['csv']['tmp_name'], 'output/' . $_FILES['csv']['name']);


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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>
<body>
<div class="container">
    <h1>Welcome to CSV File Newline Cleaner</h1>
    <h2>This is a tools to remove newline and carriage return in csv file</h2>
    <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label class="col-sm-2 control-label" for="csv">CSV File</label>
            <div class="col-sm-10">
                <input type="hidden" name="MAX_FILE_SIZE" value="10485760"/>
                <input type="file" name="csv">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="character">Which character is EOL</label>
            <div class="col-sm-10">
                <input type="text" name="character" required="required">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <input class="btn btn-info" type="submit" value="Send">
            </div>
        </div>


    </form>
    <div>
        <?php
        if ($output) {
            echo '<a href="output/' . $_FILES['csv']['name'] . '">Download corrected file</a>';
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
</div>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>