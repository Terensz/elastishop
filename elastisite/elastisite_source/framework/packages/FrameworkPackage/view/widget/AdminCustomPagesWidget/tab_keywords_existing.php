<?php if (count($pageKeywords) > 0): ?>
<div class="" style="overflow: hidden;">
<?php foreach ($pageKeywords as $pageKeyword): ?>
    <div class="alert alert-success">
        <div class="tag">
            <table style="width: 100%;">
                <tbody>
                    <tr>
                        <td>
                            <?php echo $pageKeyword->getName(); ?>
                        </td>
                        <td style="width: 30px; text-align: center">
                            <a class="ajaxCallerLink" href="" onclick="Keywords.deleteKeyword(event, <?php echo $pageKeyword->getId(); ?>);">X</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php endforeach; ?>
</div>
<?php endif; ?>