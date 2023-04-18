<?php
/*
 * File name: helpers.php
 * Last modified: 2021.04.12 at 19:39:57
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use InfyOm\Generator\Common\GeneratorField;
use InfyOm\Generator\Utils\GeneratorFieldsInputUtil;
use InfyOm\Generator\Utils\HTMLFieldGenerator;
use Symfony\Component\ErrorHandler\Error\FatalError;

use Illuminate\Support\Facades\Request;

use App\Models\EProvider;
use App\Models\EProviderType;
use App\Models\Township;
use App\Models\Category;
use App\Models\EProviderRating;
use App\Models\UserFavourite;
use App\Models\Promotion;
use App\Models\User;

/**
 * @param $bytes
 * @param int $precision
 * @return string
 */
function formatedSize($bytes, $precision = 1)
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . ' ' . $units[$pow];
}

function getPromotion($limit = 0)
{
    if ($limit == 0) {
        $promotions = Promotion::where('status', 1)
            ->whereDate('from_date', '<=', date('Y-m-d'))
            ->whereDate('to_date', '>=', date('Y-m-d'))
            ->orderBy('created_at', 'desc')
            ->get();
    } else {
        $promotions = Promotion::where('status', 1)
            ->whereDate('from_date', '<=', date('Y-m-d'))
            ->whereDate('to_date', '>=', date('Y-m-d'))
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    $data = [];
    foreach ($promotions as $key => $provider) {
        $pdata['id'] = $provider->id;
        $pdata['title'] = $provider->title;
        $pdata['description'] = $provider->description;
        $pdata['from_date'] = $provider->from_date;
        $pdata['to_date'] = $provider->to_date;
        $pdata['place_id'] = $provider->place_id;
        $pdata['status'] = $provider->status;
        $pdata['imgPath'] = ($provider->has_media) ? $provider->media[0]->url : "";
        array_push($data, $pdata);
    }
    return $data;
}

function getRecentPromotion()
{
    $promotions = Promotion::where('status', 1)
        ->leftjoin('e_providers', 'e_providers.id', '=', 'promotions.place_id')
        ->orderBy('promotions.created_at', 'desc')
        ->limit(5)
        ->get();

    // dd($promotions);
    $data = [];
    foreach ($promotions as $key => $provider) {
        $pdata['id'] = $provider->id;
        $pdata['title'] = $provider->title;
        $pdata['name'] = $provider->name;
        $pdata['to_date'] = $provider->to_date;
        $pdata['imgPath'] = ($provider->has_media) ? $provider->media[0]->url : "";
        array_push($data, $pdata);
    }
    return $data;
}

function getTotalRating($grade, $eprovider_id)
{
    return EProviderRating::where('eprovider_id', $eprovider_id)->where('rating_grade', $grade)->count();
}

function getTotalRatingForAll($eprovider_id)
{
    return EProviderRating::where('eprovider_id', $eprovider_id)->count();
}

function getAverageStars($eprovider_id)
{
    // AR = 1*a+2*b+3*c+4*d+5*e/(R)

    $one = EProviderRating::where('eprovider_id', $eprovider_id)->where('rating_grade', 1)->count();
    $two = EProviderRating::where('eprovider_id', $eprovider_id)->where('rating_grade', 2)->count();
    $three = EProviderRating::where('eprovider_id', $eprovider_id)->where('rating_grade', 3)->count();
    $four = EProviderRating::where('eprovider_id', $eprovider_id)->where('rating_grade', 4)->count();
    $five = EProviderRating::where('eprovider_id', $eprovider_id)->where('rating_grade', 5)->count();

    $oneStar = 1 * $one;
    $twoStar = 2 * $two;
    $threeStar = 3 * $three;
    $fourStar = 4 * $four;
    $fiveStar = 5 * $five;

    // dd($one, $two, $three, $four, $five);
    $total = $one + $two + $three + $four + $five;
    $sum = $oneStar + $twoStar + $threeStar + $fourStar + $fiveStar;
    // dd($sum);

    if ($total == 0) {
        return 0;
    } else {
        return $sum / $total;
    }
}

function getReviews($provider_id)
{
    $data = new EProviderRating();
    $data = $data->leftjoin('users', 'users.id', '=', 'e_provider_ratings.user_id')
        ->select('e_provider_ratings.*', 'users.name as username')
        ->where('e_provider_ratings.eprovider_id', $provider_id)
        ->where('e_provider_ratings.remark', '!=', '')
        ->orderBy('e_provider_ratings.created_at', 'desc')->limit(5)->get();
    return $data;
}

function getTotalRatingCount($eprovider_id)
{
    return EProviderRating::where('eprovider_id', $eprovider_id)->count();
}

function getPromotionByPlaceId($id)
{
    $promotions = Promotion::where('status', 1)
        ->where('place_id', $id)
        ->whereDate('from_date', '<=', date('Y-m-d'))
        ->whereDate('to_date', '>=', date('Y-m-d'))
        ->orderBy('created_at', 'desc')
        ->get();
    $data = [];
    foreach ($promotions as $key => $provider) {
        $pdata['id'] = $provider->id;
        $pdata['title'] = $provider->title;
        $pdata['description'] = $provider->description;
        $pdata['from_date'] = $provider->from_date;
        $pdata['to_date'] = $provider->to_date;
        $pdata['place_id'] = $provider->place_id;
        $pdata['status'] = $provider->status;
        $pdata['imgPath'] = ($provider->has_media) ? $provider->media[0]->url : "";
        array_push($data, $pdata);
    }
    return $data;
}

function getPromotionCount()
{
    $promotions = Promotion::where('status', 1)
        ->whereDate('from_date', '<=', date('Y-m-d'))
        ->whereDate('to_date', '>=', date('Y-m-d'))
        ->orderBy('created_at', 'desc')
        ->count();
    return $promotions;
}

function getUserForDashboard()
{
    $users = User::orderBy('created_at', 'desc')->limit(5)->get();
    return $users;
}

// public function get_img($shop_id)
// {
//     $e_providers = $this->eproviderRepository->find($shop_id);

//     // dd($e_providers->has);
//     $img_path = ($e_providers->has_media) ? $e_providers->media[0]->url : "";
//     return $img_path;
// }

function provider_types()
{
    $e_provider_types = EProviderType::all();
    return $e_provider_types;
}

function isFav($eprovider_id)
{
    $user_id = is_null(Auth()->user()) ? 0 : Auth()->user()->id;
    return UserFavourite::where('user_id', $user_id)->where('eprovider_id', $eprovider_id)->count();
}

function checkFav($eprovider_id, $user_id)
{
    return UserFavourite::where('user_id', $user_id)->where('eprovider_id', $eprovider_id)->count();
}

function getRatingCount($id)
{
    $count = EProviderRating::where('eprovider_id', $id)->count();
    return $count;
}

function townships()
{
    $townships = Township::all();
    return $townships;
}

function categories()
{
    $categories = Category::all();
    return $categories;
}

function e_providers()
{
    $e_providers = EProvider::all();
    return $e_providers;
}

function checkAlreadyReview($user_id, $provider_id)
{
    $count = EProviderRating::where('user_id', $user_id)->where('eprovider_id', $provider_id)->count();
    return ($count != 0) ? false : true;
}



function getMediaColumn($mediaModel, $mediaCollectionName = '', $optionClass = '', $mediaThumbnail = 'icon')
{
    $optionClass = $optionClass == '' ? ' rounded ' : $optionClass;

    if ($mediaModel->hasMedia($mediaCollectionName)) {
        return "<img class='" . $optionClass . "' style='height:50px' src='" . $mediaModel->getFirstMediaUrl($mediaCollectionName, $mediaThumbnail) . "' alt='" . $mediaModel->getFirstMedia($mediaCollectionName)->name . "'>";
    } else {
        return "<img class='" . $optionClass . "' style='height:50px' src='" . asset('images/image_default.png') . "' alt='image_default'>";
    }
}

/**
 * @param $modelObject
 * @param string $attributeName
 * @return null|string|string[]
 */
function getDateColumn($modelObject, $attributeName = 'updated_at')
{
    if (setting('is_human_date_format', false)) {
        $html = '<span data-toggle="tooltip" data-placement="left" title="${date}">${dateHuman}</span>';
    } else {
        $html = '<span data-toggle="tooltip" data-placement="left" title="${dateHuman}">${date}</span>';
    }
    if (!isset($modelObject[$attributeName])) {
        return '';
    }
    $dateObj = new Carbon($modelObject[$attributeName]);
    $replace = preg_replace('/\$\{date\}/', $dateObj->format(setting('date_format', 'l jS F Y (h:i:s)')), $html);
    $replace = preg_replace('/\$\{dateHuman\}/', $dateObj->diffForHumans(), $replace);
    return $replace;
}

function getPriceColumn($modelObject, $attributeName = 'price')
{

    if ($modelObject[$attributeName] != null && strlen($modelObject[$attributeName]) > 0) {
        $modelObject[$attributeName] = number_format((float)$modelObject[$attributeName], 2, '.', '');
        if (setting('currency_right', false) != false) {
            return $modelObject[$attributeName] . "<span>" . setting('default_currency') . "</span>";
        } else {
            return "<span>" . setting('default_currency') . "</span>" . $modelObject[$attributeName];
        }
    }
    return '-';
}

function getPrice($price = 0)
{
    if (setting('currency_right', false) != false) {
        return number_format((float)$price, 2, '.', '') . "<span>" . setting('default_currency') . "</span>";
    } else {
        return "<span>" . setting('default_currency') . "</span>" . number_format((float)$price, 2, '.', ' ');
    }
}

/**
 * generate boolean column for datatable
 * @param $column
 * @return string
 */
function getBooleanColumn($column, $attributeName)
{
    if (isset($column)) {
        if (is_null($column[$attributeName])) {
            return "<span class='badge badge-danger'>No</span>";
        } 

        if($column[$attributeName]) {
            return "<span class='badge badge-success'>Yes</span>";
        }else {
              return "<span class='badge badge-danger'>No</span>";
        }
    }
}

/**
 * generate not boolean column for datatable
 * @param $column
 * @return string
 */
function getNotBooleanColumn($column, $attributeName)
{
    if (isset($column)) {
        if ($column[$attributeName]) {
            return "<span class='badge badge-danger'>" . trans('lang.yes') . "</span>";
        } else {
            return "<span class='badge badge-success'>" . trans('lang.no') . "</span>";
        }
    }
    return "";
}

/**
 * generate color column for datatable
 * @param $column
 * @return string
 */
function getColorColumn($column, $attributeName)
{
    if (isset($column)) {
        return "<span class='badge badge-default p-2' style='background-color:$column[$attributeName]'>" . $column[$attributeName] . "</span>";
    }
    return "";
}

/**
 * generate striped html column for datatable
 * @param $column
 * @return string
 */
function getStripedHtmlColumn($column, $attributeName, $limit = 40)
{
    if (isset($column)) {
        if ($limit == 0) {
            return strip_tags($column[$attributeName]);
        }
        return Str::limit(strip_tags($column[$attributeName]), $limit);
    }
    return "";
}


/**
 * generate order payment column for datatable
 * @param $column
 * @return string
 */
function getPayment($column, $attributeName)
{
    if (isset($column) && $column[$attributeName]) {
        return "<span class='badge badge-success'>" . $column[$attributeName] . "</span> ";
    } else {
        return "<span class='badge badge-danger'>" . trans('lang.order_not_paid') . "</span>";
    }
}

/**
 * @param array $array
 * @param $baseUrl
 * @param string $idAttribute
 * @param string $titleAttribute
 * @return string
 */
function getLinksColumn($array = [], $baseUrl = '', $idAttribute = 'id', $titleAttribute = 'title')
{
    $html = '<a href="${href}" class="text-bold text-dark">${title}</a>';
    $result = [];
    foreach ($array as $link) {
        $replace = preg_replace('/\$\{href\}/', url($baseUrl, $link[$idAttribute]), $html);
        $replace = preg_replace('/\$\{title\}/', $link[$titleAttribute], $replace);
        $result[] = $replace;
    }
    return implode(', ', $result);
}

/**
 * @param array $array
 * @param $routeName
 * @param string $idAttribute
 * @param string $titleAttribute
 * @return string
 */
function getLinksColumnByRouteName($array = [], $routeName = '', $idAttribute = 'id', $titleAttribute = 'title')
{
    $html = '<a href="${href}" class="text-bold text-dark">${title}</a>';
    $result = [];
    foreach ($array as $link) {
        if (!empty($link)) {
            $replace = preg_replace('/\$\{href\}/', route($routeName, $link[$idAttribute]), $html);
            $replace = preg_replace('/\$\{title\}/', $link[$titleAttribute], $replace);
            $result[] = $replace;
        }
    }
    return implode(', ', $result);
}

function getArrayColumn($array = [], $titleAttribute = 'title', $optionClass = '', $separator = ', ')
{
    $result = [];
    foreach ($array as $link) {
        $title = $link[$titleAttribute];
        //        $replace = preg_replace('/\$\{href\}/', url($baseUrl, $link[$idAttribute]), $html);
        //        $replace = preg_replace('/\$\{title\}/', $link[$titleAttribute], $replace);
        $html = "<span class='{$optionClass}'>{$title}</span>";
        $result[] = $html;
    }
    return implode($separator, $result);
}

function getEmailColumn($column, $attributeName)
{
    if (isset($column)) {
        if ($column[$attributeName]) {
            return "<a class='btn btn-outline-secondary btn-sm' href='mailto:" . $column[$attributeName] . "'><i class='fa fa-envelope mr-1'></i>" . $column[$attributeName] . "</a>";
        } else {
            return '';
        }
    }
}

/**
 * get available languages on the application
 */
function getAvailableLanguages()
{
    $dir = base_path('resources/lang');
    $languages = array_diff(scandir($dir), array('..', '.'));
    $languages = array_map(function ($value) {
        return ['id' => $value, 'value' => trans('lang.app_setting_' . $value)];
    }, $languages);

    return array_column($languages, 'value', 'id');
}

/**
 * get all languages
 */

function getLanguages()
{

    return array(
        'en' => 'English',
        'mm' => 'Myanmar'
    );
}

function generateCustomField($fields, $fieldsValues = null)
{
    $htmlFields = [];
    $startSeparator = '<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">';
    $endSeparator = '</div>';
    foreach ($fields as $field) {
        $dynamicVars = [
            '$RANDOM_VARIABLE$' => 'var' . time() . rand() . 'ble',
            '$FIELD_NAME$' => $field->name,
            '$DISABLED$' => $field->disabled === true ? '"disabled" => "disabled",' : '',
            '$REQUIRED$' => $field->required === true ? '"required" => "required",' : '',
            '$MODEL_NAME_SNAKE$' => getOnlyClassName($field->custom_field_model),
            '$FIELD_VALUE$' => 'null',
            '$INPUT_ARR_SELECTED$' => '[]',

        ];
        $gf = new GeneratorField();
        if ($fieldsValues) {
            foreach ($fieldsValues as $value) {
                if ($field->id === $value->customField->id) {
                    $dynamicVars['$INPUT_ARR_SELECTED$'] = $value->value ? $value->value : '[]';
                    $dynamicVars['$FIELD_VALUE$'] = '\'' . addslashes($value->value) . '\'';
                    $gf->validations[] = $value->value;
                    continue;
                }
            }
        }
        // dd($gf->validations);
        $gf->htmlType = $field['type'];
        $gf->htmlValues = $field['values'];
        $gf->dbInput = '';
        if ($field['type'] === 'selects') {
            $gf->htmlType = 'select';
            $gf->dbInput = 'hidden,mtm';
        }
        $fieldTemplate = HTMLFieldGenerator::generateCustomFieldHTML($gf, config('infyom.laravel_generator.templates', 'adminlte-templates'));


        if (!empty($fieldTemplate)) {
            foreach ($dynamicVars as $variable => $value) {
                $fieldTemplate = str_replace($variable, $value, $fieldTemplate);
            }
            $htmlFields[] = $fieldTemplate;
        }
        //    dd($fieldTemplate);
    }
    foreach ($htmlFields as $index => $field) {
        if (round(count($htmlFields) / 2) == $index + 1) {
            $htmlFields[$index] = $htmlFields[$index] . "\n" . $endSeparator . "\n" . $startSeparator;
        }
    }
    $htmlFieldsString = implode("\n\n", $htmlFields);
    $htmlFieldsString = $startSeparator . "\n" . $htmlFieldsString . "\n" . $endSeparator;
    //    dd($htmlFieldsString);
    $renderedHtml = "";
    try {
        $renderedHtml = render(Blade::compileString($htmlFieldsString));
        //        dd($renderedHtml);
    } catch (FatalError $e) {
    }
    return $renderedHtml;
}

/**
 * render php code in string give with compiling data
 *
 * @param $__php
 * @param null $__data
 * @return string
 * @throws FatalError
 */
function render($__php, $__data = null)
{
    $obLevel = ob_get_level();
    ob_start();
    if ($__data) {
        optionct($__data, EXTR_SKIP);
    }
    try {
        eval('?' . '>' . $__php);
    } catch (Exception $e) {
        while (ob_get_level() > $obLevel) ob_end_clean();
        throw $e;
    } catch (Throwable $e) {
        while (ob_get_level() > $obLevel) ob_end_clean();
        throw new FatalError($e->getMessage(), 500, []);
    }
    return ob_get_clean();
}

/**
 * get custom field value from custom fields collection given
 * @param null $customFields
 * @param $request
 * @return array
 */
function getCustomFieldsValues($customFields = null, $request = null)
{

    if (!$customFields) {
        return [];
    }
    if (!$request) {
        return [];
    }
    $customFieldsValues = [];
    foreach ($customFields as $cf) {
        $value = $request->input($cf->name);
        $view = $value;
        $fieldType = $cf->type;
        if ($fieldType === 'selects') {
            $view = GeneratorFieldsInputUtil::prepareKeyValueArrFromLabelValueStr($cf->values);
            $view = array_filter($view, function ($v) use ($value) {
                return in_array($v, $value);
            });
            $view = implode(', ', array_flip($view));
            $value = json_encode($value);
        } elseif ($fieldType === 'select' || $fieldType === 'radio') {
            $view = GeneratorFieldsInputUtil::prepareKeyValueArrFromLabelValueStr($cf->values);
            $view = array_flip($view)[$value];
        } elseif ($fieldType === 'boolean') {
            $view = getBooleanColumn(['0' => $view], '0');
        } elseif ($fieldType === 'password') {
            $view = str_repeat('•', strlen($value));
            $value = bcrypt($value);
        } elseif ($fieldType === 'date') {
            $view = getDateColumn(['date' => $view], 'date');
        } elseif ($fieldType === 'email') {
            $view = getEmailColumn(['email' => $view], 'email');
        } elseif ($fieldType === 'textarea') {
            $view = strip_tags($view);
        }


        $customFieldsValues[] = [
            'custom_field_id' => $cf->id,
            'value' => $value,
            'view' => $view
        ];
    }

    return $customFieldsValues;
}


/**
 * convert an array to assoc array using one attribute in the array
 * 0 => [
 *      name => 'The_Name'
 *      title => 'TITLE'
 * ]
 *
 * to
 *
 * The_Name => [
 *      name => 'The_Name'
 *      title => 'TITLE'
 * ]
 */
function convertToAssoc($collection, $key)
{
    $newCollection = [];
    foreach ($collection as $c) {
        $newCollection[$c[$key]] = $c;
    }
    return $newCollection;
}

/**
 * Get class name by giving the full name of th class
 * Ex:
 * $fullClassName = App\Models\UserModel
 * $isSnake = true
 * return
 * user_model
 * $fullClassName = App\Models\UserModel
 * $isSnake = false
 * return
 * UserModel
 * @param $fullClassName
 * @param bool $isSnake
 * @return mixed|string
 */
function getOnlyClassName($fullClassName, $isSnake = true)
{
    $modelNames = preg_split('/\\\\/', $fullClassName);
    if ($isSnake) {
        return Str::snake(end($modelNames));
    }
    return end($modelNames);
}

function getModelsClasses(string $dir, array $excepts = null)
{
    if ($excepts === null) {
        $excepts = [
            'App\Models\Upload',
            'App\Models\CustomField',
            'App\Models\Media',
            'App\Models\CustomFieldValue',
        ];
    }
    $customFieldModels = array();
    $cdir = scandir($dir);
    foreach ($cdir as $key => $value) {
        if (!in_array($value, array(".", ".."))) {
            if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                $customFieldModels[$value] = getModelsClasses($dir . DIRECTORY_SEPARATOR . $value);
            } else {
                $fullClassName = "App\\Models\\" . basename($value, '.php');
                if (!in_array($fullClassName, $excepts)) {
                    $customFieldModels[$fullClassName] = trans('lang.' . Str::snake(basename($value, '.php')) . '_plural');
                }
            }
        }
    }
    return $customFieldModels;
}

