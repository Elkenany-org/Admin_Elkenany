<?php
use Illuminate\Support\Facades\Route;

use App\User;
use App\Role;
use App\Contact;
use App\SmsEmailNotification;
use App\Permission;



# navbar menu
function NavbarMenu()
{
    $routes = Route::getRoutes();
    foreach ($routes as $value)
    {
        if($value->getName() === Route::currentRouteName())
        {
            if($value->getName() !== null)
            {
                if(isset($value->getAction()['title']) && isset($value->getAction()['icon']))
                {
                    if(isset($value->getAction()['child']) && isset($value->getAction()['subTitle']) && isset($value->getAction()['subIcon']))
                    {

                        $permissions = Permission::where('role_id',Auth::user()->role)->pluck('permission')->toArray();


                        if(in_array($value->getName(),$permissions))
                        {
                            echo '<li class="nav-item d-none d-sm-inline-block">';
                                echo '<a href="'.route($value->getName()).'" class="nav-link text-primary">'.  $value->getAction()['icon'] . '  ' .$value->getAction()['subTitle'].'</a>';
                            echo '</li>';

                            foreach ($value->getAction()['child'] as $child)
                            {
                                #foreach for sub links
                                $routes = Route::getRoutes();
                                foreach ($routes as $value)
                                {
                                    if($value->getName() !== null && isset($value->getAction()['icon']))
                                    {
                                        if($value->getName() == $child)
                                        {
                                            if(in_array($value->getName(),$permissions))
                                            {
                                                echo '<li class="nav-item d-none d-sm-inline-block">';
                                                    echo '<a href="'.route($value->getName()).'" class="nav-link text-primary">'. $value->getAction()['icon'].'  '.$value->getAction()['title'].'</a>';
                                                echo '</li>';
                                            }
                                        }
                                    }
                                }
                            }

                        }

                    }
                    
                }
            } 
        }

    }
}


function menu()
{
    $permissions = Permission::where('role_id',Auth::user()->role)->pluck('permission')->toArray();

	$routes = Route::getRoutes();
	foreach ($routes as $value)
	{
		if($value->getName() !== null)
		{
			if(isset($value->getAction()['title']) && isset($value->getAction()['icon']))
			{
				if(isset($value->getAction()['child']) && isset($value->getAction()['subTitle']) && isset($value->getAction()['subIcon']))
				{

                    if(in_array($value->getName(),$permissions))
                    {

                        echo '<li class="nav-item has-treeview">';
                          echo '<a href="#" class="nav-link">';
                           echo $value->getAction()['icon'];
                            echo '<p style="margin-right:6px">';
                              echo $value->getAction()['title'];
                             echo '<i class="right fas fa-angle-left"></i>';
                            echo '</p>';
                          echo '</a>';

                          // child links
                          echo '<ul class="nav nav-treeview">';
                            // subtitle
                            echo '<li class="nav-item">';
                              echo '<a href="'.route($value->getName()).'" class="nav-link">';
                                echo $value->getAction()['subIcon'];
                                echo '<p style="margin-right:6px">'.$value->getAction()['subTitle'].'</p>';
                              echo '</a>';
                            echo '</li>';


                            foreach ($value->getAction()['child'] as $child)
                            {
                                #foreach for sub links
                                $routes = Route::getRoutes();
                                foreach ($routes as $value)
                                {
                                    if($value->getName() !== null && isset($value->getAction()['icon']))
                                    {
                                        if($value->getName() == $child)
                                        {
                                            if(in_array($value->getName(),$permissions))
                                            {
                                                echo '<li class="nav-item">';
                                                  echo '<a href="'.route($value->getName()).'" class="nav-link">';
                                                    echo $value->getAction()['icon'];
                                                    echo '<p style="margin-right:6px">'.$value->getAction()['title'].'</p>';
                                                  echo '</a>';
                                                echo '</li>';
                                            }
                                        }
                                    }
                                }
                            }
                          echo '</ul>';
                        echo '</li>';
                    }

				}
                else if(isset($value->getAction()['child']) && isset($value->getAction()['icon']))
				{
                    if(in_array($value->getName(),$permissions))
                    {
                        echo '<li class="nav-item">';
                          echo '<a href="'.route($value->getName()).'" class="nav-link">';
                            echo $value->getAction()['icon'];
                            echo '<p style="margin-right:6px">'.$value->getAction()['title'].'</p>';
                          echo '</a>';
                        echo '</li>';
                    }
                }else if(!isset($value->getAction()['child']) && isset($value->getAction()['icon']) && !isset($value->getAction()['hasFather']))
                {
                    if(in_array($value->getName(),$permissions))
                    {
                        echo '<li class="nav-item">';
                          echo '<a href="'.route($value->getName()).'" class="nav-link">';
                            echo $value->getAction()['icon'];
                            echo '<p>'.$value->getAction()['title'].'</p>';
                          echo '</a>';
                        echo '</li>';
                    } 
                }
			}
		} 
	}
}

