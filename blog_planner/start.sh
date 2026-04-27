#!/bin/sh
set -e

echo "==> Starting HHVM FastCGI on port 9000..."
hhvm \
    -m server \
    -vServer.Type=fastcgi \
    -vServer.Port=9000 \
    -vServer.AllowRunAsRoot=1 \
    -vServer.SourceRoot=/var/www/blog_planner \
    -vServer.DefaultDocument=index.php \
    -vLog.Level=Warning \
    -vLog.UseLogFile=true \
    -vLog.File=/tmp/hhvm.log \
    -vRepo.Central.Path=/tmp/hhvm.hhbc \
    2>&1 | tee /tmp/hhvm.log &

HHVM_PID=$!

echo "==> Waiting for HHVM to be ready (up to 30s)..."
COUNT=0
while [ $COUNT -lt 30 ]; do
    if grep -qi "fastcgi\|started\|listening" /tmp/hhvm.log 2>/dev/null; then
        echo "==> HHVM is ready!"
        break
    fi
    if ! kill -0 $HHVM_PID 2>/dev/null; then
        echo "==> ERROR: HHVM crashed! Full log:"
        cat /tmp/hhvm.log
        exit 1
    fi
    sleep 1
    COUNT=$((COUNT + 1))
done

echo "==> Starting Nginx..."
nginx -g 'daemon off;'  