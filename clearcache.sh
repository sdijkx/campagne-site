#!/bin/bash
rm -rf app/cache/*
find app/cache/ -type d -exec chmod 777 {} \;
find app/cache/ -type f -exec chmod 666 {} \;
