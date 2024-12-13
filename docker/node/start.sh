#!/bin/sh

# install NPM dependencies
echo "Installing NPM packages..."
npm install

# start Vite dev server
exec npm run dev
