<?php

# PHPlot Example: Line graph, 2 lines

require_once 'phplot-6.2.0/phplot.php';

# Generate data for:

$end = 5;

$delta = 0.1;

$data = array();

for ($x = 0; $x <= $end; $x += $delta)

  $data[] = array('', $x, pow($x,3)+$x);

 

$plot = new PHPlot(600, 400);

//$plot->SetImageBorderType('plain');

 

$plot->SetPlotType('lines');

$plot->SetDataType('data-data');

$plot->SetDataValues($data);

 

# Main plot title:

$plot->SetTitle('Line Plot, X Squared');

 

# Make a legend for the 2 functions:

$plot->SetLegend(array('x^2+x'));

 

# Select a plot area and force ticks to nice values:

$plot->SetPlotAreaWorld(0, 0, 5, 25);

 

# Even though the data labels are empty, with numeric formatting they

# will be output as zeros unless we turn them off:

$plot->SetXDataLabelPos('none');

 

$plot->SetXTickIncrement(0.5);

$plot->SetXLabelType('data');

$plot->SetPrecisionX(1);

 

$plot->SetYTickIncrement(2);

$plot->SetYLabelType('data');

$plot->SetPrecisionY(1);

 

# Draw both grids:

$plot->SetDrawXGrid(True);

$plot->SetDrawYGrid(True);

 
$plot->DrawGraph();

$str = "3^3-10";
$options = json_decode($str);
echo $options;
?>