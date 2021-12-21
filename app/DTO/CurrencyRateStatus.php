<?php

namespace App\DTO;

class CurrencyRateStatus
{

    private string $currencyName;

    private string $currencySymbol;

    private string $currencyTo;

    private float $openingValue;

    private float $closeValue;

    private bool $valid;

    public function __construct(string $currencyName, string $currencySymbol, string $currencyTo, float $openingValue, float $closeValue)
    {
        $this->currencyName = $currencyName;
        $this->currencySymbol = $currencySymbol;
        $this->currencyTo = $currencyTo;
        $this->openingValue = $openingValue;
        $this->closeValue = $closeValue;
        $this->valid = $openingValue > 0 && $closeValue > 0;
    }

    public function getCurrencyName(): string
    {
        return $this->currencyName;
    }

    public function getCurrencySymbol(): string
    {
        return $this->currencySymbol;
    }

    public function getCurrencyTo(): string
    {
        return $this->currencyTo;
    }

    public function getOpeningValue(): float
    {
        return $this->openingValue;
    }

    public function getOpeningValueAsString(): string
    {
        return round($this->openingValue, 2);
    }

    public function getCloseValue(): float
    {
        return $this->closeValue;
    }

    public function getCloseValueAsString(): string
    {
        return round($this->closeValue, 2);
    }

    public function getOpenCloseRatio(): float
    {
        if (false === $this->isValid() || $this->getCloseValue() === 0.0) {
            return 0;
        }

        return round(($this->getCloseValue() * 100 / $this->getOpeningValue()) - 100, 2);
    }

    public function isValid(): bool
    {
        return $this->valid;
    }
}
