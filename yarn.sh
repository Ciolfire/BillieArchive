#!/bin/sh

# npm update
yarn cache clean && yarn install
yarn watch