<?php

namespace App\Console\Commands;

use App\Models\Persisting;
use Illuminate\Console\Command;

class checkAvgBigPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:checkAvgBigPrice {symbol}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Esse comando verificar o preço médio e informar se o preço (último) está menor do que 0.5% do que o preço médio (as últimas 100 entradas de preço)';

    /**
     * Execute the console command.
     *
     * @return string
     */
    public function handle()
    {
        //Obtêm a moeda como parâmetro
        $symbol = $this->argument('symbol');

        //Obtêm a média das ultimas 100 entradas da moeda específica
        $bidAvgPrice = Persisting::where('symbol', $symbol)
        ->orderBy('symbol', 'ASC')
        ->limit(100)->pluck('bidPrice')->avg();

        //Obtêm a ultima entrada da moeda específica
        $last = Persisting::select('bidPrice')
                ->orderBy('id', 'DESC')
                ->first();

        //Obtêm a porcentagem do preço medio
        $result = $bidAvgPrice * (0.5 / 100);

        //retorna a mensagem coformer o valor

        if ($last['bidPrice'] < $result) {
            echo "O preço da ultima entrada da moeda {$symbol} está 0.5% menor que o preço médio";
        }else{
            echo "O preço da ultima entrada da moeda {$symbol} não está 0.5% menor que o preço médio";
        }

    }
}
