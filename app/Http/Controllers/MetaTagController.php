<?php

namespace App\Http\Controllers;

use App\MetaTag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\FodderStock\Entities\Fodder_Stock;
use Modules\FodderStock\Entities\Stock_Fodder_Section;
use Modules\FodderStock\Entities\Stock_Fodder_Sub;
use Modules\Guide\Entities\Company;
use Modules\Guide\Entities\Guide_Section;
use Modules\Guide\Entities\Guide_Sub_Section;
use Modules\LocalStock\Entities\Local_Stock_Sections;
use Modules\LocalStock\Entities\Local_Stock_Sub;
use Modules\News\Entities\News;
use Modules\News\Entities\News_Section;
use Modules\Shows\Entities\Show;
use Modules\Shows\Entities\Show_Section;
use Modules\Tenders\Entities\Tender;
use Modules\Tenders\Entities\Tender_Section;
use Image;
use Session;
Use File;
class MetaTagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $metatags=MetaTag::paginate(10);
        return view('metatags.metatags',compact('metatags'));
    }

    public function selectMetaTag(Request $request)
    {
        $selection = $request->input('selection');
        $searchTerm = $request->input('search');

        $results = [];

        if ($selection == 'news') {
            $results = News::where('title', 'like', '%' . $searchTerm . '%')->get();
        }
        elseif ($selection == 'news_sections') {
            $results = News_Section::where('name', 'like', '%' . $searchTerm . '%')->get();
        }
        elseif ($selection == 'shows') {
            $results = Show::where('name', 'like', '%' . $searchTerm . '%')->get();
        }
        elseif ($selection == 'shows_sections') {
            $results = Show_Section::where('name', 'like', '%' . $searchTerm . '%')->get();
        }
        elseif ($selection == 'local_stock_section') {
            $results = Local_Stock_Sections::where('name', 'like', '%' . $searchTerm . '%')->get();
        }
        elseif ($selection == 'local_stock_subsection') {
            $results = Local_Stock_Sub::where('name', 'like', '%' . $searchTerm . '%')->get();
        }
        elseif ($selection == 'fodder_stock_section') {
            $results = Stock_Fodder_Section::where('name', 'like', '%' . $searchTerm . '%')->get();
        }
        elseif ($selection == 'fodder_stock_subsection') {
            $results = Stock_Fodder_Sub::where('name', 'like', '%' . $searchTerm . '%')->get();
        }
        elseif ($selection == 'companies') {
            $results = Company::where('name', 'like', '%' . $searchTerm . '%')->get();
        }
        elseif ($selection == 'companies_sections') {
            $results = Guide_Section::where('name', 'like', '%' . $searchTerm . '%')->get();
        }
        elseif ($selection == 'companies_sub_sections') {
            $results = Guide_Sub_Section::where('name', 'like', '%' . $searchTerm . '%')->get();
        }
        elseif ($selection == 'tenders') {
            $results = Tender::where('title', 'like', '%' . $searchTerm . '%')->get();
        }
        elseif ($selection == 'tenders_sections') {
            $results = Tender_Section::where('name', 'like', '%' . $searchTerm . '%')->get();
        }

        return response()->json($results);
    }


    public function addMetaTag(){
        $newsList = News::all();
        return view('metatags.addmeta', compact('newsList'));
    }

    public function storeMetaTag(Request $request){

        $request->validate([
            'title'           => 'required',
            'desc'          => 'required',
            'social_title'          => 'required',
            'social_desc'           => 'required',
            'selection'           => 'required',

        ]);
        $meta= new MetaTag();
        $meta->title = $request->title;
        $meta->desc = $request->desc;
        $meta->title_social = $request->social_title;
        $meta->desc_social = $request->social_desc;
        $meta->link = $request->link;
        $meta->alt = $request->alt;

        if ($request->selection == 'news') {
            $meta->news_id = $request->result;
        }
        elseif ($request->selection == 'news_sections') {
            $meta->news_section_id = $request->result;
        }
        elseif ($request->selection == 'shows') {
            $meta->show_id = $request->result;
        }
        elseif ($request->selection == 'shows_sections') {
            $meta->show_section_id = $request->result;
        }
        elseif ($request->selection == 'local_stock_section') {
            $meta->local_section_id = $request->result;
        }
        elseif ($request->selection == 'local_stock_subsection') {
            $meta->local_subsection_id = $request->result;
        }
        elseif ($request->selection == 'fodder_stock_section') {
            $meta->fodder_section_id = $request->result;
        }
        elseif ($request->selection == 'fodder_stock_subsection') {
            $meta->fodder_subsection_id = $request->result;
        }
        elseif ($request->selection == 'companies') {
            $meta->company_id = $request->result;
        }
        elseif ($request->selection == 'companies_sections') {
            $meta->company_section_id = $request->result;
        }
        elseif ($request->selection == 'companies_sub_sections') {
            $meta->company_sub_section_id = $request->result;
        }
        elseif ($request->selection == 'tenders') {
            $meta->tender_id = $request->result;
        }
        elseif ($request->selection == 'tenders_sections') {
            $meta->tender_section_id = $request->result;
        }

        if(!is_null($request->image))
        {
            $photo=$request->image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/meta/images/'.$name);
            $meta->image=$name;
        }
        $meta->save();

        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة meta tag '.$meta->name);
        return redirect()->route('addMetaTag', ['id' => $meta->id]);

    }

    # Update
    public function updateMetaTag(Request $request)
    {
        $request->validate([
            'edit_title'           => 'required',
            'edit_desc'          => 'required',
            'edit_alt'          => 'required',
//            'edit_image'          => 'required',
            'edit_title_social'          => 'required',
            'edit_desc_social'           => 'required',
            'edit_link'          => 'required',

        ]);

        $meta = MetaTag::where('id',$request->edit_id)->first();
        $meta->title = $request->edit_title;
        $meta->desc = $request->edit_desc;
        $meta->title_social = $request->edit_title_social;
        $meta->desc_social = $request->edit_desc_social;
        $meta->link = $request->edit_link;
        $meta->alt = $request->edit_alt;

        if(!is_null($request->edit_image))
        {
            File::delete('uploads/meta/images/'.$meta->edit_image);
            $photo=$request->edit_image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/meta/images/'.$name);
            $meta->image =$name;
        }
        $meta->save();


        Session::flash('success','تم الحفظ');
        MakeReport('بتحديث meta '.$meta->edit_title);
        return back();

    }


    public function deleteMetaTag(Request $request)
    {
        $meta = MetaTag::where('id',$request->id)->first();
        if($meta->image != null)
        {
            File::delete('uploads/meta/images/'.$meta->image);
        }

        MakeReport('بحذف meta tag ');
        $meta->delete();
        Session::flash('success','تم الحذف');
        return back();

    }


}