function getNeededArray($delimiter = '|', $string = '', $input = '')
{
    $array = explode($delimiter, $string, 2);
    if (count($array) === 1) {
        return [$array[0] => $input];
    } else {
        return [$array[0] => getNeededArray($delimiter, $array[1], $input)];
    }
}

/**
 * get Days array with keys and translated values
 * @return array
 */
function getDaysArray()
{
    $daysValues = array_values(Carbon::getDays());
    $daysKeys = array_map(function ($element) {
        return Str::lower($element);
    }, $daysValues);

    $daysValuesTranslated = array_map(function ($element) {
        return translateDay($element);
    }, $daysKeys);

    return array_combine($daysKeys, $daysValuesTranslated);
}

/**
 * @param string $element
 * @return string
 */
function translateDay(string $element): string
{
    return Str::title(Carbon::createFromIsoFormat('dddd', $element)->isoFormat('dddd'));
}

function isJson($string)
{
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}

/*
 * Const general
 */

const PRICE_RANGE = [
    "" => "None",
    "0" => "Free",
    "1" => "$",
    "2" => "$$",
    "3" => "$$$",
    "4" => "$$$$",
];

const DAYS = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];

const SOCIAL_LIST = [
    'Facebook' => [
        'icon' => 'la la-facebook-f',
        'name' => 'Facebook',
        'base_url' => 'https://www.facebook.com/'
    ],
    'Instagram' => [
        'icon' => 'la la-instagram',
        'name' => 'Instagram',
        'base_url' => 'https://www.instagram.com/'
    ],
    'Twitter' => [
        'icon' => 'la la-twitter',
        'name' => 'Twitter',
        'base_url' => 'https://twitter.com/'
    ],
    'Youtube' => [
        'icon' => 'la la-youtube',
        'name' => 'Youtube',
        'base_url' => 'https://www.youtube.com/'
    ],
    'Pinterest' => [
        'icon' => 'la la-pinterest',
        'name' => 'Pinterest',
        'base_url' => 'https://www.pinterest.com/'
    ],
    'Snapchat' => [
        'icon' => 'la la-snapchat',
        'name' => 'Snapchat',
        'base_url' => 'https://www.snapchat.com/'
    ]
];

