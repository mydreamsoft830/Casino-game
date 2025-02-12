<?php
if (file_exists(__DIR__ . '/storage/installed')) {
    header('Location: /');
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', TRUE);

$errors = [];

if (!version_compare(phpversion(), '7.3.0', 'ge'))
    $errors[] = 'PHP <b>7.3.0</b> or higher should be installed.';

foreach(['openssl','pdo','mbstring','tokenizer','xml','curl','ctype','fileinfo','json','bcmath','zip'] as $extension)
    if (!extension_loaded($extension))
        $errors[] = sprintf('PHP <b>%s</b> extension should be installed and enabled.', $extension);

foreach(['public',
        'storage',
        'storage/app/public',
        'storage/framework',
        'storage/framework/cache',
        'storage/framework/sessions',
        'storage/framework/views',
        'storage/logs',
        'bootstrap/cache'] as $folder)
    if (!is_writable(__DIR__ . DIRECTORY_SEPARATOR . $folder))
        $errors[] = sprintf('Folder <b>%s</b> should be writable', $folder);

if (!is_writable(__DIR__))
    $errors[] = 'Folder <b>..</b> (web root) should be writable';

if (!file_exists('.env.install'))
    $errors[] = 'Please check that <b>.env.install</b> file exists in the web root folder.';

if (in_array('set_time_limit', explode(',', ini_get('disable_functions'))))
    $errors[] = 'PHP function <b>set_time_limit</b> should be enabled.';

if (empty($errors)) {
    header('Location: /install/1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Installation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body class="theme-dark-purple">
    <div class="container">
        <div class="row mt-3 mb-3">
            <div class="col">
                <h1 class="border-bottom border-light">Installation</h1>
            </div>
        </div>
        <div class="row mt-3 mb-3">
            <div class="col">
                <h2><i class="fas fa-cogs"></i> System requirements</h2>
                <div class="alert alert-danger">
                    <h4 class="alert-heading">
                        Please resolve the following issues before installing the application:
                    </h4>
                    <ul>
                        <?php foreach ($errors as $error):?>
                            <li><?php print $error?></li>
                        <?php endforeach;?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
