<?php
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class Course extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $course_id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $description;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("konlabu");
        $this->setSource("course");
        $this->hasManyToMany(
            'course_id',
            'UserHasCourse',
            'course_course_id', 'user_user_id',
            'User',
            'user_id'
        );
        $this->hasMany('course_id', 'Subsection', 'course_course_id', ['alias' => 'Subsection']);
        $this->hasMany('course_id', 'UserHasCourse', 'course_course_id', ['alias' => 'UserHasCourse']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'course';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Course[]|Course|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Course|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function validation()
    {
        $validator = new Validation();


        $validator->add(
            'name',
            new PresenceOf(
                [
                    "message" => "У курса должно быть название."
                ]
            )
        );

        return $this->validate($validator);
    }

}
