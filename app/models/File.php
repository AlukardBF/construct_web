<?php

class File extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="file_id", type="integer", length=11, nullable=false)
     */
    public $file_id;

    /**
     *
     * @var string
     * @Column(column="filename", type="string", length=255, nullable=true)
     */
    public $filename;

    /**
     *
     * @var string
     * @Column(column="datetime", type="string", nullable=true)
     */
    public $datetime;

    /**
     *
     * @var integer
     * @Column(column="subsection_subsection_id", type="integer", length=11, nullable=false)
     */
    public $subsection_subsection_id;

    /**
     *
     * @var integer
     * @Column(column="user_user_id", type="integer", length=11, nullable=false)
     */
    public $user_user_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("konlabu");
        $this->setSource("file");
        $this->belongsTo('subsection_subsection_id', 'Subsection', 'subsection_id', ['alias' => 'Subsection']);
        $this->belongsTo('user_user_id', 'User', 'user_id', ['alias' => 'User']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return File[]|File|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return File|\Phalcon\Mvc\Model\ResultInterface
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
        return 'file';
    }

}
