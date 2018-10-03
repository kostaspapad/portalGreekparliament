import { Pie } from 'vue-chartjs'

export default {
  extends: Pie,
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
    }
  },
  data () {
    return {
      options: {
        // scales: {
        //   yAxes: [{
        //     ticks: {
        //       beginAtZero: true
        //     },
        //     gridLines: {
        //       display: true
        //     }
        //   }],
        //   xAxes: [ {
        //     gridLines: {
        //       display: false
        //     }
        //   }]
        // },
        legend: {
          //display: false
          position: 'top'
        },
        // responsive: true,
        title: {
          display: true,
          text: 'Speeches number per party'
        },
        maintainAspectRatio: false
      },
      datacollection: {
        labels: this.chartLabels,
        datasets: [
          {
            borderColor: 'white',
            pointBackgroundColor: '#1ABC9C',
            borderWidth: 1,
            pointBorderColor: '#1ABC9C',
            backgroundColor: this.chartBgColors,
            data: this.chartData
          }
        ]
      }
    }
  },
  mounted () {
    this.renderChart(this.datacollection, this.options)
  }
}