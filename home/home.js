//Pie Chart
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Task', 'Hours per Day'],
        ['Chicken Chop', 83],
        ['Lamb Chop', 72],
        ['Steak', 44],
        ['Meatball', 32],
        ['Pasta', 18]
    ]);

    var options = {'title':'Best Selling Items'};
    var chart = new google.visualization.PieChart(document.getElementById('pie'));
    chart.draw(data, options);
}



//Bar Chart
google.charts.load('current', {'packages':['bar']});
google.charts.setOnLoadCallback(drawStuff);

    function drawStuff() {
        var data = new google.visualization.arrayToDataTable([
            ['Month', 'Orders', { role: "style" }],
            ["November", 88, 'gold'],
            ["October", 90, 'gold'],
            ["September", 97, 'gold'],
            ["August)", 70, 'gold'],
            ['July', 83, 'gold'],
        ]);

        var options = {
            title: 'Latest Month Orders',
            width: 900,
            legend: { position: 'none' },
            chart: { title: 'Latest Month Orders',
                    subtitle: 'last 5 Months' },
            bars: 'horizontal', 
            axes: {
                x: {
                    0: { side: 'top', label: 'Orders'} 
                }
            },
            bar: { groupWidth: "90%" }
        };

        var chart = new google.charts.Bar(document.getElementById('bar'));
        chart.draw(data, options);
    };