function element(id) {
  return document.getElementById(id)
}

function createGraph() {
  Highcharts.chart('graphContainer', {
    title: {
      text: 'แนวโน้มจำนวนแต่ละประเภท ตั้งแต่ปี 2561-2563'
    },
    yAxis: {
      title: {
        text: 'จำนวน (คน)'
      }
    },
    xAxis: {
      title: {
        text: 'ปี'
      },
      tickInterval: 1
    },
    legend: {
      layout: 'vertical',
      align: 'right',
      verticalAlign: 'middle'
    },
    plotOptions: {
      series: {
        label: {
          connectorAllowed: false
        },
        pointStart: 2561
      }
    },
    series: [{
      name: 'ทั้งหมด',
      data: [25000, 26500, 24300]
    }, {
      name: 'ทำการคัดกรอง',
      data: [13000, 17000, 18410]
    }, {
      name: 'รับการจัดสรร',
      data: [24300, 18410, 13714]
    }],
    responsive: {
      rules: [{
        condition: {
          maxWidth: 500
        },
        chartOptions: {
          legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'bottom'
          }
        }
      }]
    }
  });
}

function createBar() {
  Highcharts.chart('barContainer', {
    chart: {
      type: 'bar'
    },
    title: {
      text: 'เปรียบเทียบจำนวนแต่ละประเภทตามปี'
    },
    xAxis: {
      categories: ['ทั้งหมด', 'ทำการคัดกรอง', 'รับการจัดสรร'],
      title: {
        text: null
      }
    },
    yAxis: {
      min: 0,
      title: {
        text: 'จำนวน (คน)',
        align: 'high'
      },
      labels: {
        overflow: 'justify'
      }
    },

    plotOptions: {
      bar: {
        dataLabels: {
          enabled: true
        }
      }
    },
    legend: {
      layout: 'vertical',
      align: 'right',
      verticalAlign: 'top',
      x: -40,
      y: 80,
      floating: true,
      borderWidth: 1,
      backgroundColor:
        Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
      shadow: true
    },
    credits: {
      enabled: false
    },
    series: [{
      name: 'ปี 2561',
      data: [25000, 13000, 6500]
    }, {
      name: 'ปี 2562',
      data: [26500, 17000, 9047]
    }, {
      name: 'ปี 2563',
      data: [24300, 18410, 13714]
    },]
  });
}

function createPie() {
  Highcharts.chart('pieContainer', {
    chart: {
      plotBackgroundColor: null,
      plotBorderWidth: null,
      plotShadow: false,
      type: 'pie'
    },
    title: {
      text: 'สัดส่วนจำนวนที่ทำคัดกรอง'
    },
    accessibility: {
      point: {
        valueSuffix: '%'
      }
    },
    plotOptions: {
      pie: {
        allowPointSelect: true,
        cursor: 'pointer',
        dataLabels: {
          enabled: true,
          format: '<b>{point.name}</b>: {point.percentage:.1f} %'
        }
      }
    },
    series: [{
      name: 'Brands',
      colorByPoint: true,
      data: [{
        name: 'ไม่ได้รับจัดสรร',
        y: 61.41,
        sliced: true,
        selected: true
      }, {
        name: 'รับการจัดสรร',
        y: 38.59
      }]
    }]
  });
}

function createTable() {
  let thead = element('thead');
  let tbody = element('tbody');

  function createCol(txt, notheader) {
    let c = document.createElement(notheader == undefined? 'th': 'td');
    c.textContent = txt;
    return c
  }
  function createHeader() {
    thead.appendChild(createCol('เขต'));
    thead.appendChild(createCol('จำนวนทั้งหมด'));
    thead.appendChild(createCol('จำนวนคัดกรอง'));
    thead.appendChild(createCol('จำนวนที่ได้รับจัดสรร'));
  }

  function addBody(d) {
    let tr = document.createElement('tr');
    
    tr.appendChild(createCol(d.name, true))
    tr.appendChild(createCol(d.total, true))
    tr.appendChild(createCol(d.screen, true))
    tr.appendChild(createCol(d.provide, true))

    tbody.appendChild(tr);
  }

  function createBody() {
    addBody({name: 'area1', total: 12121, screen: 8900, provide: 4590});
    addBody({name: 'area2', total: 15132, screen: 9900, provide: 7940});
    addBody({name: 'area3', total: 9121, screen: 6152, provide: 3590});
    addBody({name: 'area4', total: 13451, screen: 10922, provide: 8890});
    addBody({name: 'area5', total: 13151, screen: 5111, provide: 2590});
  }

  createHeader();
  createBody();
}

$(document).ready(function () {
  createGraph();
  createBar();
  createPie();
  createTable();
});