<?php

require_once 'CatBowl.php';

// unsere katzen klasse
class Cat
{
    public string $name;
    public string $hair_color;
    private float $weight;
    private float $stomachCapacity; // magenkapazität in liter (l)
    private float $stomachAmount = 0.0; // aktueller mageninhalt

    /*
     * constructor for base cat without info given
     */
    public function __construct(string $name = "Unknown Cat", string $color = "schwarz", float $initialWeight = 3.0)
    {
        $this->name = $name;
        $this->hair_color = $color;

        // zufällige magenkapazität zwischen 0,3 und 0,6 l
        $this->stomachCapacity = round(rand(30, 60) / 100, 2);

        $this->setWeight($initialWeight);
    }

    // verbraucht zufällig nahrung vom magen und reduziert das gewicht
    private function randDeductFoodWeight(int $min_percent, int $max_percent)
    {
        $currentMaxConsumption = $this->stomachAmount * ($max_percent / 100);
        $currentMinConsumption = $this->stomachAmount * ($min_percent / 100);

        if ($this->stomachAmount > 0) {
            // zufälliger verbrauch zwischen min und max prozent
            $consumption = rand((int)($currentMinConsumption * 100), (int)($currentMaxConsumption * 100)) / 100;
            $consumption = min($consumption, $this->stomachAmount);
        } else {
            $consumption = 0;
        }


        if ($consumption > 0) {
            $this->stomachAmount -= $consumption;
            $this->deductWeight($consumption);
            echo "-> verbrauchte nahrung: " . round($consumption, 2) . " kg. neues gewicht: " . round($this->weight, 2) . " kg<br />";
        } else {
            echo "-> kein futter verbraucht (magen leer).<br />";
        }
    }

    // prüft ob die katze hungrig ist (magen unter 20% voll)
    public function getIsHungry(): bool
    {
        if ($this->stomachCapacity === 0.0) return true;
        $fillPercent = ($this->stomachAmount / $this->stomachCapacity) * 100;
        return $fillPercent < 20.0;
    }

    // gibt den mageninhalt zurück
    public function getStomachAmount(): float
    {
        return $this->stomachAmount;
    }


    // lässt die katze aus einem napf essen
    public function eat(CatBowl $bowl)
    {
        echo $this->name . ' versucht zu essen...<br />';

        $spaceAvailable = $this->stomachCapacity - $this->stomachAmount; // wie viel noch reinpasst

        if ($spaceAvailable <= 0.001) {
            echo "->soll ich explodieren? magen ist zu voll (" . round(($this->stomachAmount / $this->stomachCapacity) * 100) . " %) und kann nicht mehr fressen.<br />";
            return;
        }

        // die katze kann maximal 0,2 kg pro bissen essen oder bis der magen voll ist
        $maxBite = min(0.2, $spaceAvailable);

        // versucht, vom napf die menge abzuziehen
        $eatenQuantity = $bowl->deductQuantity($maxBite);

        if ($eatenQuantity > 0) {
            $this->stomachAmount += $eatenQuantity;
            $this->weight += $eatenQuantity; // gewicht nimmt zu, klar!
            echo "-> hat " . round($eatenQuantity, 2) . " kg lecka futter gefressen. neuer füllstand: " . round($this->stomachAmount, 2) . " l. neues gewicht: " . round($this->weight, 2) . " kg<br />";
        } else {
            echo "-> der napf ist leer, kann nicht fressen. FEED ME HOOMAN<br />";
        }
    }

    // lässt die katze mit einer anderen katze spielen
    public function play(Cat $fellow)
    {
        echo $this->name . ' und ' . $fellow->name . ' wollen spielen...<br />';

        // prüfen, ob die katze zu 100% gefüllt ist (ersetzt abs() durch größer-gleich-prüfung nahe 100%)
        if ($this->stomachAmount >= $this->stomachCapacity - 0.001) {
            echo '-> ' . $this->name . ': magen ist 100% gefüllt. bin bewegungsunfähig und schlafe stattdessen...<br />';
            $this->sleep(false);
            return;
        }

        // prüfen, ob der spielkamerad zu 100% gefüllt ist
        if ($fellow->stomachAmount >= $fellow->stomachCapacity - 0.001) {
            echo '-> ' . $fellow->name . ': magen ist 100% gefüllt. bin bewegungsunfähig und schlafe stattdessen...<br />';
            $fellow->sleep(false);
            return;
        }

        if ($this->getIsHungry()) {
            echo '-> ' . $this->name . ': ich bin zu hungrig zum spielen (magen unter 20%) HUNGAAAA.<br />';
            return;
        }

        if ($fellow->getIsHungry()) {
            echo '-> ' . $fellow->name . ': ist zu hungrig zum spielen (magen unter 20%) HUNGAAAA.<br />';
            return;
        }

        echo "-> beide katzen spielen und verbrauchen energie:<br />"; // toll
        $this->randDeductFoodWeight(30, 60);
        $fellow->randDeductFoodWeight(30, 60);
    }


