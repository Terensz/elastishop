<?php
if (isset($staffMemberStats) && !isset($staffMembersStats)) {
    $staffMembersStats = [];
    $staffMembersStats[0] = $staffMemberStats;
}
// dump($staffMembersStats);
// dump($staffMembers);
?>



<div id="StaffMemberStatChart-<?php echo $chartInstanceId; ?>">

<pre>
<script>
    var StaffMemberStatChart_<?php echo $chartInstanceId; ?> = {
        draw: function() {
            var data = [];
            var annotations = [];
            var categories = [];

            <?php $index = 0; ?>
            <?php foreach ($staffMembersStats as $staffMemberId => $staffMemberStats): ?>
                <?php $index++; ?>
                <?php $uniqueWeekIds = []; ?>
                <?php foreach ($staffMemberStats as $staffMemberId => $value): ?>
                    <?php 
                    $color = $value['trend'] === 'increasing' ? '#00FF00' : ($value['trend'] === 'decreasing' ? '#FF0000' : '#e8e8e8');
                    $colorLight = $value['trend'] === 'increasing' ? '#a3e19d' : ($value['trend'] === 'decreasing' ? '#ecb5c0' : '#e8e8e8');
                    // $colorLight = $value['trend'] === 'increasing' ? '#a3e19d' : '#ecb5c0';
                    ?>

                    data.push({ x: '<?php echo $value['weekId']; ?>', y: <?php echo $value['points'] ? : 0; ?>, seriesIndex: <?php echo $index; ?>, color: '<?php echo $color; ?>' });     
                    annotations.push({ type: 'line', x: '<?php echo $value['weekId']; ?>', seriesIndex: <?php echo $index; ?>, borderColor: '<?php echo $colorLight; ?>', borderWidth: 2 });

                    if (!in_array($value['weekId'], $uniqueWeekIds)) {

                <?php endforeach; ?>
            <?php endforeach; ?>

            // console.log('data:');
            // console.log(data);

            var options = {
                chart: {
                    type: 'line',
                    height: 800,
                },
                series: [
                <?php $index = 0; ?>
                <?php foreach ($staffMembersStats as $staffMemberId => $staffMemberStats): ?>
                    <?php  
                    // dump($staffMembers[$staffMemberId]->getPerson());  
                    ?>
                    <?php $index++; ?>
                        {
                            name: '<?php echo $staffMembers[$staffMemberId]->getPerson()->getFullName(); ?>',
                            data: data.filter(item => item.seriesIndex === <?php echo $index; ?>).map(item => ({ x: item.x, y: item.y })),
                        },
                <?php endforeach; ?>
                ],
                markers: {
                    size: 6,
                    // colors: data.map(item => item.color), // Színezett sarokpontok
                    colors: data.map(function (item, index) {
                        // var color = item.trend === 'increasing' ? '#00FF00' : '#FF0000';
                        var color = item.color;
                        console.log(color, '#00FF00', index);

                        return color;
                    }),
                },
                xaxis: {
                    type: 'category',
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
</div>