<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class osamt extends Command {
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'cron:osamt';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Command description';

    /**
    * Execute the console command.
    */

    public function handle() {
        foreach ( DB::table( 'customers' )->orderBy( 'id', 'ASC' )->lazy() as $item ) {
            $id = $item->id;
            $payment = DB::table( 'payments' )
            ->where( 'deleted', null )
            ->where( 'user_id', $id )
            ->selectRaw( '*, SUM(amount) as sum' )
            ->groupBy( 'user_id' )
            ->first();
            $order = DB::table( 'orders' )
            ->where(['deleted_at' => null, 'status' => 'approved', 'save' => null, 'user_id' => $id])
            ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sum')
            ->where( 'status', 'approved' )
            ->groupBy( 'user_id' )
            ->first();
            $slr = DB::table( 'salesreturns' )
            ->where( 'user_id', $id )
            ->selectRaw( '*, SUM(quantity * price) as sum, SUM(discount * 0.01 * quantity * price) as dis' )
            ->groupBy( 'user_id' )
            ->first();
            $exp = DB::table( 'expenses' )
            ->where( 'user_id', $id )
            ->selectRaw( '*, SUM(amount) as sum' )
            ->groupBy( 'user_id' )
            ->first();
            $cus = DB::table( 'customers' )->where( 'id', $id )->first();

            $od = 0;
            $oc = 0;

            if ( $order != NULL ) {
                $or = $order->sum;
            } else {
                $or = 0;
            }
            if ( $exp != NULL ) {
                $ex = $exp->sum;
            } else {
                $ex = 0;
            }
            if ( $slr != NULL ) {
                $sr = $slr->sum-$slr->dis;
            } else {
                $sr = 0;
            }
            if ( $payment != NULL ) {
                $py = $payment->sum;
            } else {
                $py = 0;
            }

            $tdb = $od+$or+$ex;
            $tcb = $oc+$py+$sr;

            if ( $tdb > $tcb ) {
                $result = array( 'red', $tdb-$tcb );
                // return $result;
            } elseif ( $tdb < $tcb ) {
                $result = array( 'green', $tcb-$tdb );
                // return $result;
            } else {
                $result = array( 'green', 0 );
                // return $result;
            }
            DB::table( 'customers' )->where( 'id', $id )->update( [
                'balance'=>implode( '|', $result )
            ] );
        }
    }
}