    /*
     * if cat isn't hungry, goes to sleep and uses a random amount of food between 10% - 30%
     * will meow if hungry
     */

    public function sleep(bool $check_hunger = true)
    {
        echo $this->name . ' versucht zu schlafen...<br />';

        if ($check_hunger && $this->getIsHungry()) {
            // hungrige katzen können nicht schlafen, sondern miauen stattdessen... grr meow!
            echo '-> ICH HAB HUNGA KANN NICHT SCHLAFEN GRR MEOW!<br />';
            $this->makeASound(); // macht geräusch wenn hungrig
            return;
        }

        echo "-> schläft und verbraucht energie (10-30% des mageninhalts):<br />";
        $this->randDeductFoodWeight(10, 30);
    }

    /*
     * current object goes to defecate
     * if stomach capacity allows it
     *
     */
    public function defecate()
    {
        // ToDo remove this comment before finishing product
        // kaka - dieser kommentar
        echo $this->name . ' geht aufs katzenklo...<br />';

        $minDefecateAmount = $this->stomachCapacity * 0.05; // muss genug im magen sein

        if ($this->stomachAmount < $minDefecateAmount) {
            echo '-> zu wenig futter im körper (' . round($this->stomachAmount, 2) . ' l), kann nicht aufs katzenklo gehen.<br />';
            return;
        }

        $amountDefecated = $this->stomachAmount * 0.1; // 10% vom mageninhalt werden ausgeschieden

        $this->stomachAmount -= $amountDefecated;
        $this->deductWeight($amountDefecated);

        echo '-> hat ' . round($amountDefecated, 2) . ' kg ausgeschieden. neues gewicht: ' . round($this->weight, 2) . ' kg<br />';
    }

    /*
     * echo meow
     */
    public function makeASound()
    {
        echo $this->name . " sagt: meow!<br />";
    }

    /*
     * set weight of current object weight to new one if new weight > 0
     */
    public function setWeight(float $newWeight)
    {
        if ($newWeight > 0) {
            $this->weight = $newWeight;
        } else {
            if ($this->weight) { //!isset
                $this->weight = 3.0; // standard gewicht wenn nix gesetzt war
            }
            echo "achtung: ungültiges gewicht ($newWeight kg) für {$this->name} ignoriert.<br />";
        }
    }

    /*
     * deduct specific amount of weight from current object
     */
    public function deductWeight(float $amountToDeduct)
    {
        if ($this->weight - $amountToDeduct > 0.0) {
            $this->weight -= $amountToDeduct;
        } else {
            $this->weight = 0.0;
            echo 'achtung: ' . $this->name . ' hat kein gewicht mehr zu verlieren!<br />';
        }
    }

    /*
     * display:
     * name of current object
     * color of current object
     * weight of current object
     * stomach capacity of current object
     */
    public function showStatus()
    {
        $fillPercent = ($this->stomachAmount / $this->stomachCapacity) * 100;

        echo "[" . $this->name . "]:<br />";
        echo "farbe: " . $this->hair_color . "<br />";
        echo "gewicht: " . round($this->weight, 2) . " kg<br />";
        echo "hunger: " . ($this->getIsHungry() ? "hungrig raaaa (unter 20%)" : "satt") . "<br />";
        echo "mageninhalt: " . round($this->stomachAmount, 2) . " l (kapazität: " . round($this->stomachCapacity, 2) . " l / füllung: " . round($fillPercent, 1) . " %)<br />";
        echo "<br />";
    }
}