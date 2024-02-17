<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;

use App\Models\Product;

use App\Models\Order;

use Illuminate\Support\Facades\Auth;

use PDF;

use Notification;

use App\Notifications\MyForstNotification;

use RealRashid\SweetAlert\Facades\Alert;

class AdminController extends Controller
{
    public function view_category()
    {
        if(Auth::id())
        {
            $data=category::all();
        
        
        return view('admin.category', compact('data'));
        }

        else
        {

            return redirect('login');
        }
    } 

    public function add_category(Request $request)
    {
        if(Auth::id())
        {
            $data=new category;
            $data->category_name=$request->category;
            $data->save();
            return redirect()->back()->with('message', 'Category Added Succesfully');

        }
        else
        {
            return redirect('login');
        }
       

       
    }
    public function delete_category($id)
    {

        if(Auth::id())
        {
            $data=category::find($id);

            $data->delete();

            Alert::success('Product Deleted Scuccessfully', 'We have deleted product from Category');
    
            return redirect()->back()->with('message', 'Category Deleted Succesfully');
        }
        else
        {
            return redirect('login');
        }
        }
       
    public function view_product()
    {
        if(Auth::id())
        {
            $category=category::all();
            return view('admin.product',compact('category'));
        }

        else
        {
            return redirect('login');
        }
    
       
    }
    public function add_product(Request $request)
    {   
        if(Auth::id())
        {
            $product=new product;

            $product->title=$request->title;
            $product->description=$request->description;
            $product->price=$request->price;
            $product->quantity=$request->quantity;
            $product->discount_price=$request->dis_price;
            $product->category=$request->category;
            $image=$request->image;
            $imagename=time().'.'.$image->getClientOriginalExtension();
            $request->image->move('product',$imagename);
            $product->image=$imagename;
    
            $product->save();

            Alert::success('Product Added Scuccessfully', 'We have Added Product ');
    
            return redirect()->back()->with('message', 'Product_Added Successfully');
        }

        else
        {
            return redirect('login');
        }
       
    }
    public function show_product()
    {
        if(Auth::id())
        {
            $product=product::all();
            return view('admin.show_product',compact('product'));

        }
        else
        {
            return redirect('login');
        }
       
    }

    public function delete_product($id)
    {

        if(Auth::id())
        {
            $product=product::find($id);

            $product->delete();

            Alert::success('Product Deleted Scuccessfully', 'We have deleted product');
    
            return redirect()->back();
        }

        else
        {
            return redirect('login');
        }
      

    }
    public function update_product($id)

    {
        if(Auth::id())
        {
            $product=product::find($id);
            $category=category::all();
    
            return view('admin.update_product', compact('product','category'));
        }
        else
        {
            return redirect('login');
        }
      
    }

    public function update_product_confirm(Request $request, $id)
    {
        if(Auth::id())
        {
            $product=product::find($id);

            $product->title=$request->title;
    
            $product->description=$request->description;
    
            $product->price=$request->price;
    
            $product->discount_price=$request->dis_price;
    
            $product->category=$request->category;
    
            $product->quantity=$request->quantity;
    
            $image=$request->image;
    
            if($image)
            {
                $imagename=time().'.'.$image->getClientoriginalExtension();
    
                $request->image->move('product',$imagename);
        
                $product->image=$imagename;
            }
    
            
    
            $product->save();
    
            return redirect()->back()->with('message', 'Product Updated Successfully');
        }

        else
        {
            return redirect('login');
        }

       

    }
    public function order()
    {
        if(Auth::id())
        {
            $order=order::all();
            return view('admin.order',compact('order'));
        }
        else
        {
            return redirect('login');
        }
     
    }

    public function delivered($id)
    {
        if(Auth::id())
        {
            $order=order::find($id);
            $order->delivery_status="delivered";
            $order->payment_status='Paid';
            $order->save();
    
            return redirect()->back();
        }
        else
        {
            return redirect('login');
        }
       


    }

    public function print_pdf($id)
    {
        if(Auth::id())
        {
            $order=order::find($id);
            $pdf=PDF::loadView('admin.pdf',compact('order'));
    
            return $pdf->download('order_details.pdf');
        }

        else
        {
            return redirect('login');
        }
      
    }

    public function send_email($id)
    {
        if(Auth::id())
        {
            $order=order::find($id);
            return view('admin.email_info',compact('order'));
        }

        else
        {
            return redirect('login');
        }
        
    }

    public function send_user_email(Request $request , $id)
    {
        if(Auth::id())
        {
            $order=order::find($id);

            $details = [
                'greetings'=>$request->greeting,
                'firstline'=>$request->firstline,
                'body'=>$request->body,
                'button'=>$request->button,
                'url'=>$request->url,
                'lastline'=>$request->lastline,
    
    
            ];
    
            Notification::send($order, new MyForstNotification($details));
    
            return redirect()->back();
        }

        else
        {
            return redirect('login');
        }
        
    }

    public function searchdata(Request $request)
    {
        if(Auth::id())
        {
            $searchText=$request->search;
            $order=order::where('name', 'LIKE', "%$searchText%")->orWhere('phone', 'LIKE', "%$searchText%")->orWhere('product_title', 'LIKE', "%$searchText%")->get();
            
            return view('admin.order',compact('order'));
        }
        else
        {
            return redirect('login');
        }
       
    }   
}
