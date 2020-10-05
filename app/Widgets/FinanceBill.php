<?php namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

class FinanceBill extends AbstractWidget {

    /**
     * You can treat this method just like a controller action.
     * Return a view or anything else you want to display
     */
	public function run()	{
		$bill = \ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
		return \View::make('widgets.bill', [ 'bill' => $bill, ]);
	}
}
