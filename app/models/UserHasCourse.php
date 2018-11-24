<?php



class UserHasCourse extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $user_user_id;

    /**
     *
     * @var integer
     */
    public $course_course_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("konlabu");
        $this->setSource("user_has_course");
        $this->belongsTo('course_course_id', 'Course', 'course_id', ['alias' => 'Course']);
        $this->belongsTo('user_user_id', 'User', 'user_id', ['alias' => 'User']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'user_has_course';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserHasCourse[]|UserHasCourse|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserHasCourse|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
