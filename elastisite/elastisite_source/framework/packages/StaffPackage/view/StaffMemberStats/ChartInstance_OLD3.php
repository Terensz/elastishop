<?php
if (isset($staffMemberStats) && !isset($staffMembersStats)) {
    $staffMembersStats = [];
    $staffMembersStats[0] = $staffMemberStats;
}
// dump($staffMembersStats);
// dump($staffMembers);

// Kategóriák tömbjének létrehozása az 'xaxis' számára
$uniqueWeekIds = array();
foreach ($staffMembersStats as $staffMemberId => $staffMemberStats) {
    foreach ($staffMemberStats as $staffMemberId => $value) {
        $weekId = $value['weekId'];
        if (!in_array($weekId, $uniqueWeekIds)) {
            $uniqueWeekIds[] = $weekId;
        }
        // if (!in_array($weekId, $uniqueWeekIds)) {
        //     $uniqueWeekIds[] = $weekId;
        // }
    }
}

// 'uniqueWeekIds' tömb sorbarendezése
sort($uniqueWeekIds);

// Kategóriák tömbjének JavaScript tömbbe formázása
$categoryArrayString = '[';
foreach ($uniqueWeekIds as $weekId) {
    $categoryArrayString .= "'{$weekId}',";
}
$categoryArrayString .= ']';

// dump($uniqueWeekIds);

?>

<div id="StaffMemberStatChart-<?php echo $chartInstanceId; ?>">

<script>
var StaffMemberStatChart_<?php echo $chartInstanceId; ?> = {
    draw: function() {
        var data = [];
        var annotations = [];
        var categories = [];

        <?php $index = 0; ?>
        <?php foreach ($staffMembersStats as $staffMemberId => $staffMemberStats): ?>
            <?php $index++; ?>
            <?php foreach ($staffMemberStats as $staffMemberId => $value): ?>
                <?php 
                $color = $value['trend'] === 'increasing' ? '#00FF00' : ($value['trend'] === 'decreasing' ? '#FF0000' : '#e8e8e8');
                $colorLight = $value['trend'] === 'increasing' ? '#a3e19d' : ($value['trend'] === 'decreasing' ? '#ecb5c0' : '#e8e8e8');
                ?>

                data.push({ x: '<?php echo $value['weekId']; ?>', y: <?php echo $value['points'] ? : 0; ?>, seriesIndex: <?php echo $index; ?>, color: '<?php echo $color; ?>' });
                annotations.push({ type: 'line', x: '<?php echo $value['weekId']; ?>', seriesIndex: <?php echo $index; ?>, borderColor: '<?php echo $colorLight; ?>', borderWidth: 2 });

            <?php endforeach; ?>
        <?php endforeach; ?>

        var categories = <?php echo $categoryArrayString; ?>;

        var options = {
            chart: {
                type: 'line',
                height: 800,
            },
            series: [
            <?php $index = 0; ?>
            <?php foreach ($staffMembersStats as $staffMemberId => $staffMemberStats): ?>
                <?php $index++; ?>
                    {
                        name: '<?php echo $staffMembers[$staffMemberId]->getPerson()->getFullName(); ?>',
                        data: data.filter(item => item.seriesIndex === <?php echo $index; ?>).map(item => ({ x: item.x, y: item.y, fillColor: item.color, strokeColor: item.color })),
                    },
            <?php endforeach; ?>
            ],
            markers: {
                size: 6,
            },
            xaxis: {
                type: 'category',
                categories: categories, // Kategóriák hozzáadása az x-tengelyhez
            },
            yaxis: {
                title: {
                    text: 'Pontszám',
                },
            },
            legend: {
                position: 'top',
            },
            annotations: {
                position: 'back',
                xaxis: annotations,
            },
        };

        var chart = new ApexCharts(document.querySelector('#StaffMemberStatChart-<?php echo $chartInstanceId; ?>'), options);
        chart.render();
    }
};
</script>



<pre>
    <?php 
    $chartInstanceId++;
    ?>
var StaffMemberStatChart_<?php echo $chartInstanceId; ?> = {
    draw: function() {
        var data = [];
        var annotations = [];
        var categories = [];

        <?php $index = 0; ?>
        <?php foreach ($staffMembersStats as $staffMemberId => $staffMemberStats): ?>
            <?php $index++; ?>
            <?php foreach ($staffMemberStats as $staffMemberId => $value): ?>
                <?php 
                $color = $value['trend'] === 'increasing' ? '#00FF00' : ($value['trend'] === 'decreasing' ? '#FF0000' : '#e8e8e8');
                $colorLight = $value['trend'] === 'increasing' ? '#a3e19d' : ($value['trend'] === 'decreasing' ? '#ecb5c0' : '#e8e8e8');
                ?>

                data.push({ x: '<?php echo $value['weekId']; ?>', y: <?php echo $value['points'] ? : 0; ?>, seriesIndex: <?php echo $index; ?>, color: '<?php echo $color; ?>' });
                annotations.push({ type: 'line', x: '<?php echo $value['weekId']; ?>', seriesIndex: <?php echo $index; ?>, borderColor: '<?php echo $colorLight; ?>', borderWidth: 2 });

            <?php endforeach; ?>
        <?php endforeach; ?>

        var categories = <?php echo $categoryArrayString; ?>;

        var options = {
            chart: {
                type: 'line',
                height: 800,
            },
            series: [
            <?php $index = 0; ?>
            <?php foreach ($staffMembersStats as $staffMemberId => $staffMemberStats): ?>
                <?php $index++; ?>
                    {
                        name: '<?php echo $staffMembers[$staffMemberId]->getPerson()->getFullName(); ?>',
                        data: data.filter(item => item.seriesIndex === <?php echo $index; ?>).map(item => ({ x: item.x, y: item.y, fillColor: item.color, strokeColor: item.color })),
                    },
            <?php endforeach; ?>
            ],
            markers: {
                size: 6,
            },
            xaxis: {
                type: 'category',
                categories: categories, // Kategóriák hozzáadása az x-tengelyhez
            },
            yaxis: {
                title: {
                    text: 'Pontszám',
                },
            },
            legend: {
                position: 'top',
            },
            annotations: {
                position: 'back',
                xaxis: annotations,
            },
        };

        var chart = new ApexCharts(document.querySelector('#StaffMemberStatChart-<?php echo $chartInstanceId; ?>'), options);
        chart.render();
    }
};
</pre>


</div>