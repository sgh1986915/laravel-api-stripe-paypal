<?php
class InvoiceController extends BaseController {

	public function show()
	{
		$invoices = Invoice::all()->toArray();
		return View::make('invoices.view')->with('invoices',$invoices);

	}
}