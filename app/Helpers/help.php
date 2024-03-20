<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\SmsEmailNotification;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM as FCM;
use LaravelFCM\Message\Topics;

use App\User;
use App\Role;
use App\SiteSetting;
use App\Report;

use App\App_Notification;
use App\Configuration;
use App\Inbox;

use App\Mail\ManagerMail;

// use Carbon;
// use DateTime;

use Illuminate\Support\Facades\DB;

function Home()
{
	
    $colors = [
    'bg-info',
    'bg-secondary',
    'bg-success',
    'bg-warning',
    'bg-danger',
    'bg-gray-dark',
    'bg-indigo',
    'bg-purple',
    'bg-fuchsia',
    'bg-pink',
    'bg-maroon',
    'bg-orange',
    'bg-lime',
    'bg-teal',
    'bg-olive',
    ];
    $home =[

        [
            'title' =>'عدد الرسائل ',
            'count'=>Inbox::count(),
            'icon' =>'<i class="fas fa-envelope"></i>',
            'color'=>$colors[array_rand($colors)],
            'route'=>'inbox'
        ],
        [
        'title' =>'عدد المستخدمين',
        'count'=>User::count(),
        'icon' =>'<i class="fas fa-address-book"></i>',
        'color'=>$colors[array_rand($colors)],
        'route'=>'users'
        ],

        [
            'title' =>'عدد الصلاحيات',
            'count'=>Role::count(),
            'icon' =>'<i class="fas fa-biohazard"></i>',
            'color'=>$colors[array_rand($colors)],
            'route'=>'permissions'
        ],
            
        [
        'title' =>'عدد التقارير',
        'count'=>Report::count(),
        'icon' =>'<i class="fas fa-clipboard"></i>',
        'color'=>$colors[array_rand($colors)],
        'route'=>'reports'
        ]
        
      
    ];

    return $blocks[]=$home; 
}



#role name
function Role()
{
    $role = Role::findOrFail(Auth::user()->role);
    if($role)
    {
        return $role->role;
    }else{
        return 'عضو';
    }
}

#messages notification
function Notification()
{
	// $messages = Contact::where('showOrNow',0)->latest()->get(); 
	return '$messages';
}

#upload image base64
function save_img($base64_img, $img_name, $path)
{
    $image = base64_decode($base64_img);
    $pathh = $_SERVER['DOCUMENT_ROOT'].'/'. $path .'volita'. $img_name.'.png';
    file_put_contents($pathh, $image);
}

# report
function MakeReport($event)
{
	$report = new Report;
    $report->user_id = Auth::user()->id;
    $report->event   = 'قام '.Auth::user()->name .' '.$event;
    $report->save();
}

# make order report
function MakeOrderReport($event,$order_id)
{
    $report = new Order_Report;
    $report->user_id    = Auth::user()->id;
    $report->order_id   = $order_id;
    $report->report     = $event;
    $report->save();
}

# make content report
function MakeContentReport($event,$product_color_id)
{
    $report = new Content_Report;
    $report->user_id    = Auth::user()->id;
    $report->product_color_id   = $product_color_id;
    $report->event      = $event;
    $report->save();
}


#current route
function currentRoute()
{
    $routes = Route::getRoutes();
    foreach ($routes as $value)
    {
        if($value->getName() === Route::currentRouteName()) 
        {
            if(isset($value->getAction()['title']))
            {
                if(isset($value->getAction()['icon']))
                {
                    echo $value->getAction()['icon'].' '.$value->getAction()['title'] ;
                }else{
                    echo $value->getAction()['title'] ;
                }
                # echo $value->getAction()['icon'] ;
            }
            // return $value->getAction() ;
        }
    }
}

#email colors
function EmailColors()
{
    $html = Html::select('email_header_color','email_footer_color','email_font_color')->first();
    $setting = SiteSetting::first();
    return ['email'=>$html,'site_name'=>$setting->site_name_ar];
}

# generate uniqe code
function UniqCode(){
    $code = md5(uniqid(rand(), true));
    $array_code = str_split($code);
    return implode("",array_slice($array_code,22));
}

#setting and html
function SettingAndHtml()
{
    $setting = SiteSetting::first();
    $html    = Html::first();
    $SettingAndHtml = ['setting'=>$setting,'html'=>$html];
    return $SettingAndHtml;
}


function Nearest($lat,$lng,$section_id)
{

   $query_sql = "SELECT *, ( 6371* acos( cos( radians(
       $lat) ) * cos( radians(
       stores.lat ) ) * cos( radians(
       stores.lng ) - radians(
       $lng) ) + sin( radians(
       $lat) ) * sin( radians(
       stores.lat ) ) ) ) AS distance
       FROM stores WHERE stores.status= 1 AND stores.section_id=$section_id ORDER BY distance ";

   $result = DB::select($query_sql);
   return collect($result);
}