function Permissions()
{
	$routes = Route::getRoutes();
	foreach ($routes as $key_father => $value)
	{
		if($value->getName() !== null)
		{
			if(isset($value->getAction()['title']))
			{
                if(isset($value->getAction()['child']) )
                {
                    echo ' <div class="col-lg-3 col-md-6 col-xs-12">';          
                        echo ' <div class="card card-primary">';    
                            echo ' <div class="card-header" style="height: 38px;padding-right:12px">'; 
                                echo '<input type="checkbox" class="father" data-id="'.$key_father.'" style="float: right;margin-left: 5px;">';  
                                echo '<h3 class="card-title" style="float: right;">'.$value->getAction()['title'].'</h3>'; 
                            echo ' </div>';


                                echo ' <div class="card-body" style="padding: 4px 10px 0px 10px;height: 30px">';
                                    echo ' <div class="form-group clearfix">';
                                        echo '<div class="icheck-danger d-inline">';
                                        echo '<input type="checkbox" class="permission child'.$key_father.'" name="permission[]" id="checkboxPrimary'.$key_father.'" value="'.$value->getName().'">';
                                        echo '<label for="checkboxPrimary'.$key_father.'">';
                                        echo '</label>';
                                      echo '</div>';

                                      echo '<div class="icheck-primary d-inline">';
                                        echo '<label for="checkboxPrimary'.$key_father.'">';
                                          echo $value->getAction()['title'];
                                       echo ' </label>';
                                      echo '</div>';

                                    echo '</div>';
                                echo '</div>';

                                foreach ($value->getAction()['child'] as $child)
                                {
                                    # foreach for child links
                                    $routes = Route::getRoutes();
                                    foreach ($routes as $key => $route)
                                    {
                                        if($route->getName() == $child)
                                        {
                                            echo ' <div class="card-body" style="padding: 4px 10px 0px 10px;height: 30px">';
                                                echo ' <div class="form-group clearfix">';
                                                    echo '<div class="icheck-danger d-inline">';
                                                    echo '<input type="checkbox" class="permission child'.$key_father.'" name="permission[]" id="checkboxPrimary'.$key.'" value="'.$route->getName().'">';
                                                    echo '<label for="checkboxPrimary'.$key.'">';
                                                    echo '</label>';
                                                  echo '</div>';

                                                  echo '<div class="icheck-primary d-inline">';
                                                    echo '<label for="checkboxPrimary'.$key.'">';
                                                      echo $route->getAction()['title'];
                                                   echo ' </label>';
                                                  echo '</div>';

                                                echo '</div>';
                                            echo '</div>';
                                        }
                                    }
                                }
                        echo '</div>';
                    echo '</div>';
                }elseif(!isset($value->getAction()['child']) && isset($value->getAction()['icon']) && !isset($value->getAction()['hasFather'])){
                    echo ' <div class="col-lg-3 col-md-6 col-xs-12">';          
                        echo ' <div class="card card-primary">';    
                            echo ' <div class="card-header" style="height: 38px">';    
                                echo '<h3 class="card-title" style="float: right;">'.$value->getAction()['title'].'</h3>'; 
                            echo ' </div>';

                                echo ' <div class="card-body" style="padding: 4px 10px 0px 10px;height: 30px">';
                                    echo ' <div class="form-group clearfix">';
                                        echo '<div class="icheck-danger d-inline">';
                                        echo '<input type="checkbox" class="permission" name="permission[]" id="checkboxPrimary'.$key_father.'" value="'.$value->getName().'">';
                                        echo '<label for="checkboxPrimary'.$key_father.'">';
                                        echo '</label>';
                                      echo '</div>';

                                      echo '<div class="icheck-primary d-inline">';
                                        echo '<label for="checkboxPrimary'.$key_father.'">';
                                          echo $value->getAction()['title'];
                                       echo ' </label>';
                                      echo '</div>';

                                    echo '</div>';
                                echo '</div>';

                        echo '</div>';
                    echo '</div>';
                }
            }
        }
	}	
}

