<?php
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;
use Phalcon\Validation\Validator\Regex as RegexValidator;

class User extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="user_id", type="integer", length=11, nullable=false)
     */
    public $user_id;

    /**
     *
     * @var string
     * @Column(column="email", type="string", length=255, nullable=true)
     */
    public $email;

    /**
     *
     * @var string
     * @Column(column="pass", type="string", length=60, nullable=true)
     */
    public $pass;

    /**
     *
     * @var string
     * @Column(column="type", type="string", nullable=true)
     */
    public $type;

    /**
     *
     * @var string
     * @Column(column="name", type="string", length=45, nullable=true)
     */
    public $name;

    /**
     *
     * @var string
     * @Column(column="second_name", type="string", length=45, nullable=true)
     */
    public $second_name;

    /**
     *
     * @var string
     * @Column(column="father_name", type="string", length=45, nullable=true)
     */
    public $father_name;

    /**
     *
     * @var integer
     * @Column(column="group_group_id", type="integer", length=11, nullable=true)
     */
    public $group_group_id;

    /**
     *
     * @var string
     * @Column(column="title", type="string", length=127, nullable=true)
     */
    public $title;

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();


        $validator->add(
            "email",
            new UniquenessValidator(
                [
                    "email"   => $this,
                    "message" => "Пользователь с таким email уже существует.",
                ]
            )
        );

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Пожалуйста введите правильный email адрес.',
                ]
            )
        );
        $validator->add(
            ['email',
             'pass',
             'name',
             'second_name'],
            new PresenceOf(
                [
                    "message" => [
                            "name" => "Отчество - обязательное поле.",
                            "second_name"       => "Фамилия - обязательное поле",
                            "email"       => "Email - обязательное поле",
                            "pass"       => "Пароль - обязательное поле",
                        ]
                ]
            )
        );

        $validator->add(
            [
                "name",
                "second_name",
                "father_name"
            ],
            new RegexValidator(
                [
                    "pattern" => [
                        "name" => "/^[A-ЯЁ][a-яё]*([a-яё]|-[А-ЯЁ])[a-яё]*$/",
                        "second_name"       => "/^[A-ЯЁ][a-яё]*([a-яё]|-[А-ЯЁ])[a-яё]*$/",
                        "father_name"       => "/^[A-ЯЁ][a-яё]*([a-яё]|-[А-ЯЁ])[a-яё]*$/",
                    ],
                    "message" => [
                        "name" => "Неверно введено имя.",
                        "second_name"    => "Неверно введена фамилия.",
                        "father_name"    => "Неверно введено отчество",
                    ]
                ]
            )
        );

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("konlabu");
        $this->setSource("user");
        $this->hasManyToMany(
            'user_id',
            'UserHasCourse',
            'user_user_id', 'course_course_id',
            'Course',
            'course_id'
        );
        $this->hasMany('user_id', 'Comment', 'user_user_id', ['alias' => 'Comment']);
        $this->hasMany('user_id', 'UserHasCourse', 'user_user_id', ['alias' => 'UserHasCourse']);
        $this->belongsTo('group_group_id', 'Group', 'group_id', ['alias' => 'Group']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return User[]|User|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return User|\Phalcon\Mvc\Model\ResultInterface
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
        return 'user';
    }

}
