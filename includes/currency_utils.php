<?php

/**
 * Currency utility functions
 */

// Tỷ giá USD/VND (có thể cập nhật từ API hoặc database)
define('USD_TO_VND_RATE', 24500);

/**
 * Convert USD to VND
 * @param float $usd_amount Amount in USD
 * @return float Amount in VND
 */
function usd_to_vnd($usd_amount)
{
  return $usd_amount * USD_TO_VND_RATE;
}

/**
 * Format currency in VND
 * @param float $amount Amount in VND
 * @return string Formatted amount with VND symbol
 */
function format_vnd($amount)
{
  return number_format($amount, 0, ',', '.') . ' VNĐ';
}

/**
 * Format currency in USD
 * @param float $amount Amount in USD
 * @return string Formatted amount with USD symbol
 */
function format_usd($amount)
{
  return '$' . number_format($amount, 2);
}
