<?php



use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class User extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $pass;

    /**
     *
     * @var string
     */
    public $type;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $second_name;

    /**
     *
     * @var string
     */
    public $father_name;

    /**
     *
     * @var integer
     */
    public $group_group_id;

    /**
     *
     * @var string
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
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Please enter a correct email address',
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
        $this->hasMany('user_id', 'construct\konlabu\models\Comment', 'user_user_id', ['alias' => 'Comment']);
        $this->hasMany('user_id', 'construct\konlabu\models\UserHasCourse', 'user_user_id', ['alias' => 'UserHasCourse']);
        $this->belongsTo('group_group_id', 'construct\konlabu\models\Group', 'group_id', ['alias' => 'Group']);
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

}
