<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="identifier-url" content="<?= isset($dIdentifierUrl) ? $dIdentifierUrl : '' ?>">
    <meta name="language" content="<?= isset($dLanguage) ? $dLanguage : '' ?>">
    <meta name="subject" content="<?= isset($dSubject) ? $dSubject : '' ?>">
    <meta name="description" content="<?= isset($dDescription) ? $dDescription : '' ?>">
    <meta name="keywords" content="<?= isset($dKeywords) ? $dKeywords : '' ?>">
    <meta name="robot" content="<?= isset($dRobot) ? $dRobot : 'all' ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Bootstrap css core -->
    <link rel="stylesheet" type="text/css" href="<?= BOOTSTRAP['css']['core'] ?>">
    <!-- Fontawesome -->
    <link rel="stylesheet" type="text/css" href="<?= FONTAWESOME ?>">
    <link rel="stylesheet" type="text/css" href="<?= CSS ?>/app.css">
    <link rel="shortcut icon" href="<?= FAVICON['filename'] ?>" type="<?= FAVICON['type'] ?>">
    <link href="<?= GOOGLE_FONTS['montserrat'] ?>" rel="stylesheet">
    <title><?= isset($dTitle) ? $dTitle : 'Zana' ?></title>
</head>
<body>