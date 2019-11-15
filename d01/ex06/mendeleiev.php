<?php

$db = fopen('ex06.txt', 'rb');
if ($db === false) {
    return;
}

$elements = [];
while ($str = fgets($db, 1000)) {
    preg_match(
        '/(\w+)\s*=[\s\w]*:\s?(\d+),[\s\w]*:\s?(\d+),[\s\w]*:\s?(\w+),[\s\w]*:\s?([\d\.]+),[\s\w]*:\s?([\d ]+)/',
        $str,
        $element
    );
    $elements[] = $element;
}

usort($elements, static function ($e1, $e2) {
    return $e1[3] <=> $e2[3];
    // return $e1[5] <=> $e2[5];
});
// echo '<pre>'; var_export($elements);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Mendeleiv table</title>
</head>
<body>

<table>
    <tbody>
        <tr>
            <?php foreach ($elements as $element): ?>
                <?php if ($element[2] == 0): ?>
                    <?php unset($last_elem_num) ?>
                    </tr><tr>
                <?php endif ?>

                <?php while (isset($last_elem_num) && ++$last_elem_num < $element[2]): ?>
                    <td style="visibility: hidden"></td>
                <?php endwhile ?>
                <?php $last_elem_num = $element[2] ?>

                <td style="border: 1px solid black; padding: 10px">
                    <h4><?= $element[1] ?></h4>
                    <ul style="padding-inline-start: 20px">
                        <li>No <?= $element[3] ?></li>
                        <li><?= $element[4] ?></li>
                        <li><?= $element[5] ?></li>

                        <?php $electrons = array_sum(explode(' ', $element[6])); ?>
                        <?php if ($electrons > 1): ?>
                            <li><?= $electrons ?> electrons</li>
                        <?php else: ?>
                            <li><?= $electrons ?> electron</li>
                        <?php endif ?>
                    </ul>
                </td>
            <?php endforeach ?>
        </tr>
    </tbody>
</table>

</body>
</html>