function Noty()
{
    $Newnoti = DB::table('notifications')->where([['notifiable_id',Auth::id()],['read_at',Null]])->count();
    return $Newnoti;
}

function ConvertToBaseCurrency($country_id,$amount)
{
    $country = Country::findOrFail($country_id);
    $price =  ( $country->refactor + $country->extra ) * $amount;
    return round($price);
}



# kayan filter
function KayanFilter(
    $discount,
    $options_id,
    $colors_id,
    $marks_ids,
    $partners_id,
    $min_price,
    $max_price,
    $ids,
    $count,
    $is_best,
    $sort,
    $text,
    $type,
    $banner,
    $banner_sort,
    $country
)
{
    $products = Product_Color::with
    ([
        'OptionGroups.Options',
        'Product',
    ])->where('section_id','!=',null)->whereHas('Product',function($q){$q->where('section_id','!=',null);});

    if(!$banner)
    {
        if($is_best && $is_best == 1)
        {
            $products = $products->whereHas('Product',function($q) use ($is_best){
                return $q->where('is_best',$is_best);
            });
        }elseif($is_best == 2)
        {
            $products = $products->whereHas('Product',function($q) use ($is_best){
                return $q->whereIn('is_best',[0,1]);
            }); 
        }

        if($text && !is_null($text))
        {
            $products = $products->where('color_name_ar','LIKE', '%' . $text . '%')
            ->orWhere('color_name_en','LIKE', '%' . $text . '%');
        }

        if($ids && !is_null($ids))
        {
            $products = $products->whereIn('section_id',$ids);
        }

        if($colors_id && !empty($colors_id))
        {
            $products = $products->whereIn('color_id',$colors_id);
        }


        if($discount)
        {
            $products = $products->where('discount','>=',$discount);
        }else{
            $products = $products->where('discount','>=',0);
        }

        // if($min_price)
        // {
        //     $products = $products->whereHas('Product',function($q) use ($min_price){
        //         return $q->where('price','<=',$min_price);
        //     });
        // }

        if($max_price)
        {
            $our_country = Country::findOrFail(3);
            $price =  ( $our_country->refactor + $our_country->extra ) * $max_price;
            $reyal = round($price);
            $products = $products->whereHas('Product',function($q) use ($reyal){
                return $q->where('price','<=',$reyal);
            });
        }


        if($marks_ids && !empty($marks_ids))
        {
            $products = $products->whereHas('Product',function($q) use ($marks_ids){
                return $q->whereIn('mark_id',$marks_ids);
            });
        }

        if($partners_id && !empty($partners_id))
        {
            $products = $products->whereHas('Product',function($q) use ($partners_id){
                return $q->whereIn('partner_id',$partners_id);
            });
        }

        if($options_id && !empty($options_id))
        {
             $products = $products->whereHas('OptionGroups.Options',function($q) use ($options_id){
                return $q->whereIn('option_id',$options_id);
            });
        }
    }else{

        $banner_ids         = (array)json_decode($banner->sections_id);
        $banner_colors_id   = (array)json_decode($banner->colors_id);
        $banner_marks_ids   = (array)json_decode($banner->marks_ids);
        $banner_partners_id = (array)json_decode($banner->partners_id);
        $banner_options_id = (array)json_decode($banner->options_id);
        $banner_max_price   = $banner->max_price;
        $banner_discount   = $banner->discount;

        if($banner_ids && !is_null($banner_ids))
        {
            $products = $products->whereIn('section_id',$banner_ids);
        }

        if($banner_colors_id && !empty($banner_colors_id))
        {
            $products = $products->whereIn('color_id',$banner_colors_id);
        }

        if($banner_discount)
        {
            $products = $products->where('discount','>=',$banner_discount);
        }else{
            $products = $products->where('discount','>=',0);
        }

        // if($min_price)
        // {
        //     $products = $products->whereHas('Product',function($q) use ($min_price){
        //         return $q->where('price','<=',$min_price);
        //     });
        // }

        if($banner_max_price)
        {
            $our_country = Country::findOrFail(3);
            $price =  ( $our_country->refactor + $our_country->extra ) * $banner_max_price;
            $reyal = round($price);
            $products = $products->whereHas('Product',function($q) use ($reyal){
                return $q->where('price','<=',$reyal);
            });
        }


        if($banner_marks_ids && !empty($banner_marks_ids))
        {
            $products = $products->whereHas('Product',function($q) use ($banner_marks_ids){
                return $q->whereIn('mark_id',$banner_marks_ids);
            });
        }

        if($banner_partners_id && !empty($banner_partners_id))
        {
            $products = $products->whereHas('Product',function($q) use ($banner_partners_id){
                return $q->whereIn('partner_id',$banner_partners_id);
            });
        }

        if($banner_options_id && !empty($banner_options_id))
        {
             $products = $products->whereHas('OptionGroups.Options',function($q) use ($banner_options_id){
                return $q->whereIn('option_id',$banner_options_id);
            });
        }


        # --
        if($is_best && $is_best == 1)
        {
            $products = $products->whereHas('Product',function($q) use ($is_best){
                return $q->where('is_best',$is_best);
            });
        }

        if($text && !is_null($text))
        {
            $products = $products->where('color_name_ar','LIKE', '%' . $text . '%')
            ->orWhere('color_name_en','LIKE', '%' . $text . '%');
        }

        if($ids && !is_null($ids))
        {
            $products = $products->whereIn('section_id',$ids);
        }

        if($colors_id && !empty($colors_id))
        {
            $products = $products->whereIn('color_id',$colors_id);
        }

        // if($min_price)
        // {
        //     $products = $products->whereHas('Product',function($q) use ($min_price){
        //         return $q->where('price','<=',$min_price);
        //     });
        // }

        if($max_price)
        {
            $our_country = Country::findOrFail(3);
            $price =  ( $our_country->refactor + $our_country->extra ) * $max_price;
            $reyal = round($price);
            $products = $products->whereHas('Product',function($q) use ($reyal){
                return $q->where('price','<=',$reyal);
            });
        }


        if($marks_ids && !empty($marks_ids))
        {
            $products = $products->whereHas('Product',function($q) use ($marks_ids){
                return $q->whereIn('mark_id',$marks_ids);
            });
        }

        if($partners_id && !empty($partners_id))
        {
            $products = $products->whereHas('Product',function($q) use ($partners_id){
                return $q->whereIn('partner_id',$partners_id);
            });
        }

        if($options_id && !empty($options_id))
        {
             $products = $products->whereHas('OptionGroups.Options',function($q) use ($options_id){
                return $q->whereIn('option_id',$options_id);
            });
        }


    }

    if($sort)
    {
        if($sort != 0)
        {
            # rate
            if($sort == 1)
            {
                $products = $products->orderBy('rate','ASC');
            }

            # newest
            if($sort == 2)
            {
                $products = $products->latest();
            }

            # height price to low price
            if($sort == 4)
            {
                $products = $products->orderBy('price','DESC');
            }

            # low price to height price
            if($sort == 3)
            {
                $products = $products->orderBy('price','ASC');
            }
        }
    }
    // return $products->paginate(10);
    // return $products->paginate($count);
    // return ProductsList($products->get(),2,$country); 

    # banner sort = 1 -> random
    # banner sort = 2 -> latest
    # banner sort = 3 -> oldest

    if($banner_sort == 1)
    {
        return ProductsList($products->inRandomOrder('id')->paginate($count),$type,$country); 
    }elseif($banner_sort == 2)
    {
        return ProductsList($products->latest()->paginate($count),$type,$country); 

    }elseif($banner_sort == 3)
    {
        return ProductsList($products->paginate($count),$type,$country); 
    }else{
        return ProductsList($products->inRandomOrder('id')->paginate($count),$type,$country); 
    }
    
}

