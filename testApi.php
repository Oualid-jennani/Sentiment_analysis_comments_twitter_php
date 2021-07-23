<?php 

require "vendor/monkeylearn/monkeylearn-php/autoload.php";

$ml = new MonkeyLearn\Client('831e349064703038eb3d96a710ca316a5395a1c9');

$data = ["This is a Bad tool!"];
$model_id = 'cl_pi3C7JiL';
$res = $ml->classifiers->classify($model_id, $data);

print_r($res->result[0]["classifications"][0]["tag_name"]);
echo "<br>";
print_r($res->result[0]["classifications"][0]["confidence"]);
echo "<br>";

foreach ($res->result as $result)
{
    print_r($result);
}

?>