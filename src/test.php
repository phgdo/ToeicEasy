<form method="post">
    <?php
    for($i=1;$i<=5;$i++)
    {
        ?>
        <div class="well well-sm well-primary">
            <input type="hidden" name="ques"/>Questions?
        </div>
        <div class="well well-sm">
            <div class="radio">
                <label>
                <input type="radio" name="optradio[<?php echo $i; ?>]" value="a">Option 1</label>
            </div>
            <div class="radio">
                <label>
                <input type="radio" name="optradio[<?php echo $i; ?>]" value="b">Option 2</label>
            </div>
            <div class="radio">
                <label>
                <input type="radio" name="optradio[<?php echo $i; ?>]" value="c">Option 3</label>
            </div>
        </div>
        <?php
    }
    ?>
    <button type="submit" class="btn btn-success" name="submit">Finish</button>
</form>
<?php 
 foreach($_POST['optradio'] as $option_num => $option_val)
    echo $option_num." ".$option_val."<br>";
?>