# product list with pagination
function ProductsList($products,$type,$country_id)
{
    $country = Country::findOrFail($country_id);

    # type 1 = limit products with pagination 
    # type 2 = limit products without pagination

    $list = [];
    if($type == 1)
    {
        if(count($products) < 1)
        {
            $list['data'] = [];
            $list['current_page']                     = $products->toArray()['current_page'];
            $list['last_page']                        = $products->toArray()['last_page'];
        }

        foreach ($products as $key => $product)
        {
            $list['data'][$key]['product_id']         = $product->Product->id;
            $list['data'][$key]['product_color_id']   = $product->id;
            $list['data'][$key]['name']               = $product->name;
            $list['data'][$key]['is_best']            = $product->Product->is_best;
            $list['data'][$key]['image']              = $product->image_url;
            $list['data'][$key]['quality']            = $product->Product->quality;
            $list['data'][$key]['stock']              = $product->Product->stock;
            $list['data'][$key]['discount']           = $product->Product->discount;
            $list['data'][$key]['currency']           = $country->currency;
            // $list['data'][$key]['test_mark_id']       = $product->Product->mark_id;
            // $list['data'][$key]['test_color_id']      = $product->Color->id;
            // $list['data'][$key]['test_section_id']    = $product->section_id;

            $new_price = ConvertToBaseCurrency($country_id,$product->Product->price);

            if(!$product->Product->discount || $product->Product->discount == 0)
            {
                $list['data'][$key]['old_price']      = 0;
            }else{
                $list['data'][$key]['old_price']      = $new_price;
            }
            $list['data'][$key]['new_price']          = ListCalcDiscount($new_price,$product->Product->discount);
            $list['current_page']                     = $products->toArray()['current_page'];
            $list['last_page']                        = $products->toArray()['last_page'];
        }
    }elseif($type == 2)
    {
        foreach ($products as $key => $product)
        {
            $list[$key]['product_id']         = $product->Product->id;
            $list[$key]['product_color_id']   = $product->id;
            $list[$key]['name']               = $product->name;
            $list[$key]['is_best']            = $product->Product->is_best;
            $list[$key]['image']              = $product->image_url;
            $list[$key]['quality']            = $product->Product->quality;
            $list[$key]['stock']              = $product->Product->stock;
            $list[$key]['discount']           = $product->Product->discount;
            $list[$key]['currency']           = $country->currency;

            $new_price = ConvertToBaseCurrency($country_id,$product->Product->price);

            if(!$product->Product->discount || $product->Product->discount == 0)
            {
                $list[$key]['old_price']      = 0;
            }else{
                $list[$key]['old_price']      = $new_price;
            }
            $list[$key]['new_price']          = ListCalcDiscount($new_price,$product->Product->discount);
        }
    }


    return $list;
}

