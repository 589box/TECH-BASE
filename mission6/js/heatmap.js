var cal = new CalHeatMap();
val startdate = new Date();
var year = startdate.getFullYear();
var month = startdate.getMonth();

cal.init({
    itemSelector: "#cal-heatmap",
    domain: "month",
    subDomain: "day",
    data: "http://localhost/comp.json",
    range: 12,
    cellSize: 15,
    tooltip: true,
    highlight: "now",
    legend: [1, 3, 5, 7, 10],
});