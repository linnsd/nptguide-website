<?php
/*
 * File name: CategoryRepository.php
 * Last modified: 2021.01.31 at 14:03:57
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Repositories;

use App\Models\EmergancyContact;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class EmergancyContactRepository
 * @package App\Repositories
 * @version January 19, 2021, 2:04 pm UTC
 *
 * @method EmergancyContact findWithoutFail($id, $columns = ['*'])
 * @method EmergancyContact find($id, $columns = ['*'])
 * @method EmergancyContact first($columns = ['*'])
 */
class EmergancyContactRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'contact_name',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return EmergancyContact::class;
    }
}