function EditPermissions($id)
{

    $current_permission = Permission::where('role_id',$id)->pluck('permission')->toArray();

    $routes = Route::getRoutes();
    foreach ($routes as $key_father => $value)
    {
        if($value->getName() !== null)
        {
            if(isset($value->getAction()['title']))
            {
                if(isset($value->getAction()['child']) )
                {
                    echo ' <div class="col-lg-3 col-md-6 col-xs-12">';          
                        echo ' <div class="card card-primary">';    
                            echo ' <div class="card-header" style="height: 38px;padding-right:12px">'; 
                                echo '<input type="checkbox" class="father" data-id="'.$key_father.'" style="float: right;margin-left: 5px;">';  
                                echo '<h3 class="card-title" style="float: right;">'.$value->getAction()['title'].'</h3>'; 
                            echo ' </div>';


                                echo ' <div class="card-body" style="padding: 4px 10px 0px 10px;height: 30px">';
                                    echo ' <div class="form-group clearfix">';
                                        echo '<div class="icheck-danger d-inline">';
                                        # check if route exist
                                        if(in_array($value->getName(),$current_permission))
                                        {
                                            echo '<input type="checkbox" checked="" class="permission child'.$key_father.'" name="permission[]" id="checkboxPrimary'.$key_father.'" value="'.$value->getName().'">';
                                        }else{
                                            echo '<input type="checkbox" class="permission child'.$key_father.'" name="permission[]" id="checkboxPrimary'.$key_father.'" value="'.$value->getName().'">';
                                        }
                                        echo '<label for="checkboxPrimary'.$key_father.'">';
                                        echo '</label>';
                                      echo '</div>';

                                      echo '<div class="icheck-primary d-inline">';
                                        echo '<label for="checkboxPrimary'.$key_father.'">';
                                          echo $value->getAction()['title'];
                                       echo ' </label>';
                                      echo '</div>';

                                    echo '</div>';
                                echo '</div>';

                                foreach ($value->getAction()['child'] as $child)
                                {
                                    # foreach for child links
                                    $routes = Route::getRoutes();
                                    foreach ($routes as $key => $route)
                                    {
                                        if($route->getName() == $child)
                                        {
                                            echo ' <div class="card-body" style="padding: 4px 10px 0px 10px;height: 30px">';
                                                echo ' <div class="form-group clearfix">';
                                                    echo '<div class="icheck-danger d-inline">';
                                                    if(in_array($route->getName(),$current_permission))
                                                    {
                                                        echo '<input type="checkbox" checked="" class="permission child'.$key_father.'" name="permission[]" id="checkboxPrimary'.$key.'" value="'.$route->getName().'">';
                                                    }else{
                                                        echo '<input type="checkbox" class="permission child'.$key_father.'" name="permission[]" id="checkboxPrimary'.$key.'" value="'.$route->getName().'">'; 
                                                    }
                                                    echo '<label for="checkboxPrimary'.$key.'">';
                                                    echo '</label>';
                                                  echo '</div>';

                                                  echo '<div class="icheck-primary d-inline">';
                                                    echo '<label for="checkboxPrimary'.$key.'">';
                                                      echo $route->getAction()['title'];
                                                   echo ' </label>';
                                                  echo '</div>';

                                                echo '</div>';
                                            echo '</div>';
                                        }
                                    }
                                }
                        echo '</div>';
                    echo '</div>';
                }elseif(!isset($value->getAction()['child']) && isset($value->getAction()['icon']) && !isset($value->getAction()['hasFather'])){
                    echo ' <div class="col-lg-3 col-md-6 col-xs-12">';          
                        echo ' <div class="card card-primary">';    
                            echo ' <div class="card-header" style="height: 38px">';    
                                echo '<h3 class="card-title" style="float: right;">'.$value->getAction()['title'].'</h3>'; 
                            echo ' </div>';

                                echo ' <div class="card-body" style="padding: 4px 10px 0px 10px;height: 30px">';
                                    echo ' <div class="form-group clearfix">';
                                        echo '<div class="icheck-danger d-inline">';
                                        if(in_array($value->getName(),$current_permission))
                                        {
                                            echo '<input type="checkbox" checked="" class="permission" name="permission[]" id="checkboxPrimary'.$key_father.'" value="'.$value->getName().'">';
                                        }else{
                                            echo '<input type="checkbox" class="permission" name="permission[]" id="checkboxPrimary'.$key_father.'" value="'.$value->getName().'">'; 
                                        }
                                        echo '<label for="checkboxPrimary'.$key_father.'">';
                                        echo '</label>';
                                      echo '</div>';

                                      echo '<div class="icheck-primary d-inline">';
                                        echo '<label for="checkboxPrimary'.$key_father.'">';
                                          echo $value->getAction()['title'];
                                       echo ' </label>';
                                      echo '</div>';

                                    echo '</div>';
                                echo '</div>';

                        echo '</div>';
                    echo '</div>';
                }
            }
        }
    }   	
}

