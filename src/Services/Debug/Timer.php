<?php

namespace Dissonance\Develop\Services\Debug;


class Timer
{

    protected $timers = [];

    /**
     * Sets start microtime
     *
     * @return void
     */
    public function start(string $name)
    {
        if(!$name) {
            $d = debug_backtrace(2,2);
           $name =\md5(\serialize($d[1])).(isset($d[1]['class'])? $d[1]['class'].'::'.$d[1]['function']: $d[1]['function']);
        }
        $this->timers[$name] = [
            'name' => $name,
            'start' =>  \microtime(true)
        ];
    }

    /**
     * Sets end microtime
     *
     * @return $this
     * @throws \Exception
     */
    public function end(string $name)
    {
        if (!isset($this->timers[$name]))
        {
            throw new \LogicException("Сначала запуститте таймер start('$name')");
        }
        if(isset($this->timers[$name]['end'])) {
            return $this;
          //  throw new \LogicException("Таймер [$name] был завершен ранее!");
        }
        $this->timers[$name]['end'] =  microtime(true);
        $this->timers[$name]['memory'] = self::readableSize(memory_get_usage(true), null);
        $microtime =  $this->timers[$name]['end'] -  $this->timers[$name]['start'];
        $this->timers[$name]['time'] = self::readableElapsedTime($microtime, null);

        return $this;
    }


    public function getTimers()
    {
        foreach ($this->timers as $name => $v)
        {
            $this->end($name);
        }
        return $this->timers;
    }

    /**
     * Returns the memory peak, readable or not
     *
     * @param  boolean $readable Whether the result must be human readable
     * @param  string  $format   The format to display (printf format)
     * @return string|float
     */
    public function getMemoryPeak($raw = false, $format = null)
    {
        $memory = memory_get_peak_usage(true);

        return $raw ? $memory : self::readableSize($memory, $format);
    }

    /**
     * Returns a human readable memory size
     *
     * @param   int    $size
     * @param   string $format   The format to display (printf format)
     * @param   int    $round
     * @return  string
     */
    public static function readableSize($size, $format = null, $round = 3)
    {
        $mod = 1024;

        if (is_null($format)) {
            $format = '%.2f%s';
        }

        $units = explode(' ','B Kb Mb Gb Tb');

        for ($i = 0; $size > $mod; $i++) {
            $size /= $mod;
        }

        if (0 === $i) {
            $format = preg_replace('/(%.[\d]+f)/', '%d', $format);
        }

        return sprintf($format, round($size, $round), $units[$i]);
    }

    /**
     * Returns a human readable elapsed time
     *
     * @param  float $microtime
     * @param  string  $format   The format to display (printf format)
     * @return string
     */
    public static function readableElapsedTime($microtime, $format = null, $round = 3)
    {
        if (is_null($format)) {
            $format = '%.3f%s';
        }

        if ($microtime >= 1) {
            $unit = 's';
            $time = round($microtime, $round);
        } else {
            $unit = 'ms';
            $time = round($microtime*1000);

            $format = preg_replace('/(%.[\d]+f)/', '%d', $format);
        }

        return sprintf($format, $time, $unit);
    }

}