# all product details
function AllProductDetails($product_id,$color_reference_id,$country_id)
{
    $country = Country::findOrFail($country_id);

    $p = Product_Color::with
    ([
        'Product.Colors.Color',
        'Product.Partner',
        'Images',
        'OptionGroups.Options.Option',
        'OptionGroups.Group',
        'Section',
        'Rates.User'
    ])->where([['id',$color_reference_id],['product_id',$product_id]])->first();

    $product['product_id']         = $p->Product->id;
    $product['product_color_id']   = $p->id;
    $product['name']               = $p->name;
    $product['is_best']            = $p->Product->is_best;
    $product['image']              = $p->image_url;
    $product['quality']            = $p->Product->quality;
    $product['stock']              = $p->Product->stock;
    $product['discount']           = $p->Product->discount;
    $product['currency']           = $country->currency;

    # price
    $new_price = ConvertToBaseCurrency($country_id,$p->Product->price);

    if(!$p->Product->discount || $p->Product->discount == 0)
    {
        $product['old_price']      = 0;
    }else{
        $product['old_price']      = $new_price;
    }
    $product['new_price']          = ListCalcDiscount($new_price,$p->Product->discount);

    # details html
    $product['details']['details_html'] = $p->Product->details;

    # galary
    $product['details']['galary'] = $p->Images;

    # partner
    $product['details']['partner']['id']   = $p->Product->Partner->id;
    $product['details']['partner']['name'] = $p->Product->Partner->name;

    # colors
    foreach ($p->Product->Colors as $key => $color)
    {
        $product['details']['colors'][$key]['id']         = $color->id;
        $product['details']['colors'][$key]['sku']        = $color->color_sku;
        $product['details']['colors'][$key]['extra_text'] = $color->extra_text;
        if(!is_null($color->image_pattern_url))
        {
            $product['details']['colors'][$key]['image']  = $color->image_pattern_url;
        }else{
            $product['details']['colors'][$key]['image']  = $color->Color->color_image;
        }
    }

    # selectable options
    $product['details']['selectable_group'] = null;
    foreach ($p->OptionGroups as $key => $group)
    {
        
        if($group->selectable == 1)
        {
            $product['details']['selectable_group']['id']    = $group->id;
            $product['details']['selectable_group']['name']  = $group->Group->name;
            foreach ($group->Options as $key => $option)
            {
                $product['details']['selectable_group']['options'][$key]['id']       = $option->id;
                $product['details']['selectable_group']['options'][$key]['name']     = $option->Option->name;
                $product['details']['selectable_group']['options'][$key]['stock']    = $option->stock;
                $product['details']['selectable_group']['options'][$key]['discount'] = $p->Product->discount;
                $product['details']['selectable_group']['options'][$key]['currency'] = $country->currency;

                if($option->price == 0)
                {
                    $new_price = ConvertToBaseCurrency($country_id,$p->Product->price);

                    if(!$p->Product->discount || $p->Product->discount == 0)
                    {
                        $product['details']['selectable_group']['options'][$key]['old_price'] = 0;
                    }else{
                       $product['details']['selectable_group']['options'][$key]['old_price']  = $new_price;
                    }
                    $product['details']['selectable_group']['options'][$key]['new_price']     = ListCalcDiscount($new_price,$p->Product->discount);
                }else{

                    $new_price = ConvertToBaseCurrency($country_id,$option->price);

                    if(!$p->Product->discount || $p->Product->discount == 0)
                    {
                        $product['details']['selectable_group']['options'][$key]['old_price'] = 0;
                    }else{
                       $product['details']['selectable_group']['options'][$key]['old_price']  = $new_price;
                    }
                    $product['details']['selectable_group']['options'][$key]['new_price']     = ListCalcDiscount($new_price,$p->Product->discount);
                }
            }
        }
    }

    # unselectable options
    $unselect = [];
    foreach ($p->OptionGroups as $key => $group)
    {
        if($group->selectable == 0)
        {
            // return $group->Options[0];
            $push['key']   = $group->Group->name;
            if(isset($group->Options[0]))
            {
                $push['value'] = $group->Options[0]->Option->name;
            }else{
                $push['value'] = '';
            }
            $unselect[] = $push;
        }
    }
    $product['details']['unselectable_group'] = $unselect;

    # pages web view
    $setting = SiteSetting::select('return_policy_url','shipping_url','free_shipping_price')->first();
    if(!is_null($p->Section->sizes_table))
    {
        $product['details']['pages']['sizes_table']     = $p->Section->sizes_table;
    }else{
        $product['details']['pages']['sizes_table']     = null;
    }
    $product['details']['pages']['return_policy_url']   = $setting->return_policy_url;
    $product['details']['pages']['shipping_url']        = $setting->shipping_url;
    $product['details']['pages']['free_shipping_price'] = ConvertToBaseCurrency(request()->header('country'),$setting->free_shipping_price);

    # rates
    $rates = [];
    foreach ($p->Rates as $key => $rate)
    {
        $pushData['rate']       = $rate->rate;
        $pushData['comment']    = $rate->comment;
        $pushData['created_at'] = $rate->created_at->toDateTimeString();
        $pushData['since']      = Date::parse($rate->created_at)->diffForHumans();
        $pushData['user']       = $rate->User->name;
        $rates[] = $pushData;
    }

    $product['details']['rates']['avg']   = $p->rate;
    $product['details']['rates']['count'] = $p->getTotalRatesAttribute();
    $product['details']['rates']['users'] = $rates;
    
    # related products
    $type = 2;
    $related_products = Product_Color::with([
    'Product',
    ])->whereHas('Product',function($q) use ($p)
    {
        $q->where('id','!=',$p->Product->id);
    })
    ->where('section_id',$p->Section->id)->take(100)->get();
   
    $product['details']['related_products'] = ProductsList($related_products ,$type ,$country_id);

    return $product;
}


