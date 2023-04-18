<?php
/*
 * File name: CategoryRepository.php
 * Last modified: 2021.01.31 at 14:03:57
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Repositories;

use App\Models\Token;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class TokenRepository
 * @package App\Repositories
 * @version January 19, 2021, 2:04 pm UTC
 *
 * @method AboutUs findWithoutFail($id, $columns = ['*'])
 * @method AboutUs find($id, $columns = ['*'])
 * @method AboutUs first($columns = ['*'])
 */
class TokenRepository extends BaseRepository
{
    /**
     * @var array
     */
    // protected $fieldSearchable = [
    //     'title',
    // ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Token::class;
    }
}
