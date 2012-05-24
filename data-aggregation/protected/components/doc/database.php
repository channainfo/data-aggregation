<?php

date_default_timezone_set('America/New_York');

require dirname(__FILE__) . "/../DJJob.php";

DJJob::configure("mysql:host=127.0.0.1;dbname=djjob", array(
  "mysql_user" => "root",
  "mysql_pass" => "",
));


class HelloWorldJob {
    public function __construct($name) {
        $this->name = $name;
    }
    public function perform() {
        echo "Hello {$this->name}!\n";
        sleep(1);
    }
}

class FailingJob {
    public function perform() {
        sleep(1);
        throw new Exception("Uh oh");
    }
}

var_dump(DJJob::status());

DJJob::enqueue(new HelloWorldJob("delayed_job"));
DJJob::bulkEnqueue(array(
    new HelloWorldJob("shopify"),
    new HelloWorldJob("github"),
));
DJJob::enqueue(new FailingJob());

$worker = new DJWorker(array("count" => 5, "max_attempts" => 2, "sleep" => 10));
$worker->start();

var_dump(DJJob::status());
