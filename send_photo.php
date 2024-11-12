<?php
    session_start();

    // if (! isset($_SESSION['sent'])) {
    //     $_SESSION['sent'] = 0;  
    // } else {
    //     if (isset($_POST['form-checker'])) {
    //         $_SESSION['sent']++;
    //     }
    // }

    if (! isset($_SESSION['sent'])) {
        $_SESSION['sent'] = 0;  
    }

    $sent = $_SESSION['sent'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        function exceptionHandlerFormat(Throwable $exception): void
        {
            echo '<div style="border: 2px solid pink; background-color: #fbd6e8; padding: 10px; border-radius: 5px; margin-bottom: 15px;">';
            echo $exception->getMessage();
        }

        set_exception_handler('exceptionHandlerFormat');

        if (isset($_FILES['file']) && ($_FILES['file']['type'] === 'image/png' || $_FILES['file']['type'] === 'image/jpeg') && $_FILES['file']['size'] < 2097152) {
            try {
                move_uploaded_file($_FILES['file']['tmp_name'], './images/' . $_FILES['file']['name']);
                $_SESSION['sent']++;
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            throw new Exception('Поддерживаются файлы только JPG и PNG формата, размер которых не превышает 2 Мбайт');
        }
        // header("Location: ./send_photo.php");
        // exit();

        function exceptionHandler(Throwable $exception): void
        {
            echo '<div style="border: 2px solid pink; background-color: #fbd6e8; padding: 10px; border-radius: 5px; margin-bottom: 15px;">';
            echo $exception->getMessage();
        }

        set_exception_handler('exceptionHandler');

        if (isset($_SESSION['sent']) && $_SESSION['sent'] > 1) {
            unlink('./images/' . $_FILES['file']['name']);
            $_SESSION['sent']--;
            throw new Exception('Количество загруженных файлов не должно быть больше 1');
        }
        header("Location: ./send_photo.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>FILE</title>
    <style>
        body {
            margin: 0;
            background-color: #ffffff;
        }

        .form {
            display: flex;
            flex-direction: column;
            max-width: 600px;
            margin: 0 auto;
            margin-top: 150px;
            padding: 10px;
            border: 1px solid grey;
            border-radius: 10px;
            box-shadow: 0 0 5px grey;
        }
    </style>
</head>
<body>
    <p>Файл отправлялся: <?php echo $sent; ?> раз(а)</p>
    <form class="form" action="./send_photo.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="formFile" class="form-label">Прикрепите файл</label>
            <input class="form-control" type="hidden" name="form-checker"> <br>
            <input class="form-control" type="file" name="file"> <br>
            <input class="btn btn-outline-secondary" type="submit" value="Отправить">
        </div>
    </form>
</body>
</html>