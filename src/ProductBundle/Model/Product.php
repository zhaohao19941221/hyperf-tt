<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-tt.
 *
 * @link     https://github.com/zhaohao19941221/hyperf-tt
 * @document https://github.com/zhaohao19941221/hyperf-tt.git
 *
 *
 */

namespace ProductBundle\Model;

use Hyperf\DbConnection\Model\Model;

class Product extends Model
{
    /**
     * The table associated with the model.
     *
     * @var ?string
     */
    protected ?string $table = 'products';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['helps_show_id', 'assistance_incident_id', 'plan_id', 'uid', 'help_amount', 'type', 'status', 'created_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'int'];
}
