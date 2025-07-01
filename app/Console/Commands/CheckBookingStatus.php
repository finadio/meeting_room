<?php

namespace App\Console\Commands;
use App\Http\Controllers\Admin\BookingsController;

use Illuminate\Console\Command;

class CheckBookingStatus extends Command
{
    // /**
    //  * The name and signature of the console command.
    //  *
    //  * @var string
    //  */
    // protected $signature = 'app:check-booking-status';

    // /**
    //  * The console command description.
    //  *
    //  * @var string
    //  */
    // protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    protected $signature = 'booking:check-status';
    protected $description = 'Check the booking status for expired bookings';

    public function __construct()
    {
        parent::__construct();
    }
    public function handle()
    {
        $bookingController = new BookingsController();
            $bookingController->checkBookingStatus();
            $this->info('Booking status checked successfully');
    }
}
