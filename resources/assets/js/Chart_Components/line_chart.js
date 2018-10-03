import { HorizontalBar } from 'vue-chartjs'

export default {
  extends: HorizontalBar,
  props: {
    chartData: {
      type: Array | Object,
      required: false
    },
    chartLabels: {
      type: Array,
      required: true
    },
    chartBgColors: {
      type: Array,
      required: true
    },
    chartPartyLabels: {
        type: Array,
        required: true
    }
  },
  data () {
    return {
      options: {
        scales: {
          yAxes: [{
            scaleLabel: {
                display: true,
                labelString: 'party'
            },
            ticks: {
                //beginAtZero: true
                // callback: function(label, index, labels) {
                //     console.log(this.chartPartyLabels)
                //     //return this.
                //     // switch (label) {
                //     // case 0:
                //     //     return 'ZERO';
                //     // case 1:
                //     //     return 'ONE';
                //     // case 2:
                //     //     return 'TWO';
                //     // case 3:
                //     //     return 'THREE';
                //     // case 4:
                //     //     return 'FOUR';
                //     // case 5:
                //     //     return 'FIVE';
                //     // }
                // }
            },
            // gridLines: {
            //   display: true
            // }
          }],
          xAxes: [ {
            // ticks: {
            //     beginAtZero: true
            // },
            // gridLines: {
            //   display: false
            // },
            scaleLabel: {
                display: true,
                labelString: 'dates',
                type: 'time'
            }
          }]
        },
        legend: {
          display: false
        },
        //responsive: true,
        height: 200,
        title: {
          display: true,
          text: 'Membership'
        },
        maintainAspectRatio: false
      },
      datacollection: {
        labels: this.chartLabels,
        //labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        datasets: [
            {
                borderColor: 'white',
                pointBackgroundColor: '#1ABC9C',
                borderWidth: 1,
                pointBorderColor: '#1ABC9C',
                backgroundColor: this.chartBgColors,
                data: this.chartData
            }
            // {
            //     // label: 'Data One',
            //     backgroundColor: '#f87979',
            //     pointBackgroundColor: 'white',
            //     borderWidth: 1,
            //     pointBorderColor: '#249EBF',
            //     data: [40, 20, 30, 50, 90, 10, 20, 40, 50, 70, 90, 100]
            // }
        ]
      }
    }
  },
  mounted () {
    this.renderChart(this.datacollection, this.options)
  }
}