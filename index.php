<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/autoload.php';

\Tracy\Debugger::enable(\Tracy\Debugger::DEVELOPMENT, __DIR__ . '/log');

$grid = new \Chess\Src\Grid();

/** @var \Chess\Src\GridPosition[]|null $shortestPath */
$shortestPath = null;
$error = null;

/** @var \Chess\Src\Figure\IFigure[] $figures */
$figures = [
    'horse' => new \Chess\Src\Figure\Horse(),
    'tower' => new \Chess\Src\Figure\Tower(),
    'ranger' => new \Chess\Src\Figure\Ranger(),
];

if(!empty($_POST['do']) && $_POST['do'] === 'calculateShortestPath') {

    try {
        $currentPosition = new \Chess\Src\GridPosition($_POST['fromPosX'],$_POST['fromPosY']);
        $wantedPosition = new \Chess\Src\GridPosition($_POST['toPosX'],$_POST['toPosY']);
        $figure = $figures[$_POST['figure']];

        $shortestPath = $grid->getShortestPath($currentPosition, $wantedPosition, $figure);
    } catch (\Exception $e) {
        $error = $e->getMessage();
    }

}

?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Chess</title>
</head>
<body>

<form method="post" style="margin-bottom: 25px;">
    <input type="hidden" name="do" value="calculateShortestPath">
    <div style="margin-bottom: 15px; margin-top: 50px; text-align: center;">
        <label style="display:inline-block; width: 50px;">From: </label>
        <select name="fromPosX">
            <?php
            for ($x = 1; $x <= \Chess\Src\Grid::getMaxX();$x++) {
                $alphabetical = \Chess\Src\Utils\Helpers::toAlphabetical($x);
                echo '<option value="' . $alphabetical . '" ' . (!empty($_POST['fromPosX']) && $_POST['fromPosX'] === $alphabetical ? 'selected' :'') . '>' . $alphabetical . '</option>';
            }
            ?>
        </select>
        <select name="fromPosY">
            <?php
            for ($x = 1; $x <= \Chess\Src\Grid::getMaxY();$x++) {
                echo '<option value="' . $x . '" ' . (!empty($_POST['fromPosY']) && (int)$_POST['fromPosY'] === $x ? 'selected' :'') . '>' . $x . '</option>>';
            }
            ?>
        </select>
    </div>
    <div style="margin-bottom: 15px; text-align: center;">
        <label style="display: inline-block; width: 50px;">To: </label>
        <select name="toPosX">
            <?php
            for ($x = 1; $x <= \Chess\Src\Grid::getMaxX();$x++) {
                $alphabetical = \Chess\Src\Utils\Helpers::toAlphabetical($x);
                echo '<option value="' . $alphabetical . '" ' . (!empty($_POST['toPosX']) && $_POST['toPosX'] === $alphabetical ? 'selected' :'') . '>' . $alphabetical . '</option>>';
            }
            ?>
        </select>
        <select name="toPosY">
            <?php
            for ($x = 1; $x <= \Chess\Src\Grid::getMaxY();$x++) {
                echo '<option value="' . $x . '" ' . (!empty($_POST['toPosY']) && (int)$_POST['toPosY'] === $x ? 'selected' :'') . '>' . $x . '</option>>';
            }
            ?>
        </select>
    </div>
    <div style="margin-bottom: 15px; text-align: center;">
        <label style="display: inline-block; width: 50px;">Figure: </label>
        <select name="figure">
            <?php
            foreach ($figures as $figureIndex => $figure) {
                echo '<option value="' . $figureIndex . '" ' . (!empty($_POST['figure']) && $_POST['figure'] === $figureIndex ? 'selected' :'') . '>' . $figureIndex . '</option>>';
            }
            ?>
        </select>
    </div>
    <div style="text-align: center;">
        <button type="submit">Calculate</button>
    </div>
</form>

<?php
    if($shortestPath) {
        $out = [];
        foreach ($shortestPath as $gridMove) {
            $out[] = '<div style="margin-bottom: 10px; font-weight: bold; text-align: center;">';
            $out[] = $gridMove->getX() . ' ' . $gridMove->getY();
            $out[] = '</div>';
        }

        echo implode('', $out);
    }
    if($error) {
        echo '<div style="margin-bottom: 10px; font-weight: bold; text-align: center; color: red;">' . $error . '</div>';
    }
?>

</body>
</html>
