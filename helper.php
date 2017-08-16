<?

/**
 * Project: gladi.
 * File: helper.php
 * User: Jethro
 * Date: 16/08/2017
 * Time: 12:28
 */


class voucher_helper {

    public static function validateVoucher($voucher) {
        // 2BB7 1858 8681 3
        // 2C0C2 DD3C6 C1644 3
        // 01234 56789
        $chk = true;
        $v = preg_replace('/\ /', '', strtoupper($voucher));
        $p['p1'] = substr($v, 0, 4);
        $p['p2'] = substr($v, 5, 4);
        $p['p3'] = substr($v, 10, 4);
        $p['c1'] = substr($v, 4, 1);
        $p['c2'] = substr($v, 9, 1);
        $p['c3'] = substr($v, 14, 1);
        $p['c4'] = substr($v, 15, 1);

        if (voucher_helper::genChkSum($p['p1'])!= $p['c1']) {$chk = false;}
        if (voucher_helper::genChkSum($p['p2'])!= $p['c2']) {$chk = false;}
        if (voucher_helper::genChkSum($p['p3'])!= $p['c3']) {$chk = false;}
        if (voucher_helper::genChkSum($p['p1'])!= $p['c1']) {$chk = false;}
        if (voucher_helper::genChkSum(voucher_helper::genChkSum($p['p1']).voucher_helper::genChkSum($p['p2']).voucher_helper::genChkSum($p['p3']))!=$p['c4']) {$chk = false;}

        return $chk;
    }

    public static function genVoucher($concat = false) {
        mt_srand((double) microtime() * 10000);
        $charid = strtoupper(md5(uniqid(rand(), true).uniqid(rand(), true)));
        $pwd  = substr($charid,  0, 4); //password(10);
        $pwd1  = substr($charid,  4, 4); //password(10);
        $pwd2  = substr($charid,  8, 4); //password(10);
        $srl  = substr($charid,  9, 17);

        $c1 = voucher_helper::genChkSum($pwd);
        $c2 = voucher_helper::genChkSum($pwd1);
        $c3 = voucher_helper::genChkSum($pwd2);
        $c4 = voucher_helper::genChkSum($c1.$c2.$c3);
        $result['pwd'] =$pwd.$c1." ".$pwd1.$c2." ".$pwd2.$c3." ".$c4;
        $result['serial'] = $srl.voucher_helper::genChkSum($srl);
        $result['serial'] = substr($result['serial'], 0, 6)." ".substr($result['serial'], 6, 6)." ".substr($result['serial'], 12, 6);

        if($concat) {
            $result['pwd'] = preg_replace('/\ /','', $result['pwd']);
            $result['serial'] = preg_replace('/\ /','', $result['serial']);
        }

        return $result;
    }


    public static function genChkSum($in) {

        $a = str_split($in, 1);
        $check = '';
        foreach ($a as $v) {
            $check .= ord($v);
        }
        return voucher_helper::chkSum($check);
    }

    public static function chkSum($input) {

        # Calculate sum
        $sum = 0;
        $pos = 0;

        $length = strlen($input)+1;
        $r = strrev($input);

        while ( $pos < $length - 1 ) {

            $odd = $r[ $pos ] * 2;
            if ( $odd > 9 ) {$odd -= 9;}

            $sum += $odd;

            if ( $pos != ($length - 2) ) {$sum += $r[ $pos +1 ];}
            $pos += 2;
        }

        # Calculate check digit
        $checkdigit = (( floor($sum/10) + 1) * 10 - $sum) % 10;
        return $checkdigit;

    }
}