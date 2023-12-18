<div class="card table-card">
<?php 
// dump($keywords);
?>
    <div class="card-header">
        <?php echo trans('most.used.keywords.info'); ?>
    </div>
    <div class="pro-scroll">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover m-b-0">
                    <thead>
                        <tr>
                            <th><?php echo trans('search.word'); ?></th>
                            <th><?php echo trans('search.results'); ?></th>
                            <th><?php echo trans('search.terms'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($keywords as $keyword): ?>
                        <tr>
                            <td><?php echo $keyword['name']; ?></td>
                            <td><?php echo $keyword['count']; ?></td>
                            <td><?php echo str_replace('[separator]', ', ', $keyword['search_string']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
