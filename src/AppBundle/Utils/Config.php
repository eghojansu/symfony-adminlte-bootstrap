<?php

namespace AppBundle\Utils;

use Doctrine\ORM\EntityManager;

class Config
{
    const APP_YEAR = 2017;
    const APP_AUTHOR = 'Eko Kurniawan';
    const APP_VERSION = '1.0.0';

    const ROLE_USER = "ROLE_USER";
    const ROLE_ADMIN = "ROLE_ADMIN";
    const ROLE_SUPER_ADMIN = "ROLE_SUPER_ADMIN";

    const GENDER_MALE = 'Laki-laki';
    const GENDER_FEMALE = 'Perempuan';

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const STATUS_YES = 1;
    const STATUS_NO = 0;

    const PERPAGE = 10;
    const DEFAULT_TIMEZONE = 'Asia/Jakarta';

    protected static $list = [
        'role'=>[
            self::ROLE_USER=>self::ROLE_USER,
            self::ROLE_ADMIN=>self::ROLE_ADMIN,
            self::ROLE_SUPER_ADMIN=>self::ROLE_SUPER_ADMIN,
        ],
        'gender'=>[
            self::GENDER_MALE=>self::GENDER_MALE,
            self::GENDER_FEMALE=>self::GENDER_FEMALE,
        ],
        'active'=>[
            'Aktif'=>self::STATUS_ACTIVE,
            'Tidak Aktif'=>self::STATUS_INACTIVE,
        ],
        'yes'=>[
            'Ya'=>self::STATUS_YES,
            'Tidak'=>self::STATUS_NO,
        ],
    ];

    protected $em;
    protected $data = [
        'app_title' => 'App Title',
        'app_alias' => 'App',
        'app_name' => 'App Name',
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

    public static function getTimezone()
    {
        return self::DEFAULT_TIMEZONE;
    }

    public static function getList($var)
    {
        return array_key_exists($var, self::$list) ? self::$list[$var] : null;
    }

    public static function getLabel($var, $key)
    {
        return array_key_exists($var, self::$list) ? (array_key_exists($key, self::$list[$var])? self::$list[$var][$key]: null) : null;
    }

    public static function getRoles()
    {
        return self::$list['role'];
    }

    public static function getGenders()
    {
        return self::$list['gender'];
    }

    public static function getActiveStatus()
    {
        return self::$list['active'];
    }

    public static function getYesStatus()
    {
        return self::$list['yes'];
    }

    public static function getYesLabel($var)
    {
        return array_search($var, self::$list['yes']);
    }

    public static function getActiveLabel($var)
    {
        return array_search($var, self::$list['active']);
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
