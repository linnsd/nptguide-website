<?php
/*
 * File name: CategoryRepository.php
 * Last modified: 2021.01.31 at 14:03:57
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Repositories;

use App\Models\MinistryContact;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class MinistryContactRepository
 * @package App\Repositories
 * @version January 19, 2021, 2:04 pm UTC
 *
 * @method MinistryContact findWithoutFail($id, $columns = ['*'])
 * @method MinistryContact find($id, $columns = ['*'])
 * @method MinistryContact first($columns = ['*'])
 */
class MinistryContactRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ministry_name',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MinistryContact::class;
    }
}
