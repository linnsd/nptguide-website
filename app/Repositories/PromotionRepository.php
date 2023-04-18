<?php
/*
 * File name: PromotionRepository.php
 * Last modified: 2021.03.25 at 18:04:58
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Repositories;

use App\Models\Promotion;
use InfyOm\Generator\Common\BaseRepository;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class PromotionRepository
 * @package App\Repositories
 * @version January 19, 2021, 1:59 pm UTC
 *
 * @method Promotion findWithoutFail($id, $columns = ['*'])
 * @method Promotion find($id, $columns = ['*'])
 * @method Promotion first($columns = ['*'])
 */
class PromotionRepository extends BaseRepository implements CacheableInterface
{

    use CacheableRepository;

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'e_provider_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Promotion::class;
    }

    /**
     * @return array
     */
    public function groupedByEProviders(): array
    {
        $eServices = [];
        foreach ($this->all() as $model) {
            if (!empty($model->eProvider)) {
                $eServices[$model->eProvider->name][$model->id] = $model->name;
            }
        }
        return $eServices;
    }
}
