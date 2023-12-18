<div class="text-title">Háttér adatai</div>
<div class="text-body">Válaszd ki a háttér típusát és nevezd el a hátteret</div>
<br>
<img src="/<?php echo $thumbPath; ?>"> <b><?php echo $imageWidth; ?> x <?php echo $imageHeight; ?></b>
<br>
<br>
<form name="BackgroundPackage_newFBSBackground_form" id="BackgroundPackage_newFBSBackground_form" method="POST" action="" enctype="multipart/form-data">
    <label for="bgEngineContainer">Háttér típusa</label>
    <div class="inputGroupContainer" id="bgEngineContainer">
        <input type="radio" id="BackgroundPackage_newFBSBackground_engine_simple"
            name="BackgroundPackage_newFBSBackground_engine" value="Simple" checked> Egyszerű
        <input type="radio" id="BackgroundPackage_newFBSBackground_engine_slidingStripes"
            name="BackgroundPackage_newFBSBackground_engine"
            value="SlidingStripes"<?php echo $form->getValueCollector()->getDisplayed('engine') == 'SlidingStripes' ? ' checked' : ''; ?>> Beúszó csíkok
    </div>
    <div class="form-group">
        <label for="BackgroundPackage_newFBSBackground_theme">Háttér neve</label>
        <input id="BackgroundPackage_newFBSBackground_theme" name="BackgroundPackage_newFBSBackground_theme"
        class="inputField form-control" type="text" value="<?php echo $form->getValueCollector()->getDisplayed('theme'); ?>" size="30" maxlength="100"
        aria-describedby="email-notes" required="required">
    </div>
    <br>
    <button id="BackgroundPackage_newFBSBackground_save" onclick="BackgroundEdit.save();" style="width: 200px;" class="btn btn-secondary">Mentés</button>
    <button id="BackgroundPackage_newFBSBackground_reset" class="btn btn-danger">Másik fotót töltök fel</button>

</form>
<script>
$(document).ready(function() {
    $('#BackgroundPackage_newFBSBackground_reset').click(function() {
        BackgroundEdit.reset();
    });

    $('#BackgroundPackage_newFBSBackground_save').click(function(e) {
        console.log('BackgroundPackage_newFBSBackground_save click func');
        e.preventDefault();
    });
});
</script>
