<?php



class Grade extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $grade_id;

    /**
     *
     * @var string
     */
    public $grade;

    /**
     *
     * @var integer
     */
    public $user_user_id;

    /**
     *
     * @var integer
     */
    public $subsection_subsection_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("konlabu");
        $this->setSource("grade");
        $this->belongsTo('subsection_subsection_id', 'Subsection', 'subsection_id', ['alias' => 'Subsection']);
        $this->belongsTo('user_user_id', 'User', 'user_id', ['alias' => 'User']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'grade';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Grade[]|Grade|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Grade|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