# calculate list discount
function ListCalcDiscount($price,$discount)
{
    if(!$discount || $discount == 0)
    {
        return $price;
    }else{
        return round($price - ($price * $discount) / 100 ) ;
    }    
}

# calculate discount
function CalcDiscount($price,$product_id)
{
    $product = Product::where('id',$product_id)->first();

    return round($price - ($price * $product->discount) / 100 ) ;
}

function DiscountAmount($price,$discount)
{
    return round(($price * $discount) / 100 ) ;
}

# check coupon 
function CheckCoupon($country_id,$coupon_code ,$amount,$order_amount_status)
{
    $coupon = Coupon::where('code',$coupon_code)->first();

    # $code_validity
    # $code_date
    # $total_uses
    # $user_uses
    # $amount_status

    #check code
    if(!$coupon)
    {
        $code_validity = false;
        $msg = trans('messages.wrong_coupon_code');
        $res['message'] = $msg;
        $res['status']   = 0;
        return $res;
    }else{
        $code_validity = true;
    }

    # check date
    if(strtotime(Carbon::now()->format('Y-m-d')) > strtotime($coupon->end_date)) 
    {
        $code_date = false;
        $msg = trans('messages.date_coupon');
        $res['message'] = $msg;
        $res['status']   = 0;
        return $res;
    }else{
        $code_date = true;
    }

    # check total uses
    if($coupon->total_uses_number == 0) 
    {
        $total_uses = false;
        $msg = trans('messages.total_coupon_uses');
        $res['message'] = $msg;
        $res['status']   = 0;
        return $res;
    }else{
        $total_uses = true;
    }

    # check user uses
    $user_uses = Order::where([['coupon_id',$coupon->id],['user_id',Auth::user()->id]])->count();
    if($user_uses >= $coupon->user_uses_number) 
    {
        $user_uses = false;
        $msg = trans('messages.user_coupon_uses');
        $res['message'] = $msg;
        $res['status']   = 0;
        return $res;
    }else{
        $user_uses = true;
    }

    # check amount

    # 0 = default currency
    # 1 = country currency
    if($order_amount_status == 1)
    {
        $coupon_amount = ConvertToBaseCurrency($country_id,$coupon->total_amount);
    }elseif ($order_amount_status == 0)
    {
        $coupon_amount = $coupon->total_amount;
    }
    
    if($amount < $coupon_amount) 
    {
        $amount_status = false;
        $msg = trans('messages.coupon_amount');
        $res['message'] = $msg;
        $res['status']   = 0;
        return $res;
    }else{
        $amount_status = true;
    }

    if($code_validity && $code_date && $total_uses && $user_uses && $amount_status)
    {
        if($order_amount_status == 1)
        {
            $msg = 'done';
            $data['id']           = $coupon->id;
            $data['discount']     = $coupon->discount;
            $data['code']         = $coupon->code;
            $data['total_amount'] = ConvertToBaseCurrency($country_id,$coupon->total_amount);
            $data['type']         = $coupon->type;
            $data['currency']     = Country::findOrFail($country_id)->currency;

            if($coupon->type == 'currency')
            {
                $data['discount_amount'] = ConvertToBaseCurrency($country_id,$coupon->discount);
            }elseif($coupon->type == 'percent')
            {
                $calc_discount           = DiscountAmount($amount,$coupon->discount);
                $data['discount_amount'] = $calc_discount;
            }


        }elseif($order_amount_status == 0)
        {
            $msg = 'done';
            $data['id']           = $coupon->id;
            $data['discount']     = $coupon->discount;
            $data['code']         = $coupon->code;
            $data['total_amount'] = $coupon->total_amount;
            $data['type']         = $coupon->type;
            $data['currency']     = Country::where('id',3)->first()->currency;
            if($coupon->type == 'currency')
            {
                $data['discount_amount'] = $coupon->discount;
            }elseif($coupon->type == 'percent')
            {
                $calc_discount           = DiscountAmount($amount,$coupon->discount);
                $data['discount_amount'] = $calc_discount;
            }
        }

        $res['message'] = $msg;
        $res['status']  = 1;
        $res['data']    = $data; 
        return $res;
    }
}

