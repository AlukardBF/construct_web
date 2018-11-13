<?php



class Subsection extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $subsection_id;

    /**
     *
     * @var string
     */
    public $theme;

    /**
     *
     * @var string
     */
    public $grade;

    /**
     *
     * @var string
     */
    public $discription;

    /**
     *
     * @var string
     */
    public $section;

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
        $this->setSource("subsection");
        $this->hasMany('subsection_id', 'construct\konlabu\models\Comment', 'subsection_subsection_id', ['alias' => 'Comment']);
        $this->hasMany('subsection_id', 'construct\konlabu\models\File', 'subsection_subsection_id', ['alias' => 'File']);
        $this->belongsTo('course_course_id', 'construct\konlabu\models\Course', 'course_id', ['alias' => 'Course']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'subsection';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Subsection[]|Subsection|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Subsection|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
