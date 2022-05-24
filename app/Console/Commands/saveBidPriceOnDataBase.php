<?php

namespace App\Console\Commands;

use App\Models\Persisting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class saveBidPriceOnDataBase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:saveBidPriceOnDataBase {symbol} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Esse comando salvará os dados de preço na base de dados com base na criptomoeda informada.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //Obtêm a moeda como parâmetro
        $symbol = $this->argument('symbol');

        $json = Http::get('https://testnet.binancefuture.com/fapi/v1/ticker/price?symbol='.$symbol)->json();

        Persisting::Create([
            'symbol' => $json['symbol'],
            'bidPrice' => $json['price'],
        ]);
    
    }
}
