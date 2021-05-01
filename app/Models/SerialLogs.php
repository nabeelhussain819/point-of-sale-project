<?php

namespace App\Models;

use App\Core\Base;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $product_serial_number_id
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $serial_number
 * @property string $subject_type
 * @property integer $subject_id
 * @property string $guid
 * @property User $user
 * @property ProductSerialNumber $productSerialNumber
 */
class SerialLogs extends Base
{
    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['product_serial_number_id', 'serial_number', 'subject_type', 'subject_id', 'properties', 'created_by', 'updated_by', 'guid'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updateBy()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productSerialNumber()
    {
        return $this->belongsTo('App\Models\ProductSerialNumber');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function add(int $serialId, string $serialNumber, string $subject, int $subjectId, $properties)
    {
        $log = self::fill([
            'product_serial_number_id' => $serialId,
            'serial_number' => $serialNumber,
            'subject_type' => $subject,
            'subject_id' => $subjectId,
            'properties' => $properties
        ]);
        $log->save();
        return $log;
    }

    public function getPropertiesAttribute($properties)
    {
        return json_decode($properties);
    }
}
