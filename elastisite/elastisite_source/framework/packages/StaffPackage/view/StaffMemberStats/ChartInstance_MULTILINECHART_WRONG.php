<?php

dump($staffMembersStats);

if (isset($staffMemberStats) && !isset($staffMembersStats)) {
    $staffMembersStats = [];
    $staffMembersStats[0] = $staffMemberStats;
}

$uniqueWeekIds = array();
foreach ($staffMembersStats as $staffMemberId => $staffMemberStats) {
    foreach ($staffMemberStats as $value) {
        $weekId = $value['weekId'];
        if (!in_array($weekId, $uniqueWeekIds)) {
            $uniqueWeekIds[] = $weekId;
        }
    }
}

sort($uniqueWeekIds);

$categoryArrayString = '[';
foreach ($uniqueWeekIds as $weekId) {
    $categoryArrayString .= "'{$weekId}',";
}
$categoryArrayString .= ']';
?>

<div id="ExampleChart"></div>

<!-- <div id="StaffMemberStatChart-<?php echo $chartInstanceId; ?>"></div> -->

<script>
var ExampleChart = {
  draw: function() {
    // Segédfüggvény a random számok generálásához egy adott tartományban
    function getRandomNumber(min, max) {
      return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    // Felhasználók adatai
    var usersData = [
      {
        name: 'Felhasználó 1',
        startWeek: '2023-12',
        points: [17, 19, 16, 12, 10],
        trend: ['increasing', 'increasing', 'decreasing', 'increasing', 'decreasing'],
      },
      {
        name: 'Felhasználó 2',
        startWeek: '2022-51',
        points: [20, 18, 15, 14, 11],
        trend: ['decreasing', 'increasing', 'decreasing', 'increasing', 'decreasing'],
      },
      {
        name: 'Felhasználó 3',
        startWeek: '2023-25',
        points: [8, 12, 14, 15, 17],
        trend: ['increasing', 'increasing', 'increasing', 'decreasing', 'increasing'],
      },
    ];

    // Egyedi hetek meghatározása
    var uniqueWeeks = ['2022-12', '2023-01', '2023-02', '2023-03', '2023-04'];

    var data = usersData.map(function (userData) {
      var color = userData.trend.map(function (t) {
        return t === 'increasing' ? '#00FF00' : '#FF0000';
      });

      return {
        name: userData.name,
        data: uniqueWeeks.map(function (weekId, index) {
          var trendValue = userData.trend[index] === 'increasing' ? userData.points[index] : getRandomNumber(1, 10);
          return { x: weekId, y: trendValue, fillColor: color[index], strokeColor: color[index] };
        }),
      };
    });

    var options = {
      chart: {
        type: 'line',
        height: 800,
      },
      series: data,
      markers: {
        size: 6,
      },
      xaxis: {
        type: 'category',
        categories: uniqueWeeks,
      },
      yaxis: {
        title: {
          text: 'Pontszám',
        },
      },
      legend: {
        position: 'top',
      },
    };

    var chart = new ApexCharts(document.querySelector('#ExampleChart'), options);
    chart.render();
  }
};

ExampleChart.draw();


var StaffMemberStatChart_<?php echo $chartInstanceId; ?> = {
    draw: function() {
        var data = [];
        var annotations = [];
        var categories = <?php echo $categoryArrayString; ?>;
        <?php $index = 0; ?>
        <?php foreach ($staffMembersStats as $staffMemberId => $staffMemberStats): ?>
            <?php $index++; ?>
            let points = [];

            <?php foreach ($staffMemberStats as $value): ?>
                <?php 
                $color = $value['trend'] === 'increasing' ? '#00FF00' : ($value['trend'] === 'decreasing' ? '#FF0000' : '#e8e8e8');
                $colorLight = $value['trend'] === 'increasing' ? '#a3e19d' : ($value['trend'] === 'decreasing' ? '#ecb5c0' : '#e8e8e8');
                ?>

                data.push({ x: '<?php echo $value['weekId']; ?>', y: <?php echo $value['points'] ?: 0; ?>, seriesIndex: <?php echo $index; ?>, color: '<?php echo $color; ?>' });
                annotations.push({ type: 'line', x: '<?php echo $value['weekId']; ?>', seriesIndex: <?php echo $index; ?>, borderColor: '<?php echo $colorLight; ?>', borderWidth: 2 });
            <?php endforeach; ?>
            data.push({
                name: '<?php echo $staffMembers[$staffMemberId]->getPerson()->getFullName(); ?>',
                
            });
        <?php endforeach; ?>

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
                categories: categories,
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




<!-- <pre>
var StaffMemberStatChart_<?php echo $chartInstanceId; ?> = {
    draw: function() {
        var data = [];
        var annotations = [];
        var categories = <?php echo $categoryArrayString; ?>;
        <?php $index = 0; ?>
        <?php foreach ($staffMembersStats as $staffMemberId => $staffMemberStats): ?>
            <?php $index++; ?>
            <?php foreach ($staffMemberStats as $value): ?>
                <?php 
                $color = $value['trend'] === 'increasing' ? '#00FF00' : ($value['trend'] === 'decreasing' ? '#FF0000' : '#e8e8e8');
                $colorLight = $value['trend'] === 'increasing' ? '#a3e19d' : ($value['trend'] === 'decreasing' ? '#ecb5c0' : '#e8e8e8');
                ?>

                data.push({ x: '<?php echo $value['weekId']; ?>', y: <?php echo $value['points'] ?: 0; ?>, seriesIndex: <?php echo $index; ?>, color: '<?php echo $color; ?>' });
                annotations.push({ type: 'line', x: '<?php echo $value['weekId']; ?>', seriesIndex: <?php echo $index; ?>, borderColor: '<?php echo $colorLight; ?>', borderWidth: 2 });
            <?php endforeach; ?>
        <?php endforeach; ?>

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
                categories: categories,
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
</pre> -->
