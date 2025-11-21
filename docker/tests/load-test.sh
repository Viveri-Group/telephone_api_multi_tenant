#!/bin/bash

# Load Testing Script for ITV Telephone API
# Usage: ./load-test.sh [concurrent_users] [duration_seconds] [endpoint]

CONCURRENT=${1:-100}
DURATION=${2:-60}
ENDPOINT=${3:-"http://localhost"}

echo "🔥 LOAD TESTING CONFIGURATION:"
echo "   Concurrent Users: $CONCURRENT"
echo "   Duration: ${DURATION}s"
echo "   Endpoint: $ENDPOINT"
echo "   Target: 500-1000 concurrent requests"
echo ""

# Check if apache bench is available
if ! command -v ab &> /dev/null; then
    echo "❌ Apache Bench (ab) not found. Installing..."
    sudo apt-get update && sudo apt-get install -y apache2-utils
fi

echo "🚀 Starting load test..."
echo "📊 Monitor performance with: ./monitor-performance.sh"
echo ""

# Simple health check endpoint test
echo "=== HEALTH CHECK TEST ==="
ab -n 1000 -c 50 "$ENDPOINT/health"
echo ""

# Main application test (adjust endpoint as needed)
echo "=== MAIN APPLICATION TEST ==="
echo "Testing $CONCURRENT concurrent users for ${DURATION}s..."

# Calculate total requests for the duration
TOTAL_REQUESTS=$((CONCURRENT * DURATION / 2))  # Rough estimate

ab -n $TOTAL_REQUESTS -c $CONCURRENT -t $DURATION "$ENDPOINT/"

echo ""
echo "🎯 PERFORMANCE TARGETS:"
echo "   ✅ Good: <100ms avg response time"
echo "   ✅ Good: >95% success rate"
echo "   ✅ Good: <500 failed requests"
echo "   ⚠️  Warning: Memory usage >80%"
echo "   ❌ Critical: PHP-FPM max children reached"
echo ""

echo "📈 Check PHP-FPM status:"
echo "curl http://localhost/fpm-status?json | jq"
echo ""

echo "💡 Optimization Tips:"
echo "1. If max children reached: Increase pm.max_children (but watch RAM)"
echo "2. If high memory usage: Decrease memory_limit or pm.max_children"
echo "3. If slow responses: Check slow log and optimize queries"
echo "4. If connection errors: Increase listen.backlog and system limits"
