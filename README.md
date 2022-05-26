# Seja bem-vindo(a)

## Aqui nesta breve documentação veremos como Instalar  e configura esté projeto, Alem disso como foi desenvolvido o raciocínio. 

## Requisitos
- PHP ^7.3
- Laravel
- Composer
- MySQL


## 01 Instalação
Após extrair, basta você entrar na pasta raiz do projeto e rodar no seu terminal o comando:
**composer install** 

Lembre-se de parametrizar seu banco de dados conforme o arquivo **.env** 

```shell
 DB_CONNECTION=mysql
 DB_HOST=127.0.0.1
 DB_PORT=3306
 DB_DATABASE=banco
 DB_USERNAME=root
 DB_PASSWORD=
```

Depois de instalar as dependências e parametrizar seu banco vamos rodar o comando para as migrations:
**php artisan migrate**

**Pronto seu projeto está devidamente instalado e parametrizado.**

## 02 Projeto (IPORTO) 
obedecendo como foi especificado para o teste foi criado dois **command**, um para salva uma criptomoeda especifica no banco de dados e outra para verificar seu preço.

-saveBidPriceOnDataBase - **Salvar no banco**

-checkAvgBigPrice - **Verificar o preço**

Foi criado um model para manipularmos nossos dados, e os dois commands já mencionados. Tudo isso usando a interface da linha de comando **artisan**.

não achei necessario a criação de um controller, pois pela minha concepção apenas o command já entregaria a solução desejada. 

## Lógica - saveBidPriceOnDataBase

$symbol = $this->argument('symbol');

```php

  $json = Http::get('https://testnet.binancefuture.com/fapi/v1/ticker/price?symbol='.$symbol)->json();

  Persisting::Create([
        'symbol' => $json['symbol'],
        'bidPrice' => $json['price'],
  ]);
```

Para obter o resultado desejado eu usei o **http::get** para retorna o array da **API**, passando um parâmetro, creio que havia outras maneiras de como passa esté parâmetro porém não conseguir realizar. Logos após receber o json eu executo o create para salvar no banco de dados.

## Lógica - checkAvgBigPrice


```php

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

 //retorna a mensagem coformer o valo
 if ($last['bidPrice'] < $result) {
     echo "O preço da ultima entrada da moeda {$symbol} está 0.5% menor que o preço médio";
 }else{
     echo "O preço da ultima entrada da moeda {$symbol} não está 0.5% menor que preço médio";
 }

```

Nesta rotina eu obtive a média  das ultimas 100 entradas obtive a ultima entrada, isolei o valor da porcentagem do média e coloquei usei na condição para retorna a mensagem conforme estabelecida no teste.

## Considerações
Levei um tempo consideravel para realizar está tarefa pois nunca tinha criado **commands** antes. Outro fato que me reteve bastante tempo foi obter a mádia dos ultimos cem, fui para um caminho mais dificil e depois percebi que era mais fácil com tudo foi uma experiência gratificante e de muito aprendizado. O laravel tem me cativado cade linha de código a mais.

(IPORTO) Obrigado por está oportunidade e tempo. 

