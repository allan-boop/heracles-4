<?php
namespace App;
use Exception;
class Arena
{
    private array $monsters;
    private Hero $hero;
    private string $direction;
    private int $size = 10;
    public function __construct(Hero $hero, array $monsters)
    {
        $this->hero = $hero;
        $this->monsters = $monsters;
    }
    public function move(Fighter $fighter, string $direction)
    {
        if($direction === "N"){
            $calY = $fighter->getY() - 1;
            $calX = $fighter->getX();
        }elseif($direction === "S"){
            $calY = $fighter->getY() + 1;
            $calX = $fighter->getX();
        }elseif($direction === "E"){
            $calX = $fighter->getX() + 1;
            $calY = $fighter->getY();
        }elseif($direction === "W"){
            $calX = $fighter->getX() - 1;
            $calY = $fighter->getY();
        }
        if($calX < 0 || $calX >= $this->getSize() ){
            throw new Exception("Vous sortez de la zone");
        }elseif($calY < 0 || $calY >= $this->getSize() ){
            throw new Exception("Vous sortez de la zone");
        }
        $arrayMonsters = $this->getMonsters();
        foreach($arrayMonsters as $index => $monster)
        {
            if($monster->getX() === $calX && $monster->getY() === $calY)
            {
                throw new Exception("l'emplacement est occupé");
            }
        }
        $fighter->setX($calX);
        $fighter->setY($calY);
    }
    public function battle(int $id){
        if($this->touchable($this->getHero(), $this->getMonsters()[$id]) === true)
        {
            $this->getHero()->fight($this->getMonsters()[$id]);
            if($this->getMonsters()[$id]->isAlive() === true)
            {
                if($this->touchable($this->getMonsters()[$id], $this->getHero()) === true)
                {
                    $this->getMonsters()[$id]->fight($this->getHero());
                    if($this->getMonsters()[$id] === $this->getMonsters()[0]){
                        throw new Exception("On attaque pas un Dieu");
                    }
                }else
                {
                    throw new Exception("Le chewoual est trop loin pour contre attaquer");
                }
            }elseif($this->getMonsters()[$id]->isAlive() === false)
            {
                $gain = $this->getMonsters()[$id]->getExperience() + $this->getHero()->getExperience();
                $this->getHero()->setExperience($gain);                
                $array = $this->getMonsters();
                unset($array[$id]);
                $this->setMonsters($array);
            }
        }else
        {
            throw new Exception("Vous etes trop loin");
        }
    }
    public function getDistance(Fighter $startFighter, Fighter $endFighter): float
    {
        $Xdistance = $endFighter->getX() - $startFighter->getX();
        $Ydistance = $endFighter->getY() - $startFighter->getY();
        return sqrt($Xdistance ** 2 + $Ydistance ** 2);
    }
    public function touchable(Fighter $attacker, Fighter $defenser): bool
    {
        return $this->getDistance($attacker, $defenser) <= $attacker->getRange();
    }
    /**
     * Get the value of monsters
     */
    public function getMonsters(): array
    {
        return $this->monsters;
    }
    /**
     * Set the value of monsters
     *
     */
    public function setMonsters($monsters): void
    {
        $this->monsters = $monsters;
    }
    /**
     * Get the value of hero
     */
    public function getHero(): Hero
    {
        return $this->hero;
    }
    /**
     * Set the value of hero
     */
    public function setHero($hero): void
    {
        $this->hero = $hero;
    }
    /**
     * Get the value of size
     */
    public function getSize(): int
    {
        return $this->size;
    }
    /**
     * Get the value of direction
     *
     * @return string
     */
    public function getDirection(): string
    {
        return $this->direction;
    }
    /**
     * Set the value of direction
     *
     * @param string $direction
     *
     * @return self
     */
    public function setDirection(string $direction): self
    {
        $this->direction = $direction;
        return $this;
    }
}