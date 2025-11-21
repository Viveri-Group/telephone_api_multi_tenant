#!/bin/bash

# High Load Performance Monitoring Script
# Usage: ./monitor-performance.sh [interval_seconds]

INTERVAL=${1:-5}
echo "=== ITV Telephone API Performance Monitor ==="
echo "Monitoring every ${INTERVAL} seconds. Press Ctrl+C to stop."
echo ""

while true; do
    clear
    echo "=== $(date) ==="
    echo ""
    
    echo "🔥 CONTAINER RESOURCE USAGE:"
    docker stats --no-stream --format "table {{.Container}}\t{{.CPUPerc}}\t{{.MemUsage}}\t{{.MemPerc}}\t{{.NetIO}}" | head -7
    echo ""
    
    echo "📊 PHP-FPM STATUS:"
    if curl -s http://localhost/fpm-status?json >/dev/null 2>&1; then
        curl -s http://localhost/fpm-status?json | jq -r '
        "Pool: " + .pool + 
        " | Active: " + (.["active processes"] | tostring) + "/" + (.["total processes"] | tostring) +
        " | Queue: " + (.["listen queue"] | tostring) + "/" + (.["max listen queue"] | tostring) +
        " | Accepted: " + (.["accepted conn"] | tostring) +
        " | Max Children Reached: " + (.["max children reached"] | tostring)'
    else
        echo "❌ PHP-FPM status not accessible"
    fi
    echo ""
    
    echo "🚀 NGINX STATUS:"
    if curl -s http://localhost/health >/dev/null 2>&1; then
        echo "✅ Nginx responding normally"
        echo "📈 Active connections: $(ss -tuln | grep :80 | wc -l)"
    else
        echo "❌ Nginx not responding"
    fi
    echo ""
    
    echo "💾 MEMORY BREAKDOWN:"
    free -h | grep -E "(Mem|Swap)"
    echo ""
    
    echo "⚡ LOAD AVERAGE:"
    uptime
    echo ""
    
    echo "🔍 TOP PROCESSES BY CPU:"
    ps aux --sort=-%cpu | head -6 | awk 'NR==1 || /php-fpm|nginx|redis|mysql/'
    echo ""
    
    echo "📝 RECENT PHP-FPM SLOW LOGS:"
    # Check slow logs in container
    if docker exec itv-api-web test -f /tmp/php-fpm-slow.log 2>/dev/null; then
        docker exec itv-api-web tail -3 /tmp/php-fpm-slow.log 2>/dev/null || echo "No slow logs yet"
    else
        echo "No slow logs found (good performance!)"
    fi
    echo ""
    
    echo "🌐 NETWORK CONNECTIONS:"
    echo "ESTABLISHED: $(ss -an | grep ESTABLISHED | wc -l)"
    echo "TIME_WAIT: $(ss -an | grep TIME-WAIT | wc -l)"
    echo "LISTEN: $(ss -tln | grep :80 | wc -l) (nginx)"
    echo ""
    
    echo "Press Ctrl+C to stop monitoring..."
    sleep $INTERVAL
done
