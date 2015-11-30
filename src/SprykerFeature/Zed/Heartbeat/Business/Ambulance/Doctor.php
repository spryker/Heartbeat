<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Heartbeat\Business\Ambulance;

use Generated\Shared\Transfer\HealthReportTransfer;
use SprykerFeature\Shared\Heartbeat\Code\HealthIndicatorInterface;

class Doctor
{

    /**
     * @var HealthReportTransfer
     */
    protected $healthReport;

    /**
     * @var HealthIndicatorInterface[]
     */
    protected $healthIndicator;

    /**
     * @param HealthIndicatorInterface[] $healthIndicator
     */
    public function __construct(array $healthIndicator)
    {
        $this->healthReport = new HealthReportTransfer();
        $this->healthIndicator = $healthIndicator;
    }

    /**
     * @return self
     */
    public function doHealthCheck()
    {
        foreach ($this->healthIndicator as $healthIndicator) {
            $this->check($healthIndicator);
        }

        return $this;
    }

    /**
     * @param HealthIndicatorInterface $healthIndicator
     */
    private function check(HealthIndicatorInterface $healthIndicator)
    {
        $healthReport = $healthIndicator->doHealthCheck();
        $this->healthReport->addHealthIndicatorReport($healthReport);
    }

    /**
     * @return HealthReportTransfer
     */
    public function getReport()
    {
        return $this->healthReport;
    }

    /**
     * @return bool
     */
    public function isPatientAlive()
    {
        $patientIsAlive = true;

        foreach ($this->healthReport->getHealthIndicatorReport() as $healthIndicatorReport) {
            if (!$healthIndicatorReport->getStatus()) {
                $patientIsAlive = false;
            }
        }

        return $patientIsAlive;
    }

}
