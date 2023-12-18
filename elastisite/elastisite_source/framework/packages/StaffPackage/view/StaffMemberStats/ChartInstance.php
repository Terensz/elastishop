<?php  
// dump($staffMemberStats);
$categoryArrayString = '[';
foreach ($staffMemberStats['categories'] as $weekId) {
    $categoryArrayString .= "'{$weekId}',";
}
$categoryArrayString .= ']';


// dump($categoryArrayString);
// dump($staffMemberStats);exit;
?>

<div id="StaffMemberStatChart-<?php echo $chartInstanceId; ?>" class="apexchart-container">
</div>

<script>
    var StaffMemberStatChart_<?php echo $chartInstanceId; ?> = {
        draw: function() {
            console.log('StaffMemberStatChart_<?php echo $chartInstanceId; ?> draw');
            var seriesRowData = [];
            var annotations = [];
            let dataRow = {};

<?php foreach ($staffMemberStats['seriesData'] as $key => $value): ?>
    <?php 

    $color = $value['trend'] === 'increasing' ? '#00FF00' : ($value['trend'] === 'decreasing' ? '#FF0000' : '#e8e8e8');
    $colorLight = $value['trend'] === 'increasing' ? '#a3e19d' : ($value['trend'] === 'decreasing' ? '#ecb5c0' : '#e8e8e8');
    // $colorLight = $value['trend'] === 'increasing' ? '#a3e19d' : '#ecb5c0';
    ?>

            dataRow = {
                x: '<?php echo $value['weekId']; ?>', 
                y: <?php echo $value['points'] ? : 'null'; ?>, 
                fillColor: '<?php echo $color; ?>'
            };
            // console.log(dataRow);
            seriesRowData.push(dataRow);
            annotations.push({ type: 'line', x: '<?php echo $value['weekId']; ?>', strokeDashArray: 0, borderColor: '<?php echo $colorLight; ?>', borderWidth: 2 });

<?php endforeach; ?>

            var seriesData = [
                {
                    name: '<?php echo trans('points'); ?>',
                    data: seriesRowData,
                },
                // További felhasználók hozzáadása hasonló módon...
            ];

            console.log('seriesData:');
            console.log(seriesData);

            var containerWidth = document.getElementById("StaffMemberStatChart-<?php echo $chartInstanceId; ?>").clientWidth;
            var height = containerWidth / 1.4;

            var options = {
                chart: {
                    type: 'line',
                    width: containerWidth,
                    height: height,
                },
                series: seriesData,
                markers: {
                    size: 6,
                    colors: seriesData.map(function(item) {
                        return item.data.map(function(point) {
                            return point.fillColor;
                        });
                    }),
                },
                point: {
                    colors: seriesData.map(function(item) {
                        return item.data.map(function(point) {
                            return point.fillColor;
                        });
                    }),
                },
                xaxis: {
                    categories: <?php echo $categoryArrayString; ?>
                    // categories: data.map(function (item) {
                    //     return item.weekId;
                    // }),
                },
                yaxis: {
                    min: 0,
                    max: 20,
                    tickAmount: 20
                },
                annotations: {
                    position: 'back',
                    yaxis: [{
                        y: 0,
                        borderColor: '#fff',
                        label: {
                            show: false
                        }
                    }],
                    xaxis: annotations,
                }
            };

            var chart = new ApexCharts(document.querySelector('#StaffMemberStatChart-<?php echo $chartInstanceId; ?>'), options);
            chart.render();
        }
    };
</script>
