<?php


namespace App\Core\Thread;


use App\Core\Interfaces\Runnable;

class Thread
{

    public static function await(Runnable $delegate, int $duration = 10, int $limit = 0): void
    {
        $totalDuration = 0;
        $continue = true;
        while ($delegate->run() === true && $continue) {
            sleep($duration);
            if ($limit > 0) {
                if ($totalDuration > $limit) {
                    $continue = false;
                }
            }
        }
    }

}