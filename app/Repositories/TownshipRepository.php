<?php
/*
 * File name: CategoryRepository.php
 * Last modified: 2021.01.31 at 14:03:57
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Repositories;

use App\Models\Township;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class TownshipRepository
 * @package App\Repositories
 * @version January 19, 2021, 2:04 pm UTC
 *
 * @method Township findWithoutFail($id, $columns = ['*'])
 * @method Township find($id, $columns = ['*'])
 * @method Township first($columns = ['*'])
 */
class TownshipRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'tsh_name',
        'tsh_code'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Township::class;
    }
}
