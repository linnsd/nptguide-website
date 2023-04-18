<?php
/*
 * File name: CategoryRepository.php
 * Last modified: 2021.01.31 at 14:03:57
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Repositories;

use App\Models\PrivacyPolicy;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class PrivacyPolicyRepository
 * @package App\Repositories
 * @version January 19, 2021, 2:04 pm UTC
 *
 * @method PrivacyPolicy findWithoutFail($id, $columns = ['*'])
 * @method PrivacyPolicy find($id, $columns = ['*'])
 * @method PrivacyPolicy first($columns = ['*'])
 */
class PrivacyPolicyRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'content'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PrivacyPolicy::class;
    }
}
