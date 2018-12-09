<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;

class Group extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="group_id", type="integer", length=11, nullable=false)
     */
    public $group_id;

    /**
     *
     * @var string
     * @Column(column="name", type="string", length=45, nullable=true)
     */
    public $name;

    /**
     *
     * @var string
     * @Column(column="year", type="string", length=4, nullable=false)
     */
    public $year;

    /**
     *
     * @var string
     * @Column(column="type", type="string", nullable=false)
     */
    public $type;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("konlabu");
        $this->setSource("group");
        $this->hasMany('group_id', 'User', 'group_group_id', ['alias' => 'User']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Group[]|Group|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }


    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Group|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'group';
    }

    public function validation()
    {
        $validator = new Validation();


        $validator->add(
            ['name',
             'year',
             'type',],
            new PresenceOf(
                [
                    "message" => [
                            "name" => "Отчество - обязательное поле.",
                            "second_name"       => "Название - обязательное поле",
                            "email"       => "Год начала обучения - обязательное поле",
                            "pass"       => "Не указана форма обучения.",
                        ]
                ]
            )
        );
        $validator->add(
            "year",
            new Numericality(
                [
                    "message" => "Поле дата - введите валидный год",
                ]
            )
        );

        return $this->validate($validator);
    }

    public function getType()
    {
        switch ($this->type) {
            case 'очная':
                return 'Очная';
            case 'вечерняя':
                return 'Вечерняя';
            case 'заочная':
                return 'Заочная';
            default:
                return null;
        }
    }

}
