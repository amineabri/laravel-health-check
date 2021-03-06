<?php

namespace UKFast\HealthCheck;

use Illuminate\Support\Collection;
use UKFast\HealthCheck\Exceptions\CheckNotFoundException;

class AppHealth
{
    /**
     * @var Collection $checks
     */
    protected $checks;

    public function __construct($checks)
    {
        $this->checks = $checks;
    }

    public function passes($checkName)
    {
        $check = $this->checks->filter(function ($check) use ($checkName) {
            return $check->name() == $checkName;
        })->first();

        if (!$check) {
            throw new CheckNotFoundException($checkName);
        }

        try {
            return $check->status()->isOkay();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function fails($checkName)
    {
        return !$this->passes($checkName);
    }

    /**
     * Returns a collection of all health checks
     * 
     * @return Illuminate\Support\Collection
     */
    public function all()
    {
        return $this->checks;
    }
}
