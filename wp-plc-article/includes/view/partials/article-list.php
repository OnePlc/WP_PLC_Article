<?php
?>
<h2>Article List</h2>
<ul>
    <?php foreach($aItems as $oItem) { ?>
    <li>
        <?=$oItem->label?>
    </li>
    <?php } ?>
</ul>