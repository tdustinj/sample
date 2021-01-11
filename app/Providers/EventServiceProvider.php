<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
	/**
	 * The event listener mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		'App\Events\inventoryUpdated' => [
			'App\Listeners\inventoryUpdated',
		],
		'App\Events\contactUpdated' => [
			'App\Listeners\contactUpdatedListener',
		],
		'App\Events\listingUpdated' => [
			'App\Listeners\listingUpdated',
		],
		'App\Events\productUpdated' => [
			'App\Listeners\productUpdated',
		],
		// 'App\Events\orderImported' => [
		//     'App\Listeners\orderImported',
		// ],
		'App\Events\workOrderUpdated' => [
			'App\Listeners\workOrderUpdated',
		],
		'App\Events\OrderImported' => [
			'App\Listeners\UpdateWorkorderTax',
		],
		'App\Events\AmazonFBAOrderImported' => [
			'App\Listeners\AmazonFBAInventoryAssign',
		],
		'App\Events\OrderCancelled' => [
			'App\Listeners\CancelOrder',
		],
		'App\Events\OrderItemsImport' => [
			'App\Listeners\ImportItems',
		],
		'App\Events\OrderDoesNotExist' => [
			'App\Listeners\DeleteOrder',
		],
		'App\Events\WorkOrderNoteAdded' => [
			'App\Listeners\EmailWorkOrderNote'
		]
	];

	/**
	 * Register any events for your application.
	 *
	 * @return void
	 */
	public function boot()
	{
		parent::boot();

		//
	}
}
