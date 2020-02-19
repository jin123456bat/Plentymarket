<?php

namespace Plentymarket\Extensions\Filters;

use Plentymarket\Extensions\AbstractFilter;
use Plentymarket\Helper\LanguageMap;
use Plentymarket\Helper\MemoryCache;
use Plentymarket\Services\ConfigService;

/**
 * Class NumberFormatFilter
 * @package Plentymarket\Extensions\Filters
 */
class NumberFormatFilter extends AbstractFilter
{
	use MemoryCache;

	public function __construct ()
	{
		parent::__construct();
	}

	/**
	 * Return the available filter methods
	 * @return array
	 */
	public function getFilters (): array
	{
		return [
			"formatDecimal" => "formatDecimal",
			"formatMonetary" => "formatMonetary",
			"trimNewlines" => "trimNewlines",
			"formatDateTime" => "formatDateTime"
		];
	}

	/**
	 * Format incorrect JSON ENCODED dateTimeFormat
	 * @param $value
	 * @return string
	 */
	public function formatDateTime ($value): string
	{
		if (strpos($value, '+') === false && !is_object($value)) {
			$value = str_replace(' ', '+', $value);
		}

		return $value;
	}

	/**
	 * Format the given value to decimal
	 * @param float $value
	 * @param int $decimal_places
	 * @return string
	 */
	public function formatDecimal ($value, int $decimal_places = -1): string
	{
		if ($decimal_places < 0) {
			$decimal_places = pluginApp(ConfigService::class)->getTemplateConfig('format.number_decimals');
		}

		if ($decimal_places === "") {
			$decimal_places = 0;
		}
		$decimal_separator = pluginApp(ConfigService::class)->getTemplateConfig('format.separator_decimal');
		$thousands_separator = pluginApp(ConfigService::class)->getTemplateConfig('format.separator_thousands');
		return number_format($value, $decimal_places, $decimal_separator, $thousands_separator);
	}

	/**
	 * Format the given value to currency
	 * @param $value
	 * @param $currencyISO
	 * @return string
	 */
	public function formatMonetary ($value, $currencyISO): string
	{
		if (!is_null($value) && !is_null($currencyISO) && strlen($currencyISO)) {
			$value = $this->trimNewlines($value);
			$currencyISO = $this->trimNewlines($currencyISO);

			$formatter = $this->fromMemoryCache(
				"formatter.$currencyISO",
				function () use ($currencyISO) {
					$locale = LanguageMap::getLocale();

					$formatter = numfmt_create($locale, \NumberFormatter::CURRENCY);

					if (pluginApp(ConfigService::class)->getTemplateConfig('currency.format') === 'symbol') {
						$formatter->setTextAttribute(\NumberFormatter::CURRENCY_CODE, $currencyISO);
					} else {
						$formatter->setSymbol(\NumberFormatter::CURRENCY_SYMBOL, $currencyISO);
					}

					if (pluginApp(ConfigService::class)->getTemplateConfig('format.use_locale_currency_format') === "0") {
						$decimal_separator = pluginApp(ConfigService::class)->getTemplateConfig('format.separator_decimal');
						$thousands_separator = pluginApp(ConfigService::class)->getTemplateConfig('format.separator_thousands');
						$formatter->setSymbol(\NumberFormatter::MONETARY_SEPARATOR_SYMBOL, $decimal_separator);
						$formatter->setSymbol(\NumberFormatter::MONETARY_GROUPING_SEPARATOR_SYMBOL, $thousands_separator);
						$formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, pluginApp(ConfigService::class)->getTemplateConfig('format.number_decimals', 2));
					}

					return $formatter;
				}
			);

			return $formatter->format($value);
		}

		return '';
	}

	/**
	 * Trim newlines from string
	 * @param string $value
	 * @return string
	 */
	public function trimNewlines ($value): string
	{
		return preg_replace('/\s+/', '', $value);
	}
}
