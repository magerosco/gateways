```
    docker exec -it gateway_service php artisan tinker
```

```
use Illuminate\Support\Facades\Redis;

try {
    Redis::set('test_key', 'Laravel conectado a Redis!');
    echo Redis::get('test_key');
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
```
```
# redis-cli
127.0.0.1:6379> KEYS *
```

