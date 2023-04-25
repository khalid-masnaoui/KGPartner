<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Html;

require_once __DIR__ . "/../../../core/ini.php";
require_once __DIR__ . "/../../../functions/sanitize.php";
require_once __DIR__ . "/../../../functions/encryptDecrypt.php";

require_once __DIR__ . "/../../../vendor/autoload.php";


if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.info/pages/infos/reports/summary_report.php") {

    if (token::check(input::get("token"), "download_summary")) {

        $htmlTable = input::get("htmlTable");

        $reader = new Html();
        $spreadsheet = $reader->loadFromString($htmlTable);

        $date = new DateTime();
        $date = $date->format('_YmdHis');
        $fileName = "summaryHistory$date";

        ob_start();

        $writer = IOFactory::createWriter($spreadsheet, 'Xls')->save('php://output');
        $excel = ob_get_contents();

        ob_end_clean();

        $file = "data:application/vnd.ms-excel;base64," . base64_encode($excel);

        //log the action
        $log = new ActivityLogger();

        $details = "--fileName[$fileName.xls]";
        $action = "Excel Exported";
        $description = "Summary Report Excel Exported. <<$details>>";
        $logged = $log->addLog($action, $description);

        $token = token::generate("download_summary");
        print_r(json_encode([$token, $file, $fileName]));

    } else {
        $token = token::generate("download_summary");
        print_r(json_encode([$token]));
    }


} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}
















?>
