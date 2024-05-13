<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function productList(Request $request)
    {
        $page = $request->input('page');
        $limit =$request->input('limit');
        $offset = ($page -1)*$limit;

        $products = Product::select('products.*','product_types.prd_type_name')
            ->leftJoin('product_types','product_types.prd_type_id','=','products.prd_type')
            ->orderBy('prd_id','asc')
            ->offset($offset)->limit($limit)->get();
        $rowCount = Product::select('products.*','product_types.prd_type_name')
            ->leftJoin('product_types','product_types.prd_type_id','=','products.prd_type')
            ->count();
        //$products =$query->limit($limit)->offset($offset)->get();
       // $rowCount = $query->count();
        $last_page=0;
        if($rowCount >0 && $limit>0){
            $remains =  ($rowCount%$limit >0)? 1:0;
            $last_page = $remains + ($rowCount - $rowCount%$limit)/$limit;
        }

        //print_r($rowCount); die();
//        $products = Product::select('products.*','product_types.prd_type_name')
//            ->leftJoin('product_types','product_types.prd_type_id','=','products.prd_type')->
//            paginate(2);
//        $products = DB::table('products')
//            ->join('product_types', 'product_types.prd_type_id', '=', 'products.prd_type')
//            ->orderBy('articles.created_at', 'desc')
//            ->select('product_types.prd_type_name')
//            ->paginate(15);
        //print_r($products);die();
        //return view('pduct.products',);compact('products');
        $rsl = [];
        $rsl['data']=$products;
        $rsl['last_page']=$last_page;
        $rsl['total']=$rowCount;
        return response()->json($rsl);
    }

    /**************************************/
    public function edit(Request $request)
    {
        $id = $request->input('id');
        //print_r($id); die();
        $product_detail = Product::select('products.*','product_types.prd_type_name')
            ->leftJoin('product_types','product_types.prd_type_id','=','products.prd_type')->find($id);
        //print_r($product_detail); die();
        //return redirect('product')->with(['product'=>$product_detail]); //xem view redirect with data
        //return view('products.product')->with(['product'=>$product_detail]);
        return response()->json($product_detail);

    }

    /*****************************************/
    public function saveProduct(Request $request)
    {
        $validated = $request->validate([
            'prd_type' => 'required',
            'prd_name' => 'required',
            'prd_quantity' => 'required',
            'prd_price' => 'required',
        ]);

        $input = $request->all();
        unset($input['_token']);

        /*if ($request->file('prd_img') == null) {
            $path = "";
        }else{
            $image = $request->file('prd_img');
            //$ext = $image->extension();
            $image_name = $image->getClientOriginalName();
            //$path = $request->file('prd_img')->store('products');
            $path = $request->file('prd_img')->storeAs('products', $image_name, 'local');
        }*/
        //die($path);
        $data= array();
        $multi_sexes ='';
        $multi_sizes='';
        foreach($input as $key=>$value){
            if($value !=''){
                if($key =='prd_male'){
                    $multi_sexes =($multi_sexes=='')?$value:$multi_sexes.','.$value;
                }elseif($key =='prd_female'){
                    $multi_sexes =($multi_sexes=='')?$value:$multi_sexes.','.$value;
                }elseif($key =='prd_unknown'){
                    $multi_sexes =($multi_sexes=='')?$value:$multi_sexes.','.$value;
                }elseif($key =='prd_small'){
                    $multi_sizes =($multi_sizes=='')?$value:$multi_sizes.','.$value;
                }elseif($key =='prd_medium'){
                    $multi_sizes =($multi_sizes=='')?$value:$multi_sizes.','.$value;
                }elseif($key =='prd_x'){
                    $multi_sizes =($multi_sizes=='')?$value:$multi_sizes.','.$value;
                }elseif($key =='prd_xl'){
                    $multi_sizes =($multi_sizes=='')?$value:$multi_sizes.','.$value;
                }elseif($key =='prd_price'){
                    $data[$key] = preg_replace('/\$|\s+|\,+|\.00$/', '', $value);
                }elseif($key =='prd_regular_price'){
                    $data[$key] = preg_replace('/\$|\s+|\,+|\.00$/', '', $value);
                }else{
                    $data[$key] = $value;
                }
            }
        }
        if($multi_sexes !=''){
            $data['multi_sexes'] = $multi_sexes;
        }
        if($multi_sizes !=''){
            $data['multi_sizes'] = $multi_sizes;
        }

        // print_r($data);
        // die();

        Product::create($data);
        return response()->json(['message' => 'Product added successfully'], 201);
    }

    /*****************************************/
    public function updateproduct(Request $request,$id)
    {
        $validated = $request->validate([
            'prd_type' => 'required',
            'prd_name' => 'required',
            'prd_quantity' => 'required',
            'prd_price' => 'required',
        ]);

        $product = Product::findOrFail($id);

        $input = $request->all();
        unset($input['_token']);
       // $product->update($input);
        $data= array();
        $multi_sexes ='';
        $multi_sizes='';
        foreach($input as $key=>$value){
            if($value !=''){
                if($key =='prd_male'){
                    $multi_sexes =($multi_sexes=='')?$value:$multi_sexes.','.$value;
                }elseif($key =='prd_female'){
                    $multi_sexes =($multi_sexes=='')?$value:$multi_sexes.','.$value;
                }elseif($key =='prd_unknown'){
                    $multi_sexes =($multi_sexes=='')?$value:$multi_sexes.','.$value;
                }elseif($key =='prd_small'){
                    $multi_sizes =($multi_sizes=='')?$value:$multi_sizes.','.$value;
                }elseif($key =='prd_medium'){
                    $multi_sizes =($multi_sizes=='')?$value:$multi_sizes.','.$value;
                }elseif($key =='prd_x'){
                    $multi_sizes =($multi_sizes=='')?$value:$multi_sizes.','.$value;
                }elseif($key =='prd_xl'){
                    $multi_sizes =($multi_sizes=='')?$value:$multi_sizes.','.$value;
                }elseif($key =='prd_price'){
                    $data[$key] = preg_replace('/\$|\s+|\,+|\.00$/', '', $value);
                }elseif($key =='prd_regular_price'){
                    $data[$key] = preg_replace('/\$|\s+|\,+|\.00$/', '', $value);
                }else{
                    $data[$key] = $value;
                }
            }
        }
        if($multi_sexes !=''){
            $data['multi_sexes'] = $multi_sexes;
        }
        if($multi_sizes !=''){
            $data['multi_sizes'] = $multi_sizes;
        }

//        print_r($prd_id);
//        die();
        $prd_id = request()->route('prd_id');
        Product::where('prd_id', $id)->update($data);
        return response()->json(['message' => 'Product update successfully'], 200);
    }

    /*****************************************/
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Delete the cover image if it exists
        if ($product->cover_image) {
            Storage::delete('public/images/' . $product->prd_img);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
