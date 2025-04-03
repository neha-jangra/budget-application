<?php

namespace App\Traits;

use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\{File, Log};


trait NodeScripts
{
    protected function screenshot($url)
    {
        Log::info("Command run =========");
        $variable1 = $url;
        Log::info($variable1);
        // echo $variable1." == \n";
        $path = public_path("node.js");
        // $folder = "public/screenshots";
        $folder = "screenshots";
        // echo "Dormer folder testing \n";
        if (!File::exists("screenshots")) {
            File::makeDirectory("screenshots", 0777, true);
        }
        // echo "after folder";
        $random = time();
        $reportSection = $folder . '/' . $random . '-' . '-report-section.png';
        $returnArr = [
            "front" => 'screenshots/' . $random . '-' . '-report-section.png'
        ];
        $error = "";

        $node_path = config('env.node') ?? '';
        Log::info("node_path " . $node_path);
        $process = new Process([$node_path, $path, $variable1, $reportSection]);
        // $process->run();
        $process->run(function ($type, $buffer) {
            if (Process::ERR === $type) {
                Log::info("process ==> in err " . $buffer);
            } else {
                Log::info("process ==>  " . $buffer);
            }
        });
        while ($process->isRunning()) {
            Log::info($process->getOutput());
            Log::info($process->getErrorOutput());
        }
        // Access the output, if needed
        $output = $process->getOutput();

        Log::info("Command executed =========");
        Log::info("Error => " . $process->getErrorOutput());
        Log::info("output => " . $process->getOutput());

        if ($process->isSuccessful()) {
            return [
                'status' => true,
                "message" => "Screenshot created",
                "screenshot" => $returnArr
            ];
        } else {
            return [
                'status' => false,
                "message" => $error,
                "screenshot" => (object) []
            ];
        }
    }
}
