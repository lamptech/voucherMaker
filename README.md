# voucherMaker
A PHP class to generate and validate unique codes for use in vouchers, tickets, serial numbers etc.

Usage:

To generate voucher code / serial

$voucher = voucher_helper::genVoucher();

print_r($voucher); 

Array
(
    [pwd] => 5D6D0 65955 12379 8
    [serial] => 23722A 101A53 367603
)

to validate:-

if(voucher_helper::validateVoucher('5D6D0 65955 12379 8')) {
// Voucher valid
} else {
// voucher invalid
}


