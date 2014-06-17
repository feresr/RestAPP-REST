<?php

class OrdersController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$orders = Auth::User()->orders()->get();
		return Response::json($orders);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('orders.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$order = new Order();
		
		$input = Input::get();
		$table = Table::find($input['table_id']);

		if(!$table->taken){

			

			$order->user_id = Auth::User()->id;
			$table->taken = true;
			$order->table_id = $table->id;

			$order->save();
			$table->save();

			
		}

		return Response::json($order);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$order = Order::find($id);
		return Response::json($order);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{


		$order = Order::find($id);

		if($order->user == Auth::user()){


			if (Request::isJson())
			{


			    $input = Input::json()->all();




			    DB::table('order_item')->where('order_id', '=', $id)->delete();



			    foreach($input["items"] as $item){

				   	DB::table('order_item')->insert( array(
				   								'order_id' => $id,
				   								'quantity' => $item['quantity'],
				   								'item_id'	=> $item['item_id'],
				   								'price' => $item['price'],
				   								)
											);

			    }

			return Response::json(array('action'=>'update-order', 'status' => 'success'));


			}

			return Response::json(array('action'=>'update-order', 'status' => 'failure', 'message' => 'no json received'));
		}

		return Response::json(array('action'=>'update-order', 'status' => 'failure', 'message' => 'this order doesnt belong to this user'));
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$order = Order::find($id);
		$order->active = false;
		$order->table->taken = false;
		$order->table->save();
		if($order->save()){
			return Response::json($order);
		}
	}


}