# order status text
function OrderStatus($s)
{
    if($s == 1)
    {
        return ['text'=>'طلب جديد','class'=>'info'];
    }elseif($s == 2)
    {
        return ['text'=>'جاري المعالجه','class'=>'primary'];
    }elseif($s == 3)
    {
        return ['text'=>'تم التسليم','class'=>'success'];
    }elseif($s == 4)
    {
        return ['text'=>'إالغاء','class'=>'danger'];
    }
}

# send notification for topic
function NotiForTopic($title,$body,$data,$image)
{
    $database = Configuration::first();

    $fcm_server_key = $database->fcm_server_key;


    // prep the bundle
    $msg = $data;
    $fields = array
    (
        'data'          => $msg,
        'priority'      => 'high',
        "to"            => '/topics/kenanny',
        'notification'  => array(
            'title'     => $title,
            'body'      => $body,
            'image'     => $image  
        )
    );
     
    $headers = array
    (
        'Authorization: key=' . $fcm_server_key,
        'Content-Type: application/json'
    );
     
    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' ); //https://fcm.googleapis.com/fcm/send //old //https://android.googleapis.com/gcm/send
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
    $result = curl_exec($ch );
    curl_close( $ch );
    #echo $result;
}

# send notification for users by tokens
function NotiByTokenUser($title,$body,$token,$data,$image)
{
    $database = SmsEmailNotification::first();

    $fcm_server_key = $database->fcm_server_key;

    $registrationIds = $token;

    // prep the bundle
    $msg = $data;

    $fields = array
    (
        'registration_ids'   => $registrationIds,
        'data'               => $msg,
        'priority'           => 'high',
        'notification'       => array(
            'title'          => $title,
            'body'           => $body,
            'image'          => $image
        )
    );
     
    $headers = array
    (
        'Authorization: key=' .$fcm_server_key,
        'Content-Type: application/json'
    );
     
    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' ); //https://fcm.googleapis.com/fcm/send //old //https://android.googleapis.com/gcm/send
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
    $result = curl_exec($ch );
    curl_close( $ch );
    #echo $result;
}


# parent id
function ParentID($section_id)
{
    $sections = Section::with('Parent.Parent.Parent.Parent.Parent')->findOrFail($section_id);
    if(!is_null($sections->Parent))
    {
        # 1
        if(!is_null($sections->Parent->Parent))
        {
            # 2
            if(!is_null($sections->Parent->Parent->Parent))
            {
                # 3
                if(!is_null($sections->Parent->Parent->Parent->Parent))
                {
                    # 4
                    if(!is_null($sections->Parent->Parent->Parent->Parent->Parent))
                    {
                        # 5
                        if(!is_null($sections->Parent->Parent->Parent->Parent->Parent->Parent))
                        {

                        }else{
                            return $sections->Parent->Parent->Parent->Parent->Parent->id;
                        }
                    }else{
                        return $sections->Parent->Parent->Parent->Parent->id;
                    }
                }else{
                    return $sections->Parent->Parent->Parent->id;
                }
            }else{
                return $sections->Parent->Parent->id;
            }
        }else{
            return $sections->Parent->id;
        }
    }else{
        return $section_id;
    }
}

# current country
function CurrentCountry(){
    return Country::where('id',request()->header('country'))->first();
}

function send_mobile_sms($numbers, $msg)
{
    $url = 'http://api.yamamah.com/SendSMS';
    $fields = array(
        "Username" => "966543956641",
        "Password" => "Aa100400700300",
        "Message" => $msg,
        "RecepientNumber" => $numbers,
        "ReplacementList" => "",
        "SendDateTime" => "0",
        "EnableDR" => False,
        "Tagname" => "Kayan",
        "VariableList" => "0"
    );

    $fields_string = json_encode($fields);

    $ch = curl_init($url);
    curl_setopt_array($ch, array(
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        CURLOPT_POSTFIELDS => $fields_string
    ));
    $result = curl_exec($ch);
    curl_close($ch);
}

