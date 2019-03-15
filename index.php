<html>
    <head>
        <title>My Site</title>
    </head>
    <body>
        <!-- Embedding PHP: -->
        <?php $options = ['Dog', 'Cat', 'Ex'] ?>
        <select name="animal">
            <?php foreach($options as $option): ?>
            <!-- Embedding PHP and printing it right away -->
            <option><?= $option ?></option>
            <?php endforeach ?>
        </select>
    </body>
</html>