<?php


namespace App\Core\Components;


use Cocur\Slugify\Slugify;

class Slugger
{

    private static $instance = null;

    private $slugger;

    /**
     * Slugger constructor.
     */
    public function __construct()
    {
        if (self::$instance !== null) {
            throw new \Exception("Please don't call constructor directly, prefere use getInstance Method...");
        }
        $this->slugger = new Slugify(['rulesets' => ['default', 'french']]);
    }


    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new Slugger();
        }

        return self::$instance;
    }


    public function slug(string $value): string
    {
        return $this->slugger->slugify($value);
    }


}