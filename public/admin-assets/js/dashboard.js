// Line Chart
am5.ready(function () {
    let element = "line_chart";
    let line_chart = $("#" + element);
    let data = line_chart.data("chat-data") ?? [];
    let name = line_chart.data("name") ?? "Line Chart";
    // Create root element
    var root = am5.Root.new(element);

    // Set themes
    root.setThemes([am5themes_Animated.new(root)]);

    // Create chart
    var chart = root.container.children.push(
        am5xy.XYChart.new(root, {
            panX: true,
            panY: true,
            wheelX: "panX",
            wheelY: "zoomX",
            pinchZoomX: true,
            paddingLeft: 0,
            paddingRight: 1,
        }),
    );

    // Add cursor
    var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
    cursor.lineY.set("visible", false);

    // Create axes
    var xRenderer = am5xy.AxisRendererX.new(root, {
        minGridDistance: 30,
        minorGridEnabled: true,
    });

    xRenderer.labels.template.setAll({
        // rotation: -90,
        centerY: am5.p50,
        centerX: am5.p100,
        paddingRight: 15,
    });

    xRenderer.grid.template.setAll({
        location: 1,
    });

    var xAxis = chart.xAxes.push(
        am5xy.CategoryAxis.new(root, {
            maxDeviation: 0.3,
            categoryField: "label",
            renderer: xRenderer,
            tooltip: am5.Tooltip.new(root, {}),
        }),
    );

    var yRenderer = am5xy.AxisRendererY.new(root, {
        strokeOpacity: 0.1,
    });

    var yAxis = chart.yAxes.push(
        am5xy.ValueAxis.new(root, {
            maxDeviation: 0.2,
            renderer: yRenderer,
        }),
    );

    // Create series
    var series = chart.series.push(
        am5xy.ColumnSeries.new(root, {
            name: name,
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "value",
            sequencedInterpolation: true,
            categoryXField: "label",
            tooltip: am5.Tooltip.new(root, {
                labelText: "{valueY}",
            }),
        }),
    );
    series.columns.template.adapters.add("fill", function (fill, target) {
        if (target.dataItem.get("valueY") % 2 === 0) {
            return am5.color(0x219653);
        } else {
            return am5.color(0xeceff6);
        }
    });

    series.columns.template.adapters.add("stroke", function (stroke, target) {
        return chart.get("colors").getIndex(series.columns.indexOf(target));
    });
    //
    series.columns.template.setAll({
        cornerRadiusTL: 5,
        cornerRadiusTR: 5,
        strokeOpacity: 0,
    });
    xAxis.data.setAll(data);
    series.data.setAll(data);

    // Make stuff animate on load
    series.appear(1000);
    chart.appear(1000, 100);
});

// doughnut chart
am5.ready(function () {
    let element = "doughnut_chart";
    let el = $("#" + element);
    let data = el.data("chat-data") ?? [];
    let name = el.data("name") ?? "Doughnut Chart";
    // check if data all key value 0 then set retun no data
    let isData = data.every((item) => item.value === 0);
    if (isData) {
        // set no data in text center
        el.html("<p class='text-center text-muted my-5'>Нет данных</p>");
        return;
    }

    var root = am5.Root.new(element);

    root.setThemes([am5themes_Animated.new(root)]);

    var chart = root.container.children.push(
        am5percent.PieChart.new(root, {
            layout: root.verticalLayout,
            innerRadius: am5.percent(65),
        }),
    );

    var series = chart.series.push(
        am5percent.PieSeries.new(root, {
            name: name,
            valueField: "value",
            categoryField: "category",
            alignLabels: false,
        }),
    );
    series
        .get("colors")
        .set("colors", [
            am5.color(0x219653),
            am5.color(0xe9e9e9),
            am5.color(0x6b7678),
            am5.color(0xf0c98c),
        ]);

    series.data.setAll(data);

    var legend = chart.children.push(
        am5.Legend.new(root, {
            centerX: am5.percent(50),
            x: am5.percent(50),
            marginTop: 30,
            marginBottom: 30,
        }),
    );

    legend.data.setAll(series.dataItems);

    series.labels.template.set("visible", false);
    series.ticks.template.set("visible", false);

    series.appear(1000, 100);
}); // end am5.ready()

