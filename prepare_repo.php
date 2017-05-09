<?php

// Composer's autoloader
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Method to detect the requested URI from server environment variables.
 *
 * @return  string  The requested URI
 *
 * @since   1.0
 */
function detectRequestUri() {

    $input = new \Joomla\Input\Input();
    $_SERVER;
    $serverSSLVar    = $input->server->getString('HTTPS', '');
    $isSslConnection = (!empty($serverSSLVar) && strtolower($serverSSLVar) != 'off');

    $scheme = $isSslConnection ? 'https://' : 'http://';

    $phpSelf    = $input->server->getString('PHP_SELF', '');
    $requestUri = $input->server->getString('REQUEST_URI', '');

    // If PHP_SELF and REQUEST_URI are both populated then we will assume "Apache Mode".
    if (!empty($phpSelf) && !empty($requestUri)) {
        $uri = $scheme . $input->server->getString('HTTP_HOST') . $requestUri;
    } else {
        // IIS uses the SCRIPT_NAME variable instead of a REQUEST_URI variable... thanks, MS
        $uri       = $scheme . $input->server->getString('HTTP_HOST') . $input->server->getString('SCRIPT_NAME');
        $queryHost = $input->server->getString('QUERY_STRING', '');

        // If the QUERY_STRING variable exists append it to the URI string.
        if (!empty($queryHost)) {
            $uri .= '?' . $queryHost;
        }
    }

    return trim($uri);
}

// SSL !!!!!!

$uri = new \Joomla\Uri\Uri(detectRequestUri());

if (!$uri->isSSL()) {
    $uri->setScheme("https");
    header('Location: ' . $uri->toString());
    die;
}

?>
<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Création des labels</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>

<form action="/do_prepare.php" method="post">

    <fieldset>
        <legend>Connexion</legend>
        <input type="password" name="github_token" value="" placeholder="GitHub token">
        <input type="text" name="github_owner" value="jbanety" placeholder="owner">
        <input type="text" name="github_repo" value="" placeholder="repo">
        <input type="password" name="zenhub_token" value="" placeholder="ZenHub token"><br>
        <label for="create_labels"><input type="checkbox" id="create_labels" name="create_labels" value="1" checked> Créer labels</label>
        <label for="create_templates"><input type="checkbox" id="create_templates" name="create_templates" value="1" checked> Créer templates</label>
        <label for="create_issues"><input type="checkbox" id="create_issues" name="create_issues" value="1" checked> Créer issues</label><br>
        <button type="submit">boum!</button>
    </fieldset>

</form>

</body>
</html>
