<div id="<?php echo $chartId; ?>"></div>
    <script>
    var Chart_<?php echo $chartId; ?> = {
        selector: '#<?php echo $chartId; ?>',
        render: function() {
            var chart = new ApexCharts(document.querySelector(this.selector), this.options);
            chart.render();
        },
        // options: {
        //     series: [{
        //         name: "Desktops",
        //         data: [10, 41, 35, 51, 49, 62, 69, 91, 148]
        //     }],
        //     chart: {
        //         height: 350,
        //         type: 'line',
        //         zoom: {
        //             enabled: false
        //         }
        //     },
        //     dataLabels: {
        //         enabled: false
        //     },
        //     stroke: {
        //         curve: 'straight'
        //     },
        //     title: {
        //         text: 'Product Trends by Month',
        //         align: 'left'
        //     },
        //     grid: {
        //         row: {
        //             colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
        //             opacity: 0.5
        //         }
        //     },
        //     xaxis: {
        //         categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
        //     }
        // }
        options: {
          series: [
<?php 
foreach ($chartData['series'] as $seriesLoop):
?>
          {
            name: "<?php echo $seriesLoop['name']; ?>",
            data: [<?php echo implode(',', $seriesLoop['data']); ?>]
          },
<?php 
endforeach;
?>
        ],
        chart: {
          zoom: {
            enabled: false
          },
          selection: {
            enabled: false
          },
          height: 350,
          type: '<?php echo $chartType; ?>',
          dropShadow: {
            enabled: true,
            color: '#000',
            top: 18,
            left: 7,
            blur: 10,
            opacity: 0.2
          },
          toolbar: {
            show: false
          }
        },
        // colors: ['#77B6EA', '#545454'],
        dataLabels: {
          enabled: true,
        },
        stroke: {
          curve: 'smooth'
        },
        title: {
          text: '<?php echo $chartTitle; ?>',
          align: 'left'
        },
        grid: {
          borderColor: '#e7e7e7',
          row: {
            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5
          },
        },
        markers: {
          size: 1
        },
        xaxis: {
          categories: [<?php echo implode(',', $chartData['categoryValues']); ?>],
          title: {
            text: '<?php echo $categoryAxisTitle; ?>'
          }
        },
        yaxis: {
          title: {
            text: '<?php echo $valueAxisTitle; ?>'
          },
          min: 1,
          // max: 40
        },
        legend: {
          position: 'top',
          horizontalAlign: 'right',
          floating: true,
          offsetY: -25,
          offsetX: -5
        }
      }
    };
    Chart_<?php echo $chartId; ?>.render();
        // var options = {
        //   series: [{
        //     name: "Desktops",
        //     data: [10, 41, 35, 51, 49, 62, 69, 91, 148]
        //   }],
        //   chart: {
        //   height: 350,
        //   type: 'line',
        //   zoom: {
        //     enabled: false
        //   }
        // },
        // dataLabels: {
        //   enabled: false
        // },
        // stroke: {
        //   curve: 'straight'
        // },
        // title: {
        //   text: 'Product Trends by Month',
        //   align: 'left'
        // },
        // grid: {
        //   row: {
        //     colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
        //     opacity: 0.5
        //   },
        // },
        // xaxis: {
        //   categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
        // }
        // };

        // var chart = new ApexCharts(document.querySelector("#chart"), options);
        // chart.render();
</script>