// Venn Diagram
am5.ready(function () {
    let element = "venn_diagram";
    let el = $("#" + element);
    let data = el.data("chat-data") ?? [];
    let name = el.data("name") ?? "Doughnut Chart";

    // object to array
    data = Object.keys(data).map((key) => data[key]);
    // check if data all key value 0 then set retun no data
    let isData = data.every((item) => item.value === 0);
    if (isData) {
        // set no data in text center
        el.html("<p class='text-center text-muted my-5'>Нет данных</p>");
        return;
    }
    // Create root
    var root = am5.Root.new(element);

    // Set themes
    root.setThemes([am5themes_Animated.new(root)]);

    // Create wrapper container
    var container = root.container.children.push(
        am5.Container.new(root, {
            name: name,
            width: am5.p100,
            height: am5.p100,
            layout: root.verticalLayout,
        }),
    );

    // Create venn series
    var chart = container.children.push(
        am5venn.Venn.new(root, {
            categoryField: "name",
            valueField: "value",
            intersectionsField: "sets",
            fillField: "color",
            paddingTop: 40,
            paddingBottom: 40,
            paddingLeft: 40,
            paddingRight: 40,
        }),
    );

    chart.data.setAll(data);

    // Set tooltip content
    chart.slices.template.set("tooltipText", "{category}: {value}");

    // Set up hover appearance
    chart.hoverGraphics.setAll({
        strokeDasharray: [3, 3],
        stroke: am5.color("#000"),
        strokeWidth: 2,
    });
    // Add legend
    var legend = container.children.push(
        am5.Legend.new(root, {
            centerX: am5.p50,
            x: am5.p50,
        }),
    );
    legend.data.setAll(chart.dataItems);
}); // end am5.ready()

// multi_axis_line
am5.ready(function () {
    let element = "multi_axis_line";
    let el = $("#" + element);
    let data = el.data("chat-data") ?? [];
    let name = el.data("name") ?? "Multi Line Chart";

    var root = am5.Root.new(element);

    root.setThemes([am5themes_Animated.new(root)]);

    var chart = root.container.children.push(
        am5xy.XYChart.new(root, {
            name: name,
            panX: true,
            panY: true,
            layout: root.verticalLayout,
        }),
    );

    // Create Y-axis
    var yAxis = chart.yAxes.push(
        am5xy.ValueAxis.new(root, {
            extraTooltipPrecision: 1,
            renderer: am5xy.AxisRendererY.new(root, {}),
        }),
    );

    // Create X-Axis
    var xAxis = chart.xAxes.push(
        am5xy.DateAxis.new(root, {
            baseInterval: {
                timeUnit: "month",
                count: 1,
            },
            renderer: am5xy.AxisRendererX.new(root, {
                minGridDistance: 20,
            }),
        }),
    );

    // Create series
    function createSeries(name, field) {
        var series = chart.series.push(
            am5xy.SmoothedXLineSeries.new(root, {
                name: name,
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: field,
                valueXField: "date",
                tooltip: am5.Tooltip.new(root, {
                    pointerOrientation: "horizontal",
                    labelText: "[bold]{name}[/]\n{categoryX}: {valueY}",
                }),
            }),
        );

        series.strokes.template.setAll({
            strokeWidth: 2,
            shadowColor: am5.color(0x000000),
            shadowBlur: 10,
            shadowOffsetX: 10,
            shadowOffsetY: 10,
            shadowOpacity: 0.5,
        });

        series.bullets.push(function () {
            return am5.Bullet.new(root, {
                sprite: am5.Circle.new(root, {
                    radius: 5,
                    fill: series.get("fill"),
                    shadowColor: am5.color(0x000000),
                    shadowBlur: 10,
                    shadowOffsetX: 10,
                    shadowOffsetY: 10,
                    shadowOpacity: 0.3,
                }),
            });
        });

        // series
        //     .get("tooltip")
        //     .label.set(
        //         "text",
        //         "[bold]{name}[/]\n{valueX.formatDate()}: {valueY}"
        //     );
        series.data.setAll(data);
    }

    chart
        .get("colors")
        .set("colors", [am5.color(0x198754), am5.color(0x000000)]);

    createSeries("Approved", "approved");
    createSeries("Pending", "pending");

    // Add cursor

    chart.set(
        "cursor",
        am5xy.XYCursor.new(root, {
            behavior: "zoomXY",
            xAxis: xAxis,
        }),
    );

    xAxis.set(
        "tooltip",
        am5.Tooltip.new(root, {
            themeTags: ["axis"],
        }),
    );

    yAxis.set(
        "tooltip",
        am5.Tooltip.new(root, {
            themeTags: ["axis"],
        }),
    );
}); // end am5.ready()
