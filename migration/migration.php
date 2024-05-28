<?php

global $steps;

addStep('Test', function() {
    echo 'test réussi';
});

dump($_POST, $_GET);

?>

<h1>Migration</h1>

<form action="migration" method="POST">
    <?php if(!empty($steps)): ?>
        <ol>
            <?php foreach($steps as $key => $value): ?>
                <li><input type="checkbox" name="query" value="<?= $key; ?>"><?= $value['label']; ?></li>
            <?php endforeach; ?>
        </ol>
    <?php endif; ?>
    <button onclick="$(this).closest('form').submit();">Lancer la migration</button>
</form>

<?php

function addStep(string $description, $callback)
{
    global $steps;

    if(!isset($steps))
        $steps = array();

    $steps[] = array('label' => $description, 'callback' => $callback);
}

?>