#Class API Trading Indodax

* Mempermudah memngakses API Indodax baik **API Public** maupun **API Private**.


Name | Description | Type
------------ | ------------ | ------------ 

getServerTime() | Mendapatkan waktu server indodax | Public
getPairs() | mendapatkan pasangan COIN yang tersedia | Public
getPriceInt() | mendapatkan kanaikan harga dari setiap pasangan COIN | Public
getSummaries() | mendapatkan rangkuman COIN | Public
getTickerall() | mendapatkan seluruh harga COIN | Public
getTicker($pair_id) | mendapatkan harga COIN tertentu | Public
getTrades($pair_id) | mendapatkan data order COIN tertentu | Public
getDepth($pair_id) | mendapatkan daftar volume beli dan jual COIN tertentu | Public
InfoAccount() | mendapatkan detail info akun | Private
TransHistory() | mendapatkan riwayat deposit dan withdrawl | Private
Trade($data) | lakukan pembelian atau penjualan COIN | Private
TradeHistory($data) | mendapatkan daftar order yang pernal dilakukan | Private
OpenOrder($data) | mengecek data open order | Private
OrderHistory() | mendapatkan daftar histori order | Private
GetOrder($data) | Mendapatkan Data Order  | Private
CancelOrder($data) | melakukan pembatalan order | Private
