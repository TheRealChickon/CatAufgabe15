<?php

// die futternapf klasse, wo das futter drin ist
class CatBowl
{
    private float $cubicCapacity; // maximale kapazität des napfes
    private float $quantity; // aktuelle menge futter

    // konstruktor für den napf
    public function __construct(float $capacity = 5.0, float $initialQuantity = 0.0)
    {
        // stellt sicher, dass kapazität und menge nicht negativ sind
        $this->cubicCapacity = max(0.0, $capacity);
        $this->quantity = min($this->cubicCapacity, max(0.0, $initialQuantity));
    }

    // berechnet den füllstand in prozent
    public function getLevel(): float
    {
        if ($this->cubicCapacity === 0.0) {
            return 0.0;
        }
        return round(($this->quantity / $this->cubicCapacity) * 100, 2);
    }

    // füllt den napf auf, gibt zurück wie viel hinzugefügt wurde
    public function refill(float $quantity): float
    {
        $added = 0.0;
        if ($quantity > 0) {
            $spaceAvailable = $this->cubicCapacity - $this->quantity; // wie viel platz noch frei is
            $added = min($quantity, $spaceAvailable);
            $this->quantity += $added;
        }
        return $added;
    }

    // gibt die aktuelle futtermenge zurück
    public function getQuantity(): float
    {
        return $this->quantity;
    }

    // zieht eine menge futter ab (wenn katze isst), gibt zurück wie viel abgezogen wurde
    public function deductQuantity(float $amount): float
    {
        $deducted = min($amount, $this->quantity);
        $this->quantity -= $deducted;
        return $deducted;
    }

    // zeigt den aktuellen status vom napf an
    public function showStatus()
    {
        echo "napfstatus:<br />";
        echo "fassungsvermögen: {$this->cubicCapacity} l<br />";
        echo "aktueller füllstand: {$this->quantity} l ({$this->getLevel()} %)<br />";
    }
}