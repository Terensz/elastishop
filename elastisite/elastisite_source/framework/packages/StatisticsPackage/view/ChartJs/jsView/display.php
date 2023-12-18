<script>
  var ctx = document.getElementById('myChart').getContext('2d');
  var myChart = new Chart(ctx, {
    type: 'line',
    data: {
      datasets: [
<?php foreach ($charts as $chart): ?>
        {
          label: '<?php echo $chart['label']; ?>',
          data: [
            <?php foreach ($chart['data'] as $chartDataRow): ?>
            { 
                <?php foreach ($chartDataRow as $chartDataRowKey => $chartDataRowValue): ?>
                '<?php $chartDataRowKey; ?>': <?php is_numeric($chartDataRowValue) ? $chartDataRowValue : "'" . $chartDataRowValue . "'"; ?>, 
                <?php endforeach; ?>
            },
            <?php endforeach; ?>
          ],
          borderColor: 'red', // Itt állítod be a vonal színét
          fill: false, // Nem tölti ki a területet a vonal alatt
        },
<?php endforeach; ?>
      ],
    },
    options: {
      // Egyéb beállítások és konfigurációk itt lehetnek
    },
  });
</script>