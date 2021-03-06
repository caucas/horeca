<?php namespace Macrobit\Horeca\Models;

use Model;
use Mail;

/**
 * Table Model
 */
class Table extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'macrobit_horeca_tables';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name'
    ];

    /**
     * @var array Jsonable fields
     */
    protected $jsonable = [
        'position'
    ];

    /**
     * @var array Visible fields
     */
    protected $visible = [
        'id', 'name', 'position'
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [
        'user'      => ['RainLab\User\Models\User'],
        'placement' => ['Macrobit\Horeca\Models\Placement']
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function beforeCreate()
    {
        $this->position = ['top' => 0, 'left' => 0];
    }

    public function beforeSave()
    {
        if (is_string($this->position))
            $this->position = json_decode($this->position);
    }

    public function book($user)
    {
        $firm = $this->placement->firm;

        $data = [
            'user' =>   $user,
            'table' =>  $this
        ];


        Mail::send('macrobit.horeca::mail.booking', $data, 
                function($message) use ($firm) {
            $message->to($firm->email, $firm->name);
        });
    }

}