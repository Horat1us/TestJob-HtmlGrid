<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 17.10.16
 * Time: 22:32
 */
$samples = [
    'first', 'second', 'third',
];
$collapseIn = false;
foreach ($samples as $number => $sample):
    $filename = "./samples/{$sample}.php";
    if (!file_exists($filename)) {
        continue;
    }
    ?>
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingOne">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $number; ?>"
                   aria-expanded="true" aria-controls="collapseOne">
                    <input type="radio" name="codeSample" id="codeSample<?php echo $number ?>"
                           value="<?php echo $sample; ?>" <?php echo $collapseIn ? '' : 'checked'?>>
                    <label for="codeSample<?php echo $number ?>">Sample <?php echo $sample; ?> (#<?php echo $number+1; ?>
                        )</label>
                </a>
            </h4>
        </div>
        <div id="collapse<?php echo $number ?>" class="panel-collapse collapse <?php echo $collapseIn ? '' : 'in' ?>"
             role="tabpanel" aria-labelledby="heading<?php echo $sample; ?>">
            <div class="panel-body">
                <?php highlight_file($filename); ?>
            </div>
        </div>
    </div>
    <?php
    $collapseIn = true;
endforeach;