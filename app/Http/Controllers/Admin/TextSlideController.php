<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\TextSlide;
use Illuminate\Http\Request; 
use Exception;
use Flash;
use Prettus\Validator\Exceptions\ValidatorException;
class TextSlideController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $textSlides = TextSlide::all();
        $textSlides = new TextSlide();
        $textSlides = $textSlides->orderBy('created_at','desc')->paginate(10);
        return view('admin.text_slide.index',compact('textSlides'))->with('i', (request()->input('page', 1) - 1) * 10);
        // dd("Here");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.text_slide.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $pAbout = TextSlide::create([
                'text'=>$request->text,
                'status'=>1
            ]);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.text_slide')]));

        return redirect(route('text_slides.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TextSlide  $textSlide
     * @return \Illuminate\Http\Response
     */
    public function show(TextSlide $textSlide)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TextSlide  $textSlide
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $textSlide = TextSlide::find($id);
        return view('admin.text_slide.edit',compact('textSlide'));  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TextSlide  $textSlide
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $textSlide = TextSlide::find($id);
        $textSlide = $textSlide->update([
            'text'=>$request->text
        ]);

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.text_slide')]));

        return redirect(route('text_slides.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TextSlide  $textSlide
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd($id);
        $textSlide = TextSlide::find($id)->delete();

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.text_slide')]));

        return redirect(route('text_slides.index'));
    }

    public function change_slider_text(Request $request)
    {
        // dd($request->all());
        $text_slide = TextSlide::find($request->slide_id);
        $text_slide->status = $request->status;

        $text_slide->save();
        return response()->json(['success'=>'Status change successfully.']);
    }
}
