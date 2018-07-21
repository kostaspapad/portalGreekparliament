<canvas id="myChart" width="400" height="100"></canvas>
<script>
$(function () {
    var ctx = document.getElementById("myChart").getContext('2d');
    var data = {!! json_encode($data->toArray()) !!};
    console.log(data);
    
    var labels = data.map(function(e) {
        return e.speaker_id;
    });

    var counts = data.map(function(e) {
        return e.total;
    });
    console.log(labels);
    console.log(counts);

    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Speeches count for every speaker',
                data: counts,
                backgroundColor: 'rgba(0, 119, 204, 0.3)'
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
});
</script>