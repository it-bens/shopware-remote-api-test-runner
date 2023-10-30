#!/bin/bash
set -eo pipefail

# Check if Apache is running
if ! pidof apache2 > /dev/null; then
    echo "Apache is not running."
    exit 1
fi

# Check if MariaDB is running
if ! pidof mysqld > /dev/null; then
    echo "MariaDB is not running."
    exit 1
fi

echo "Health check passed."
exit 0
