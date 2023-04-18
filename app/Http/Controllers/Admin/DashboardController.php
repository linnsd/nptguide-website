<?php
/*
 * File name: DashboardController.php
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\BookingRepository;
use App\Repositories\EarningRepository;
use App\Repositories\EProviderRepository;
use App\Repositories\UserRepository;
use App\Repositories\CategoryRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Response;
use Illuminate\View\View;

class DashboardController extends Controller
{

    /** @var  BookingRepository */
    private $bookingRepository;


    /**
     * @var UserRepository
     */
    private $userRepository;

    /** @var  EProviderRepository */
    private $eProviderRepository;
    /** @var  EarningRepository */
    private $earningRepository;

    /** @var  CategoryRepository */
    private $categoryRepository;

    public function __construct(BookingRepository $bookingRepo, UserRepository $userRepo, EarningRepository $earningRepository, EProviderRepository $eProviderRepo, CategoryRepository $categoryRepo)
    {
        parent::__construct();
        $this->bookingRepository = $bookingRepo;
        $this->userRepository = $userRepo;
        $this->eProviderRepository = $eProviderRepo;
        $this->earningRepository = $earningRepository;
        $this->categoryRepository = $categoryRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|Response|View
     */
    public function index()
    {
        $bookingsCount = $this->bookingRepository->count();
        $membersCount = $this->userRepository->count();
        $eprovidersCount = $this->eProviderRepository->count();
        $adprovidersCount = $this->eProviderRepository->where('e_provider_type_id', 2)->count();
        $eProviders = $this->eProviderRepository->limit(4);
        $earning = $this->earningRepository->all()->sum('total_earning');
        $ajaxEarningUrl = route('payments.byMonth', ['api_token' => auth()->user()->api_token]);
        $promotionCount = getPromotionCount();
        $promotions = getRecentPromotion();
        $users = getUserForDashboard();
        //        dd($ajaxEarningUrl);
        //       
        $categoryCount = $this->categoryRepository->count();
        return view('admin.dashboard.index')
            ->with("ajaxEarningUrl", $ajaxEarningUrl)
            ->with("bookingsCount", $bookingsCount)
            ->with("adprovidersCount", $adprovidersCount)
            ->with("eProvidersCount", $eprovidersCount)
            ->with("eProviders", $eProviders)
            ->with("membersCount", $membersCount)
            ->with("earning", $earning)
            ->with("promotions", $promotions)
            ->with("users", $users)
            ->with('promotionCount', $promotionCount)
            ->with("categoryCount", $categoryCount);
    }
}