const STATUS = [
    0 => "Deactive",
    1 => "Active",
    2 => "Pending",
    4 => "Deleted",
];

function isRoute($name = '')
{
    if (!$name || (is_array($name) && !count($name)) || !Request::route()) {
        return false;
    }
    if (is_array($name)) {
        return in_array(Request::route()->getName(), $name);
    }
    return Request::route()->getName() === $name;
}

/**
 * function helper
 * @return \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
 */
function user()
{
    return \Illuminate\Support\Facades\Auth::user();
}

function isActiveMenu($router_name)
{
    if (isRoute($router_name)) {
        return "active";
    }
    return "";
}

function isChecked($val1, $val2)
{
    if (is_array($val2)) {
        if (in_array($val1, $val2)) {
            return "checked";
        } else {
            return "";
        }
    } else {
        if ($val1 == $val2) {
            return "checked";
        } else {
            return "";
        }
    }
}

function isSelected($val1, $val2)
{
    if (is_array($val2)) {
        if (in_array($val1, $val2)) {
            return "selected";
        } else {
            return "";
        }
    } else {
        if ($val1 == $val2) {
            return "selected";
        } else {
            return "";
        }
    }
}

// function isActive($val1, $val2)
// {
//     if (is_array($val2)) {
//         if (in_array($val1, $val2)) {
//             return "active";
//         } else {
//             return "";
//         }
//     } else {
//         if ($val1 === $val2) {
//             return "active";
//         } else {
//             return "";
//         }
//     }
// }


