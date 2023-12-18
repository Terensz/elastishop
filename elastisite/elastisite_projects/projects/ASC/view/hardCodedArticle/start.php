<style>
.articleStartWrapper {
    background-color: #5867a2 !important;
    color: #fff;
}

.articleStartLink:link {
    color: #a8b8c4;
}
.articleStartLink:visited {
    color: #a8b8c4;
}
</style>
<div class="article-wrapper articleStartWrapper">
<?php
// $container->getRequest()->get('teaser')
if ($container->getRequest()->get('teaser')) {
?>
    <div id="articleContainer_notFound" class="article-container">
        <div class="article-head">
            <div class="article-info"></div>
            <div class="article-title">ElastiSite: Na, már megint egy keretrendszer...</div>
        </div>
        <div class="article-teaser"><i>Jó, de akkor legyen könnyedebb, gyorsabb, stabilabb!</i></div>
        <div class="articleFooter">
            <a class="ajaxCallerLink articleStartLink" href="<?php echo $container->getUrl()->getHttpDomain().'/article/'.$container->getRequest()->get('slug'); ?>"><?php echo trans('read.more'); ?></a>
        </div>
    </div>
<?php
} else {
?>
    <div id="articleContainer_notFound" class="article-container">
        <div class="article-head">
            <div class="article-info"></div>
            <div class="article-title">ElastiSite: lázadás a webfejlesztői trendek ellen</div>
        </div>
        <div class="article-teaser">Másfél éve használok egy népszerű webfejlesztői keretrendszert.
            Szeretek vele dolgozni, rengeteg előnyét
            tudnám felsorolni. Mellétéve számos negatívumot, mint pl. az erőforrás-pazarlás, a teljes hiánya a
            dinamikus elemek (widgetek) kezelésének, a kérdőív-kezelés indokolatlan túlbonyolítása, a könnyű hibakeresés
            miatti tövábbi erőforrás-zabálás azért, hogy a legnagyobb bajban egy oda nem illő, vagy semmitmondó hibaüzenetet kapjunk.<br><br>
            Történt egyszer, hogy a legnagyobb tanítómesterem füle hallatára élesen bíráltam a keretrendszert, mire ő csak ennyit mondott:
            "Terence, írj jobbat." Mire én: "Fogd meg a söröm."<br><br>
            Az ElastiSite motorban egyesítettem mindent, amit szeretek a keretrendszerekben, és mindent kihagytam, amit
            nem tartok jónak, fölöslegesen lassítják a működést, vagy túlbonyolítják a programot.
            Tettem hozzá pár saját ötletet, amiket hiányoltam, hogy lefektethessem egy képernyő-újratöltés nélküli,
            teljesen dinamikus, biztonságos, könnyen fejleszthető keretrendszer stabil alapjait.
        </div>
    </div>
<?php
}
?>
</div>
