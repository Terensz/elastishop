<!-- <div class="widgetWrapper"> -->
<?php
$currentPath = __DIR__;
$parentPath = dirname($currentPath);
$pathToStaffMemberStatsDir = $parentPath . '/../StaffMemberStats/';
$chartInstanceId = 1;

// dump($staffMembersStats);
$chartInstanceIds = [];
foreach ($staffMembersStats as $staffMemberId => $staffMemberStats):
    $chartInstanceIds[] = $staffMemberId;
    $chartInstanceId = $staffMemberId;
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="card-header-textContainer">
            <h6 class="mb-0"><?php echo $staffMembers[$staffMemberId]->getPerson()->getFullName(); ?></h6>
        </div>
    </div>
    <div class="card-body" style="width: 99%;">
        <?php
        include($pathToStaffMemberStatsDir.'ChartInstance.php');
        ?>
    </div>
</div>

<!-- <div class="article-title"><?php echo $staffMembers[$staffMemberId]->getPerson()->getFullName(); ?></div> -->
<?php 
// dump('ALMA!!');exit;
endforeach;
?>


<div id="TestChart" class="apexchart-container">
</div>

<!-- <script>
    var TestChart = {
        draw: function() {
            var seriesData = [
                {
                    name: 'Felhasználó 1',
                    data: [
                        { x: '2023-02', y: null, fillColor: '#e8e8e8' }, // Null érték az üres helyett
                        { x: '2023-03', y: 8, fillColor: '#FF0000' },
                        { x: '2023-04', y: 12, fillColor: '#00FF00' },
                        { x: '2023-05', y: 5, fillColor: '#FF0000' },
                        { x: '2023-06', y: null, fillColor: '#e8e8e8' }, // Null érték az üres helyett
                        // További hetek hozzáadása hasonló módon...
                    ],
                },
                // További felhasználók hozzáadása hasonló módon...
            ];

            var annotations = [];
            var categories = ['2023-02', '2023-03', '2023-04', '2023-05', '2023-06'];

            var containerWidth = document.getElementById("TestChart").clientWidth;
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
                    categories: categories,
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

            var chart = new ApexCharts(document.querySelector('#TestChart'), options);
            chart.render();
        }
    };
</script> -->

<script>
    $('document').ready(function() {
        <?php foreach ($chartInstanceIds as $chartInstanceId): ?>
        StaffMemberStatChart_<?php echo $chartInstanceId; ?>.draw();
        <?php endforeach; ?>
        // TestChart.draw();
    });
</script>
<!-- </div> -->