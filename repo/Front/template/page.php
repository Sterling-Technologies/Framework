<!DOCTYPE html>
<html class="<?php print $class; ?>">

<head>
	<title><?php $_($title); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<?php if(isset($meta) && is_array($meta)): ?>
    <?php foreach($meta as $name => $content): ?>
    <meta name="<?php print $name; ?>" content="<?php print $content; ?>" />
    <?php endforeach; ?>
    <?php endif; ?>

	<link rel="stylesheet" type="text/css" href="<?php $cdn(); ?>/styles/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php $cdn(); ?>/styles/awesome.css" />
    <link rel="stylesheet" type="text/css" href="<?php $cdn(); ?>/styles/custom.css" />

    <script type="text/javascript" src="<?php $cdn(); ?>/scripts/jquery.js"></script>
    <script type="text/javascript" src="<?php $cdn(); ?>/scripts/bootstrap.js"></script>
</head>

<body>
	<section class="page">
        <section class="head"><?php echo $head; ?></section>
        <section class="body"><?php echo $body; ?></section>
        <section class="foot"><?php echo $foot; ?></section>
    </section>
</body>
</html>
