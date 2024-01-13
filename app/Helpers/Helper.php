<?php

function getqty( $i, $products, $qty ) {
    $a = array_search( $i, $products );
    $b = $qty[ $a ];
    return $b;
}

function getTotalAmount( $orderid ) {
    $orders = DB::table( 'orders' )->where( 'order_id', $orderid )->get();
    $ts = 0;
    foreach ( $orders as $item ) {
        if ( $item->status == 'pending' ) {
            $ts = $ts + ( $item->quantity * $item->price );
        } else {
            $ts = $ts + ( $item->approvedquantity * $item->price );
        }
    }
    $tsd = $ts - ( $ts * 0.01 * $orders[ 0 ]->discount );
    return $tsd;
}

function updateMainStatus( $orderid ) {
    $order = DB::table( 'orders' )->where( 'order_id', $orderid )->get();
    $tc = count( $order );
    $cc = 0;
    $rc = 0;
    foreach ( $order as $item ) {
        if ( $item->status == 'approved' ) {
            $cc = $cc + 1;
        } elseif ( $item->status == 'rejected' ) {
            $cc = $cc + 1;
            $rc = $rc + 1;
        }
    }
    if ( $order[ 0 ]->delivered == 'on' && $tc !== $rc && $tc == $cc + $rc) {
        $result = 'green';
        $del = "on";
        $cln = "delivered";
    } elseif ( $order[ 0 ]->clnstatus == 'packorder' && $tc !== $rc && $tc == $cc + $rc) {
        $result = 'deep-purple';
        $del = NULL;
        $cln = "packorder";
    } elseif ( $tc == $cc && $tc == $rc ) {
        $result = 'red';
        $del = NULL;
        $cln = NULL;
    } elseif ( $tc == $cc ) {
        $result = 'amber darken-1';
        $del = NULL;
        $cln = NULL;
    } else {
        $result = 'blue';
        $del = NULL;
        $cln = NULL;
    }
    DB::table( 'orders' )->where( 'order_id', $orderid )->update( [
        'mainstatus'=>$result,
        'delivered'=>$del,
        'clnstatus'=>$cln
    ] );
}
function money($money){
    $decimal = (string)($money - floor($money));
    $money = floor($money);
    $length = strlen($money);
    $m = '';
    $money = strrev($money);
    for($i=0;$i<$length;$i++){
        if(( $i==3 || ($i>3 && ($i-1)%2==0) )&& $i!=$length){
            $m .=',';
        }
        $m .=$money[$i];
    }
    $result = strrev($m);
    $decimal = preg_replace("/0\./i", ".", $decimal);
    $decimal = substr($decimal, 0, 3);
    // if( $decimal != '0'){
    // $result = $result.$decimal;
    // }
    return $result;
}