#!/bin/bash

php artisan make:model Transaction -m
php artisan make:model TransactionDetail -m
php artisan make:controller TransactionController
php artisan make:migration create_transactions_table
php artisan make:migration create_transaction_details_table
