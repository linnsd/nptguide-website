<?php
/*
 * File name: CategoryRepository.php
 * Last modified: 2021.01.31 at 14:03:57
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Repositories;

use App\Models\PrivacyAbout;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class PrivacyAboutRepository
 * @package App\Repositories
 * @version January 19, 2021, 2:04 pm UTC
 *
 * @method PrivacyAbout findWithoutFail($id, $columns = ['*'])
 * @method PrivacyAbout find($id, $columns = ['*'])
 * @method PrivacyAbout first($columns = ['*'])
 */
class PrivacyAboutRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'category',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PrivacyAbout::class;
    }
}
