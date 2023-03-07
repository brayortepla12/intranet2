app.config(['ChartJsProvider', function (ChartJsProvider) {
        // Configure all charts
        ChartJsProvider.setOptions({
            //custom colors		
            responsive: true,
            legend: {
                display: true,
                labels:{fontSize:25}
            },
            legendCallback: function (chart) {
                var text = [];
                text.push('<ul class="' + chart.id + '-legend">');
                for (var i = 0; i < chart.data.datasets[0].data.length; i++) {
                    text.push('<li><span style="background-color:' + chart.data.datasets[0].backgroundColor[i] + '">');
                    if (chart.data.labels[i]) {
                        text.push(chart.data.labels[i] + "TEXT");
                    }
                    text.push('</span></li>');
                }
                text.push('</ul>');
                return text.join("");
            }
//            maintainAspectRatio: true
        });
        //       Chart.pluginService.register({
        //   beforeRender: function (chart) {
        //     if (chart.config.options.showAllTooltips) {
        //       // create an array of tooltips
        //       // we can't use the chart tooltip because there is only one tooltip per chart
        //       chart.pluginTooltips = [];
        //       chart.config.data.datasets.forEach(function (dataset, i) {
        //         chart.getDatasetMeta(i).data.forEach(function (sector, j) {
        //           chart.pluginTooltips.push(new Chart.Tooltip({
        //             _chart: chart.chart,
        //             _chartInstance: chart,
        //             _data: chart.data,
        //             _options: chart.options.tooltips,
        //             _active: [sector]
        //           }, chart));
        //         });
        //       });

        //       // turn off normal tooltips
        //       chart.options.tooltips.enabled = false;
        //     }
        //   },
        //   afterDraw: function (chart, easing) {
        //     if (chart.config.options.showAllTooltips) {
        //       // we don't want the permanent tooltips to animate, so don't do anything till the animation runs atleast once
        //       if (!chart.allTooltipsOnce) {
        //         if (easing !== 1)
        //           return;
        //         chart.allTooltipsOnce = true;
        //       }

        //       // turn on tooltips
        //       chart.options.tooltips.enabled = true;
        //       Chart.helpers.each(chart.pluginTooltips, function (tooltip) {
        //         tooltip.initialize();
        //         tooltip.update();
        //         // we don't actually need this since we are not animating tooltips
        //         tooltip.pivot();
        //         tooltip.transition(easing).draw();
        //       });
        //       chart.options.tooltips.enabled = false;
        //     }
        //   }
        // })
    }]);
