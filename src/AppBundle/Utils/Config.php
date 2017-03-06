<?php

namespace AppBundle\Utils;

use Doctrine\ORM\EntityManager;

class Config
{
    const APP_YEAR = 2017;
    const APP_AUTHOR = 'Eko Kurniawan';
    const APP_VERSION = '1.0.0';
    const GENDER_MALE = 'Laki-laki';
    const GENDER_FEMALE = 'Perempuan';
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_YES = 1;
    const STATUS_NO = 0;
    const PERPAGE = 10;

    protected static $genders = [
        self::GENDER_MALE=>self::GENDER_MALE,
        self::GENDER_FEMALE=>self::GENDER_FEMALE,
    ];
    protected static $actives = [
        'Aktif'=>self::STATUS_ACTIVE,
        'Tidak Aktif'=>self::STATUS_INACTIVE,
    ];
    protected static $yes = [
        'Ya'=>self::STATUS_YES,
        'Tidak'=>self::STATUS_NO,
    ];

    protected $em;
    protected $data = [
        'app_name' => 'App Name',
        'app_alias' => 'App',
        'app_description' => 'App description',
        'app_owner' => 'App Owner',
    ];
    protected $dry = true;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function __call($var, array $args)
    {
        return $this->get($var);
    }

    public function get($var)
    {
        $this->loadConfig();

        return array_key_exists($var, $this->data) ? $this->data[$var] : null;
    }

    public function getAll()
    {
        $this->loadConfig();

        return $this->data;
    }

    public static function getGenders()
    {
        return static::$genders;
    }

    public static function getActiveStatus()
    {
        return static::$actives;
    }

    public static function getYesStatus()
    {
        return static::$yes;
    }

    public static function getYesLabel($var)
    {
        return array_search($var, static::$yes);
    }

    public static function getActiveLabel($var)
    {
        return array_search($var, static::$actives);
    }

    public static function getConstantValue($var)
    {
        $val = @constant((self::class).'::'.$var);

        return $val;
    }

    protected function loadConfig()
    {
        if ($this->dry) {
            $allSetting = $this->em->getRepository('AppBundle:Setting')->findAll();

            foreach ($allSetting as $record) {
                $this->data[$record->getName()] = $record->getContent();
            }

            $this->dry = false;
        }
    }
}
