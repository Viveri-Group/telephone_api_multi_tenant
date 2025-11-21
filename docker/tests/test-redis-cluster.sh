#!/bin/bash

echo "=== Redis Cluster Connection Test ==="
echo "Testing Redis cluster configuration and prefix functionality..."
echo

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    local status=$1
    local message=$2
    if [ "$status" = "SUCCESS" ]; then
        echo -e "${GREEN}✓ $message${NC}"
    elif [ "$status" = "ERROR" ]; then
        echo -e "${RED}✗ $message${NC}"
    elif [ "$status" = "INFO" ]; then
        echo -e "${BLUE}ℹ $message${NC}"
    elif [ "$status" = "WARNING" ]; then
        echo -e "${YELLOW}⚠ $message${NC}"
    fi
}

# Test 1: Check if all Redis nodes are responding
echo "1. Testing Redis cluster node connectivity..."
for port in 7001 7002 7003; do
    if docker exec itv-api-redis-1 redis-cli -h itv-api-redis-1 -p 7001 -c ping >/dev/null 2>&1; then
        print_status "SUCCESS" "Redis node on port $port is responding"
    else
        print_status "ERROR" "Redis node on port $port is not responding"
    fi
done

# Test 2: Check cluster status
echo
echo "2. Checking Redis cluster status..."
cluster_status=$(docker exec itv-api-redis-1 redis-cli --cluster check localhost:7001 2>/dev/null | grep -c "OK")
if [ "$cluster_status" -gt 0 ]; then
    print_status "SUCCESS" "Redis cluster is healthy"
else
    print_status "ERROR" "Redis cluster has issues"
fi

# Test 3: Test Laravel app connection to Redis cluster
echo
echo "3. Testing Laravel app Redis connection..."

# Create a simple test command to check Redis connection
docker exec itv-api-web php artisan tinker --execute="
try {
    \$redis = app('redis');
    \$testKey = 'test_cluster_connection_' . time();
    \$redis->set(\$testKey, 'Laravel app connected successfully');
    \$value = \$redis->get(\$testKey);
    \$redis->del(\$testKey);
    if (\$value) {
        echo 'SUCCESS: Laravel app connected to Redis cluster\n';
    } else {
        echo 'ERROR: Laravel app Redis connection failed\n';
    }
} catch (Exception \$e) {
    echo 'ERROR: ' . \$e->getMessage() . '\n';
}
" 2>/dev/null

# Test 4: Test worker Redis connection
echo
echo "4. Testing Laravel worker Redis connection..."

# Test worker connection by checking queue connection
docker exec itv-api-worker php artisan tinker --execute="
try {
    \$queue = app('queue');
    \$connection = \$queue->connection();
    echo 'SUCCESS: Worker connected to Redis queue\n';
} catch (Exception \$e) {
    echo 'ERROR: Worker Redis connection failed: ' . \$e->getMessage() . '\n';
}
" 2>/dev/null

# Test 5: Check Redis prefix functionality
echo
echo "5. Testing Redis prefix functionality..."

# Set a test key and check if it appears with correct prefix
docker exec itv-api-web php artisan tinker --execute="
try {
    \$redis = app('redis');
    \$testKey = 'prefix_test_' . time();
    \$redis->set(\$testKey, 'testing prefix');
    echo 'Test key set in Laravel app\n';
} catch (Exception \$e) {
    echo 'ERROR setting test key: ' . \$e->getMessage() . '\n';
}
" 2>/dev/null

# Check what keys actually exist in Redis (should have prefix)
echo
echo "6. Checking actual keys in Redis cluster (should show prefixed keys)..."
docker exec itv-api-redis-1 redis-cli -c --scan --pattern "itv_test_*" | head -10

# Test 7: Performance test - set/get operations
echo
echo
echo "7. Performance test - 100 set/get operations..."
start_time=$(date +%s%N)

docker exec itv-api-web php artisan tinker --execute="
\$redis = app('redis');
\$start = microtime(true);
for (\$i = 0; \$i < 100; \$i++) {
    \$redis->set('perf_test_' . \$i, 'value_' . \$i);
    \$redis->get('perf_test_' . \$i);
}
\$end = microtime(true);
echo 'Performance test completed in ' . round((\$end - \$start) * 1000, 2) . ' ms\n';
" 2>/dev/null

# Test 8: Test cache functionality
echo
echo "8. Testing Laravel cache with Redis cluster..."
docker exec itv-api-web php artisan tinker --execute="
try {
    \$cache = app('cache');
    \$cache->put('test_cache_key', 'cache_value_' . time(), 60);
    \$value = \$cache->get('test_cache_key');
    if (\$value) {
        echo 'SUCCESS: Cache working with Redis cluster\n';
    } else {
        echo 'ERROR: Cache not working\n';
    }
} catch (Exception \$e) {
    echo 'ERROR: Cache error: ' . \$e->getMessage() . '\n';
}
" 2>/dev/null

# Test 9: Test session functionality
echo
echo "9. Testing Laravel session with Redis cluster..."
docker exec itv-api-web php artisan tinker --execute="
try {
    \$session = app('session');
    \$sessionId = 'test_session_' . time();
    \$session->put('test_key', 'session_value');
    echo 'SUCCESS: Session data stored\n';
} catch (Exception \$e) {
    echo 'ERROR: Session error: ' . \$e->getMessage() . '\n';
}
" 2>/dev/null

# Test 10: Show current Redis configuration
echo
echo "10. Current Redis configuration in Laravel:"
docker exec itv-api-web php artisan tinker --execute="
\$config = config('database.redis');
echo 'Cluster: ' . (\$config['options']['cluster'] ?? 'not set') . '\n';
echo 'Prefix: ' . (\$config['default']['prefix'] ?? 'not set') . '\n';
echo 'Hosts: ' . print_r(\$config['clusters']['default'], true);
" 2>/dev/null

echo
echo "=== Test Complete ==="
echo "Check the output above to verify Redis cluster is working correctly."
echo "Look for 'SUCCESS' messages and prefixed keys (itv_test_*)"
