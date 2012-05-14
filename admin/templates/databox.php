
<?php if (count($data) == 1): ?>


<div class="databox">
    <h2><?php echo $title ?></h2>
    <div class="databox-inner">
        <div class="databox-legend cf">
            <span><?php echo $tab_titles[0] ?></span>
        </div>
        <div class="databox-data cf">
            <div class="databox-img <?php echo $img ?>"></div>
            <div class="databox-data-inner">
                <?php echo $tab_data[0] ?>
            </div>
        </div>
    </div>
</div>


<?php else: ?>


<div class="databox">
    <h2><?php echo $title ?></h2>
    <div class="databox-inner">
        <div class="databox-legend cf">
            <ul class="cf">
                <?php foreach($tab_titles as $i => $tab_title): ?>
                    <?php if ($tab_title == "decoy"): ?>
                        <li class="decoy">&nbsp;</li>
                    <?php else: ?>
                        <?php if ($i == 0): ?>
                            <li class="active"><?php echo $tab_title ?></li>
                        <?php else: ?>
                            <li><?php echo $tab_title ?></li>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="databox-data cf">
            <div class="databox-img <?php echo $img ?>"></div>
            <div class="databox-data-inner">
                <?php foreach($tab_data as $i => $value): ?>
                    <?php if ($i == 0): ?>
                        <div><?php echo $value ?></div>
                    <?php else: ?>
                        <div style="display:none;"><?php echo $value ?></div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>


<?php endif; ?>

    