function isDisabled($val1, $val2)
{
    if ($val1 === $val2) {
        return "disabled";
    } else {
        return "";
    }
}

function isMobile()
{
    $useragent = $_SERVER['HTTP_USER_AGENT'];

    if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
        return true;
    }
    return false;
}

function generateRandomString($length = 5)
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function getImageUrl($image_file)
{
    if ($image_file) {
        return asset("uploads/{$image_file}");
    }
    return "https://via.placeholder.com/300x300?text=GOLO";
}

function getUserAvatar($image_file)
{
    if ($image_file) {
        return "/uploads/{$image_file}";
    }
    return "/assets/images/default_avatar.svg";
}

function formatDate($date, $format)
{
    return Carbon::parse($date)->format($format);
}

if (!function_exists('setting')) {

    function setting($key, $default = null)
    {
        if (is_null($key)) {
            return new \App\Models\Setting();
        }

        if (is_array($key)) {
            return \App\Models\Setting::set($key[0], $key[1]);
        }

        $value = \App\Models\Setting::get($key);

        return is_null($value) ? value($default) : $value;
    }
}

function getSlug($request, $key)
{
    $language_default = \App\Models\Language::query()
        ->where('is_default', \App\Models\Language::IS_DEFAULT)
        ->select('code')
        ->first();
    $language_code = $language_default->code;
    $value = $request[$language_code][$key];
    $slug = \Illuminate\Support\Str::slug($value);
    return $slug;
}

function SEOMeta($title = '', $description = '', $image = null, $canonical = '', $type = 'website')
{
    $image = $image ? $image : asset('images/common/default-cover-fb-1.jpg');

    SEO::setTitle($title);
    SEO::setDescription($description);
    SEO::opengraph()->setUrl(url()->current());
    SEO::setCanonical(url()->current());
    SEO::opengraph()->addProperty('type', $type);
    SEO::opengraph()->addProperty("image", $image);
    SEO::opengraph()->addProperty("site_name", setting('app_name'));
}

function flagImageUrl($language_code)
{
    return asset("assets/images/flags/{$language_code}.png");
}

function getYoutubeId($url)
{
    //$url = "http://www.youtube.com/watch?v=C4kxS1ksqtw&feature=relate";
    parse_str(parse_url($url, PHP_URL_QUERY), $my_array_of_vars);
    return $my_array_of_vars ? $my_array_of_vars['v'] : '';
}