function GetUserIdByToken($token)
{
    if(!$token)
    {
        return null;
    }
    // break up the string to get just the token
    $auth_header = explode(' ', $token);
    $token = $auth_header[1];

    // break up the token into its three parts
    $token_parts = explode('.', $token);
    $token_header = $token_parts[0];

    // base64 decode to get a json string
    $token_header_json = base64_decode($token_header);
    // you'll get this with the provided token:
    // {"typ":"JWT","alg":"RS256","jti":"9fdb0dc4382f2833ce2d3993c670fafb5a7e7b88ada85f490abb90ac211802720a0fc7392c3f2e7c"}

    // then convert the json to an array
    $token_header_array = json_decode($token_header_json, true);
    $user_token = $token_header_array['jti'];
    $user_id = DB::table('oauth_access_tokens')->where('id', $user_token)->value('user_id');
    if($user_id)
    {
        return $user_id;
    }else{
        return null;
    }
}


function AramexLocalShipment(
    $PersonName,
    $PhoneNumber1,
    // $CellPhone,
    $EmailAddress,
    $ForeignHAWB,
    $city_name,
    $post_code,
    $address,
    $count,
    $names,
    $PaymentType # c - p - 3
)
{
    // $PersonName   = $PersonName = '';
    // $PhoneNumber1 = $PhoneNumber1 = '';
    // // $CellPhone    = $CellPhone;
    // $EmailAddress = $EmailAddress = '';
    // $ForeignHAWB  = $ForeignHAWB = '';
    // $city_name    = $city_name = '';
    // $post_code    = $post_code = '';

    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    
    $soapClient = new SoapClient(URL::to('dashboard/shipping.wsdl'));
    // echo '<pre>';
    // print_r($soapClient->__getFunctions());

    $params = array(
            'Shipments' => array(
                'Shipment' => array(
                        'Shipper'   => array(
                                        'Reference1'    => 'Ref 111111',
                                        'Reference2'    => 'Ref 222222',
                                        'AccountNumber' => '164246',
                                        'PartyAddress'  => array(
                                            'Line1'                 => 'Mecca St',
                                            'Line2'                 => '',
                                            'Line3'                 => '',
                                            'City'                  => 'Jeddah',
                                            'StateOrProvinceCode'   => '',
                                            'PostCode'              => '22263',
                                            'CountryCode'           => 'SA'
                                        ),
                                        'Contact'       => array(
                                            'Department'            => '',
                                            'PersonName'            => 'kayan alkarat',
                                            'Title'                 => 'kayan alkarat order',
                                            'CompanyName'           => 'kayan alkarat',
                                            'PhoneNumber1'          => '595253333',
                                            'PhoneNumber1Ext'       => '125',
                                            'PhoneNumber2'          => '',
                                            'PhoneNumber2Ext'       => '',
                                            'FaxNumber'             => '',
                                            'CellPhone'             => '07777777',
                                            'EmailAddress'          => 'kayanelkarat@gmail.com',
                                            'Type'                  => ''
                                        ),
                        ),
                                                
                        'Consignee' => array(
                                        'Reference1'    => 'Ref 333333',
                                        'Reference2'    => 'Ref 444444',
                                        'AccountNumber' => '164246',
                                        'PartyAddress'  => array(
                                            'Line1'                 => $address,
                                            'Line2'                 => '',
                                            'Line3'                 => '',
                                            'City'                  => $city_name,
                                            'StateOrProvinceCode'   => '',
                                            'PostCode'              => $post_code,
                                            'CountryCode'           => 'SA'
                                        ),
                                        
                                        'Contact'       => array(
                                            'Department'            => $city_name,
                                            'PersonName'            => $PersonName,
                                            'Title'                 => 'كيان القارات '.$ForeignHAWB,
                                            'CompanyName'           => $PersonName,
                                            'PhoneNumber1'          => $PhoneNumber1,
                                            'PhoneNumber1Ext'       => '',
                                            'PhoneNumber2'          => '',
                                            'PhoneNumber2Ext'       => '',
                                            'FaxNumber'             => '',
                                            'CellPhone'             => $PhoneNumber1,
                                            'EmailAddress'          => $EmailAddress,
                                            'Type'                  => ''
                                        ),
                        ),
                        
                        'ThirdParty' => array(
                                        'Reference1'    => '',
                                        'Reference2'    => '',
                                        'AccountNumber' => '164246',
                                        'PartyAddress'  => array(
                                            'Line1'                 => '',
                                            'Line2'                 => '',
                                            'Line3'                 => '',
                                            'City'                  => $city_name,
                                            'StateOrProvinceCode'   => '',
                                            'PostCode'              => $post_code,
                                            'CountryCode'           => 'SA'
                                        ),
                                        'Contact'       => array(
                                            'Department'            => '',
                                            'PersonName'            => $PersonName,
                                            'Title'                 => '',
                                            'CompanyName'           => '',
                                            'PhoneNumber1'          => $PhoneNumber1,
                                            'PhoneNumber1Ext'       => '',
                                            'PhoneNumber2'          => '',
                                            'PhoneNumber2Ext'       => '',
                                            'FaxNumber'             => '',
                                            'CellPhone'             => $PhoneNumber1,
                                            'EmailAddress'          => $EmailAddress,
                                            'Type'                  => ''                           
                                        ),
                        ),
                        
                        'Reference1'                => 'Shpt '.$ForeignHAWB,
                        'Reference2'                => '',
                        'Reference3'                => '',
                        'ForeignHAWB'               => $ForeignHAWB,
                        'TransportType'             => 0,
                        'ShippingDateTime'          => time(),
                        'DueDate'                   => time(),
                        'PickupLocation'            => 'Reception',
                        'PickupGUID'                => '',
                        'Comments'                  => 'Shpt 0001',
                        'AccountingInstrcutions'    => '',
                        'OperationsInstructions'    => '',
                        
                        'Details' => array(
                                        'Dimensions' => array(
                                            'Length'                => 10,
                                            'Width'                 => 10,
                                            'Height'                => 10,
                                            'Unit'                  => 'cm',
                                            
                                        ),
                                        
                                        'ActualWeight' => array(
                                            'Value'                 => 0.5,
                                            'Unit'                  => 'Kg'
                                        ),
                                        
                                        'ProductGroup'          => 'DOM',
                                        'ProductType'           => 'OND',
                                        'PaymentType'           => $PaymentType,
                                        'PaymentOptions'        => '',
                                        'Services'              => '',
                                        'NumberOfPieces'        => $count,
                                        'DescriptionOfGoods'    => $names,
                                        'GoodsOriginCountry'    => 'SA',
                                        
                                        'CashOnDeliveryAmount'  => array(
                                            'Value'                 => 0,
                                            'CurrencyCode'          => ''
                                        ),
                                        
                                        'InsuranceAmount'       => array(
                                            'Value'                 => 0,
                                            'CurrencyCode'          => ''
                                        ),
                                        
                                        'CollectAmount'         => array(
                                            'Value'                 => 0,
                                            'CurrencyCode'          => ''
                                        ),
                                        
                                        'CashAdditionalAmount'  => array(
                                            'Value'                 => 0,
                                            'CurrencyCode'          => ''                           
                                        ),
                                        
                                        'CashAdditionalAmountDescription' => '',
                                        
                                        'CustomsValueAmount' => array(
                                            'Value'                 => 0,
                                            'CurrencyCode'          => ''                               
                                        ),
                                        
                                        'Items'                 => array(
                                            'PackageType'   => 'Box',
                                            'Quantity'      => $count,
                                            'Weight'        => array(
                                                    'Value'     => 0.5,
                                                    'Unit'      => 'Kg',        
                                            ),
                                            'Comments'      => $names,
                                            'Reference'     => $names
                                        )
                        ),
                ),
        ),
        
            'ClientInfo'            => array(
                 'AccountCountryCode'    => 'SA',
                 'AccountEntity'         => 'JED',
                 'AccountNumber'         => '164246',
                 'AccountPin'            => '443543',
                 'UserName'              => 'Kayan_na@hotmail.com',
                 'Password'              => 'Kayan_2018',
                 'Version'               => 'v1.0'
            ),

            'Transaction'           => array(
                                        'Reference1'            => '001',
                                        'Reference2'            => '', 
                                        'Reference3'            => '', 
                                        'Reference4'            => '', 
                                        'Reference5'            => '',                                  
                                    ),
            'LabelInfo'             => array(
                                        'ReportID'              => 9201,
                                        'ReportType'            => 'URL',
            ),
    );
    
    // $params['Shipments']['Shipment']['Details']['Items'][] = array(
    //  'PackageType'   => 'Box',
    //  'Quantity'      => 1,
    //  'Weight'        => array(
    //          'Value'     => 0.5,
    //          'Unit'      => 'Kg',        
    //  ),
    //  'Comments'      => 'Docs',
    //  'Reference'     => ''
    // );
    
    // print_r($params);
    
    try {
        $auth_call = $soapClient->CreateShipments($params);
        // echo '<pre>';
        // return response()->json( $auth_call);
        return  $auth_call;
        // die();
    } catch (SoapFault $fault) {
        // die('Error : ' . $fault->faultstring);
    }

}


# send mail to manager when add new order
function SendMailToManager($order_id)
{
    $email     = 'mohamed.hamada0103@gmail.com';

    $products  = Order_Details::with(['Product.Partner','Color','Option','Order'])
    ->where([
        ['order_id'  ,$order_id]
    ])->get();

    $send = Mail::to($email)->send(new ManagerMail($products,$order_id));
}





