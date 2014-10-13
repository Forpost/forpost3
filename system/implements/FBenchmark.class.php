<?php

/**
 * Created by PhpStorm.
 * User: CoolKid
 * Date: 12.07.14
 * Time: 23:12
 */
class FBenchmark
{
    protected $bench_start_time = null;
    protected $bench_finish_time = null;
    protected $checkpoints = array();

    protected function setStartTime($start_time = null)
    {
        $this->bench_start_time = is_null($start_time) ? microtime(true) : $start_time;
    }

    public function startBench($start_time = null)
    {
        $this->setStartTime($start_time);
    }

    public function finishBench()
    {
        $this->bench_finish_time = microtime(true);

        return array(
            'bench_start_time'  => $this->bench_start_time,
            'bench_finish_time' => $this->bench_finish_time,
            'checkpoints'       => $this->checkpoints,
            'bench_duration'    => self::getElapsedTime($this->bench_finish_time - $this->bench_start_time),
            'memory_peak_usage' => self::getMemorySize(memory_get_peak_usage(true)),
        );
    }

    public function startMeasure($code, $description = null)
    {
        $this->checkpoints[$code] = array(
            'description'           => $description,
            'memory_usage'          => self::getMemorySize(memory_get_usage()),
            'elapsed_from_start'    => self::getElapsedTime(microtime(true) - $this->bench_start_time),
            'checkpoint_begin_time' => microtime(true),
            'checkpoint_end_time'   => null,
            'checkpoint_duration'   => null,
        );
    }

    public function stopMeasure($code)
    {
        $this->checkpoints[$code]['checkpoint_end_time'] = microtime(true);
        $this->checkpoints[$code]['duration'] = self::getElapsedTime(
            $this->checkpoints[$code]['checkpoint_end_time'] - $this->checkpoints[$code]['checkpoint_begin_time']
        );
    }

    public static function getMemorySize($size, $format = null, $round = 3)
    {
        $mod = 1024;

        if (is_null($format)) {
            $format = '%.2f%s';
        }

        $units = explode(' ', 'B Kb Mb Gb Tb');

        for ($i = 0; $size > $mod; $i++) {
            $size /= $mod;
        }

        if (0 === $i) {
            $format = preg_replace('/(%.[\d]+f)/', '%d', $format);
        }

        return sprintf($format, round($size, $round), $units[$i]);
    }

    public static function getElapsedTime($microtime, $format = null, $round = 3)
    {
        if (is_null($format)) {
            $format = '%.3f%s';
        }

        if ($microtime >= 1) {
            $unit = 's';
            $time = round($microtime, $round);
        } else {
            $unit = 'ms';
            $time = round($microtime * 1000);

            $format = preg_replace('/(%.[\d]+f)/', '%d', $format);
        }

        return sprintf($format, $time, $unit);
    }
}
